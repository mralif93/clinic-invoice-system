<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display listing of invoices with filters.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['patient', 'creator'])->latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('id_number', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $perPage = (int) $request->input('per_page', 10);
        $invoices = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_invoices' => Invoice::count(),
            'total_revenue' => Invoice::whereIn('status', ['paid', 'partial'])->sum('paid_amount'),
            'outstanding' => Invoice::whereIn('status', ['unpaid', 'partial'])->get()->sum(fn ($i) => $i->total_amount - $i->paid_amount),
            'paid_count' => Invoice::where('status', 'paid')->count(),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Show form to create new invoice (POS Interface).
     */
    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $items = Item::orderBy('category')->orderBy('name')->get();
        $doctors = \App\Models\User::where('role', 'admin')
            ->orWhere('name', 'like', 'Dr.%')
            ->orderBy('name')
            ->get();

        if ($doctors->isEmpty()) {
            $doctors = \App\Models\User::orderBy('name')->get();
        }

        $invoiceNumber = Invoice::generateInvoiceNumber();
        $profile = \App\Models\ClinicProfile::getActiveProfile();

        return view('admin.invoices.create', compact('patients', 'items', 'doctors', 'invoiceNumber', 'profile'));
    }

    /**
     * Store newly created invoice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['nullable', 'exists:items,id'],
            'items.*.item_name' => ['required', 'string'],
            'items.*.item_code' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.tax' => ['nullable', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_received' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string'],
            'transaction_ref' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $totalTax = 0;
            $totalLineDiscount = 0;

            foreach ($validated['items'] as $item) {
                $qty = (int)$item['quantity'];
                $price = (float)$item['unit_price'];
                $disc = (float)($item['discount'] ?? 0);
                $tax = (float)($item['tax'] ?? 0);

                $subtotal += ($price * $qty);
                $totalLineDiscount += $disc;
                $totalTax += $tax;
            }

            $overallDiscount = (float)($validated['discount_amount'] ?? 0) + $totalLineDiscount;
            $grandTotal = max(0, $subtotal - $overallDiscount + $totalTax);
            $paidAmount = (float)($validated['payment_received'] ?? 0);

            $status = 'unpaid';
            if ($paidAmount >= $grandTotal && $grandTotal > 0) {
                $status = 'paid';
            } elseif ($paidAmount > 0) {
                $status = 'partial';
            }

            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'patient_id' => $validated['patient_id'],
                'user_id' => Auth::id(),
                'doctor_name' => $validated['doctor_name'],
                'subtotal' => $subtotal,
                'discount_amount' => $overallDiscount,
                'tax_amount' => $totalTax,
                'total_amount' => $grandTotal,
                'paid_amount' => min($paidAmount, $grandTotal),
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $qty = (int)$item['quantity'];
                $price = (float)$item['unit_price'];
                $disc = (float)($item['discount'] ?? 0);
                $tax = (float)($item['tax'] ?? 0);
                $lineSubtotal = ($price * $qty) - $disc + $tax;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $item['item_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'item_code' => $item['item_code'] ?? null,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $disc,
                    'tax' => $tax,
                    'subtotal' => $lineSubtotal,
                ]);
            }

            if ($paidAmount > 0) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => Auth::id(),
                    'amount' => min($paidAmount, $grandTotal),
                    'payment_method' => $validated['payment_method'] ?? 'cash',
                    'bank_name' => $validated['bank_name'] ?? null,
                    'transaction_ref' => $validated['transaction_ref'] ?? null,
                    'payment_date' => now(),
                    'is_reconciled' => true,
                ]);
            }

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', "Invoice {$invoice->invoice_number} created successfully.");
        });
    }

    /**
     * Show single invoice details with settlement and print actions.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'creator', 'items', 'payments.cashier']);
        $profile = \App\Models\ClinicProfile::getActiveProfile();
        return view('admin.invoices.show', compact('invoice', 'profile'));
    }

    /**
     * Add payment settlement to invoice.
     */
    public function settlePayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $invoice->due_amount],
            'payment_method' => ['required', 'string'],
            'bank_name' => ['nullable', 'string'],
            'transaction_ref' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            $newPayment = Payment::create([
                'invoice_id' => $invoice->id,
                'user_id' => Auth::id(),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'bank_name' => $validated['bank_name'] ?? null,
                'transaction_ref' => $validated['transaction_ref'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'payment_date' => now(),
                'is_reconciled' => true,
            ]);

            $totalPaid = $invoice->payments()->sum('amount');
            $status = $totalPaid >= $invoice->total_amount ? 'paid' : 'partial';

            $invoice->update([
                'paid_amount' => $totalPaid,
                'status' => $status,
            ]);
        });

        return back()->with('success', 'Payment of RM ' . number_format($validated['amount'], 2) . ' recorded successfully.');
    }

    /**
     * Void an invoice.
     */
    public function voidInvoice(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'min:5'],
        ]);

        $invoice->update([
            'status' => 'void',
            'void_reason' => $validated['void_reason'],
        ]);

        return back()->with('success', "Invoice {$invoice->invoice_number} has been voided.");
    }
}
