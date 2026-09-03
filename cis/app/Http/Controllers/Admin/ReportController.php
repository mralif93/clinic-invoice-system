<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * FR-5.1: Daily Cash Drawer & Shift Reconciliation.
     */
    public function dailyCashDrawer(Request $request)
    {
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $date = Carbon::parse($selectedDate);

        // Payments for the day
        $payments = Payment::with(['invoice.patient', 'cashier'])
            ->whereDate('payment_date', $date)
            ->latest()
            ->get();

        // Breakdown grouped by payment method
        $channelSummary = Payment::whereDate('payment_date', $date)
            ->select('payment_method', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        // Breakdown grouped by receiving bank/terminal
        $bankSummary = Payment::whereDate('payment_date', $date)
            ->whereNotNull('bank_name')
            ->select('bank_name', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('bank_name')
            ->get();

        $totalCollected = $payments->sum('amount');
        $cashTotal = $payments->where('payment_method', 'cash')->sum('amount');
        $cardTotal = $payments->where('payment_method', 'card')->sum('amount');
        $qrTotal = $payments->where('payment_method', 'qr')->sum('amount');

        $profile = \App\Models\ClinicProfile::getActiveProfile();

        return view('admin.reports.cash-drawer', compact(
            'selectedDate',
            'payments',
            'channelSummary',
            'bankSummary',
            'totalCollected',
            'cashTotal',
            'cardTotal',
            'qrTotal',
            'profile'
        ));
    }

    /**
     * FR-5.2 & FR-5.3: Bank Reconciliation & Terminal EDC Batch Matching.
     */
    public function bankReconciliation(Request $request)
    {
        $query = Payment::with(['invoice.patient', 'cashier'])
            ->where('payment_method', '!=', 'cash')
            ->latest();

        if ($request->filled('status')) {
            if ($request->status === 'reconciled') {
                $query->where('is_reconciled', true);
            } elseif ($request->status === 'unreconciled') {
                $query->where('is_reconciled', false);
            }
        }

        if ($request->filled('method') && $request->method !== 'all') {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_ref', 'like', "%{$search}%")
                  ->orWhere('batch_number', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 12);
        $electronicPayments = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_electronic' => Payment::where('payment_method', '!=', 'cash')->sum('amount'),
            'reconciled_amount' => Payment::where('payment_method', '!=', 'cash')->where('is_reconciled', true)->sum('amount'),
            'pending_amount' => Payment::where('payment_method', '!=', 'cash')->where('is_reconciled', false)->sum('amount'),
            'pending_count' => Payment::where('payment_method', '!=', 'cash')->where('is_reconciled', false)->count(),
        ];

        return view('admin.reports.bank-recon', compact('electronicPayments', 'stats'));
    }

    /**
     * Toggle payment reconciliation status.
     */
    public function toggleReconciled(Payment $payment)
    {
        $payment->update([
            'is_reconciled' => !$payment->is_reconciled,
        ]);

        $statusText = $payment->is_reconciled ? 'Reconciled' : 'Unreconciled';
        return back()->with('success', "Payment record ({$payment->transaction_ref}) marked as {$statusText}.");
    }

    /**
     * FR-5.4: Revenue Analytics, Aging Debt & Top Billed Treatments.
     */
    public function revenueAnalytics()
    {
        // Monthly revenue for past 6 months
        $monthlyRevenue = Payment::select(
            DB::raw("strftime('%Y-%m', payment_date) as month"),
            DB::raw("SUM(amount) as total")
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(6)
        ->get();

        // Aging Unpaid Bills
        $unpaidInvoices = Invoice::with('patient')
            ->whereIn('status', ['unpaid', 'partial'])
            ->get();

        $aging = [
            '0_30' => $unpaidInvoices->filter(fn ($i) => $i->created_at->diffInDays(now()) <= 30)->sum(fn ($i) => $i->due_amount),
            '31_60' => $unpaidInvoices->filter(fn ($i) => $i->created_at->diffInDays(now()) > 30 && $i->created_at->diffInDays(now()) <= 60)->sum(fn ($i) => $i->due_amount),
            '61_90' => $unpaidInvoices->filter(fn ($i) => $i->created_at->diffInDays(now()) > 60 && $i->created_at->diffInDays(now()) <= 90)->sum(fn ($i) => $i->due_amount),
            '90_plus' => $unpaidInvoices->filter(fn ($i) => $i->created_at->diffInDays(now()) > 90)->sum(fn ($i) => $i->due_amount),
        ];

        // Top billed treatments and medications
        $topBilled = InvoiceItem::select(
            'item_name',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(subtotal) as total_sales')
        )
        ->groupBy('item_name')
        ->orderBy('total_sales', 'desc')
        ->take(6)
        ->get();

        $stats = [
            'gross_billed' => Invoice::where('status', '!=', 'void')->sum('total_amount'),
            'total_collected' => Payment::sum('amount'),
            'total_outstanding' => $unpaidInvoices->sum(fn ($i) => $i->due_amount),
        ];

        $profile = \App\Models\ClinicProfile::getActiveProfile();

        return view('admin.reports.analytics', compact('monthlyRevenue', 'aging', 'topBilled', 'stats', 'unpaidInvoices', 'profile'));
    }
}
