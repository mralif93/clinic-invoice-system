<x-layouts.admin title="Invoice {{ $invoice->invoice_number }}">

    <div class="space-y-6">

        <!-- Top Action Navigation Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.invoices.index') }}" class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                    <i class="bx bx-arrow-back text-lg"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-mono tracking-tight">
                            {{ $invoice->invoice_number }}
                        </h1>
                        @if($invoice->status === 'paid')
                            <x-badge variant="emerald" dot="true">Settled &amp; Paid</x-badge>
                        @elseif($invoice->status === 'partial')
                            <x-badge variant="amber" dot="true">Partially Paid (RM {{ number_format($invoice->due_amount, 2) }} Due)</x-badge>
                        @elseif($invoice->status === 'void')
                            <x-badge variant="rose">Voided Invoice</x-badge>
                        @else
                            <x-badge variant="rose" dot="true">Unpaid Pending</x-badge>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Issued on <span class="font-mono">{{ $invoice->created_at->format('d M Y, h:i A') }}</span> by <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $invoice->creator?->name ?? 'Frontdesk' }}</span>
                    </p>
                </div>
            </div>

            <!-- Print Actions & Settlement CTA -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- 80mm Thermal Receipt Button -->
                <button
                    type="button"
                    onclick="document.getElementById('modal-thermal-receipt').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 shadow-xs transition cursor-pointer"
                >
                    <i class="bx bx-printer text-base text-amber-500"></i>
                    <span>80mm Thermal Receipt</span>
                </button>

                <!-- Formal A4 PDF Invoice Button -->
                <button
                    type="button"
                    onclick="document.getElementById('modal-a4-pdf').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 shadow-xs transition cursor-pointer"
                >
                    <i class="bx bxs-file-pdf text-base text-rose-500"></i>
                    <span>A4 Formal Invoice</span>
                </button>

                <!-- Record Payment CTA (if due amount remains) -->
                @if($invoice->status !== 'paid' && $invoice->status !== 'void')
                    <button
                        type="button"
                        onclick="document.getElementById('modal-record-payment').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 border border-emerald-500/30 transition cursor-pointer"
                    >
                        <i class="bx bx-credit-card text-base"></i>
                        <span>Record Payment</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Flash Notice -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
            </x-alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Patient & Invoice Line Items Card -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Patient Profile Card -->
                <x-card title="Patient Information">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 font-medium">Patient Full Name</span>
                            <div class="font-bold text-slate-900 dark:text-white text-sm mt-0.5">{{ $invoice->patient->name }}</div>
                            <div class="text-slate-500 dark:text-slate-400 font-mono mt-0.5">IC: {{ $invoice->patient->id_number }}</div>
                        </div>

                        <div>
                            <span class="text-slate-400 font-medium">Contact Phone</span>
                            <div class="font-mono font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $invoice->patient->phone }}</div>
                            <div class="text-slate-500 dark:text-slate-400 mt-0.5">{{ $invoice->patient->gender ?? 'Not specified' }} &bull; DOB: {{ $invoice->patient->date_of_birth ?? 'N/A' }}</div>
                        </div>

                        <div>
                            <span class="text-slate-400 font-medium">Attending Physician</span>
                            <div class="font-bold text-indigo-600 dark:text-indigo-400 text-sm mt-0.5">{{ $invoice->doctor_name }}</div>
                            <div class="text-[11px] text-rose-500 font-medium mt-0.5">
                                Allergies: {{ $invoice->patient->allergies ?: 'None known' }}
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Line Items Table Card -->
                <x-card title="Invoice Line Items Breakdown" noPadding="true">
                    <x-table :headers="[
                        'Item Description & SKU',
                        ['label' => 'Qty', 'align' => 'center'],
                        ['label' => 'Unit Price', 'align' => 'right'],
                        ['label' => 'Discount', 'align' => 'right'],
                        ['label' => 'Line Total', 'align' => 'right'],
                    ]">
                        @foreach($invoice->items as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-5">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $item->item_name }}</div>
                                    @if($item->item_code)
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $item->item_code }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-center font-mono">
                                    {{ $item->quantity }}
                                </td>
                                <td class="py-3.5 px-5 text-right font-mono">
                                    RM {{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="py-3.5 px-5 text-right font-mono text-slate-500 dark:text-slate-400">
                                    RM {{ number_format($item->discount, 2) }}
                                </td>
                                <td class="py-3.5 px-5 text-right font-mono font-bold text-slate-900 dark:text-white">
                                    RM {{ number_format($item->subtotal, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                </x-card>

                <!-- Clinical Remarks -->
                @if($invoice->notes)
                    <x-card title="Invoice Notes">
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                            {{ $invoice->notes }}
                        </p>
                    </x-card>
                @endif
            </div>

            <!-- Right Column: Calculation Summary & Settlement Audit Trail -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Financial Calculation Card -->
                <x-card title="Payment Balance Summary">
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-slate-500 dark:text-slate-400">
                            <span>Gross Line Items</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">RM {{ number_format($invoice->subtotal, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-medium">
                            <span>Discount Deductions</span>
                            <span class="font-mono">- RM {{ number_format($invoice->discount_amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1">
                                <span>Service Tax (SST)</span>
                                @if($invoice->tax_amount == 0)
                                    <span class="text-[9px] px-1.5 py-0.2 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 font-bold">Exempt</span>
                                @endif
                            </span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">RM {{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>

                        <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-between items-center text-slate-900 dark:text-white">
                            <span class="text-sm font-extrabold">Invoice Total</span>
                            <span class="text-lg font-black font-mono text-indigo-600 dark:text-indigo-400">RM {{ number_format($invoice->total_amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-slate-600 dark:text-slate-300 font-medium">
                            <span>Amount Settled</span>
                            <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">RM {{ number_format($invoice->paid_amount, 2) }}</span>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 flex justify-between items-center">
                            <span class="font-bold text-slate-700 dark:text-slate-300 text-xs">Balance Remaining</span>
                            <span class="text-base font-black font-mono {{ $invoice->due_amount > 0 ? 'text-amber-500' : 'text-emerald-500' }}">
                                RM {{ number_format($invoice->due_amount, 2) }}
                            </span>
                        </div>
                    </div>
                </x-card>

                <!-- Payments Settlement History Card -->
                <x-card title="Payment Transactions (Audit)" subtitle="{{ $invoice->payments->count() }} transaction(s) recorded">
                    <div class="space-y-3">
                        @forelse($invoice->payments as $payment)
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono text-sm">
                                        + RM {{ number_format($payment->amount, 2) }}
                                    </span>
                                    <x-badge variant="indigo" size="sm">
                                        {{ strtoupper($payment->payment_method) }}
                                    </x-badge>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400 text-[11px]">
                                    <span>{{ $payment->bank_name ?: 'Frontdesk Cash' }}</span>
                                    <span class="font-mono">{{ $payment->payment_date->format('d M Y') }}</span>
                                </div>
                                @if($payment->transaction_ref)
                                    <div class="text-[10px] text-slate-400 font-mono truncate">
                                        Ref: {{ $payment->transaction_ref }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 text-xs">
                                No payment settlements recorded yet.
                            </div>
                        @endforelse
                    </div>
                </x-card>

                <!-- Void Action Trigger -->
                @if($invoice->status !== 'void')
                    <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 text-xs space-y-2">
                        <div class="font-bold text-rose-700 dark:text-rose-400 flex items-center gap-1.5">
                            <i class="bx bx-shield-x"></i>
                            <span>Void Transaction</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-[11px] leading-relaxed">
                            Voiding an invoice marks the bill inactive in audit records. Requires justification.
                        </p>
                        <button
                            type="button"
                            onclick="document.getElementById('modal-void-invoice').classList.remove('hidden')"
                            class="text-rose-600 dark:text-rose-400 font-bold hover:underline cursor-pointer"
                        >
                            Proceed to void invoice &rarr;
                        </button>
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- 1. Modal: 80mm Thermal Receipt Preview & Print -->
    <x-modal name="thermal-receipt" title="80mm Thermal Receipt Preview" size="sm">
        <div id="thermal-receipt-printable" class="p-5 rounded-2xl bg-white text-black font-mono text-[11px] space-y-3 border border-slate-300 shadow-inner leading-relaxed select-text">
            <!-- Receipt Header -->
            <div class="text-center space-y-0.5 border-b border-dashed border-slate-400 pb-3">
                <div class="font-black text-sm uppercase tracking-tight">{{ $profile->clinic_name ?? 'POLIKLINIK PRIMA CIS' }}</div>
                <div>{{ $profile->address ?? 'No 12, Jalan Boulevard 3, Kuala Lumpur' }}</div>
                <div>Tel: {{ $profile->phone ?? '+603-8899 1234' }}</div>
                <div class="text-[10px]">SSM / Reg: {{ $profile->registration_number ?? '202601004921' }}</div>
            </div>

            <!-- Receipt Metadata -->
            <div class="text-[10px] space-y-0.5 border-b border-dashed border-slate-400 pb-2">
                <div class="flex justify-between"><span>Inv No:</span> <span class="font-bold">{{ $invoice->invoice_number }}</span></div>
                <div class="flex justify-between"><span>Date:</span> <span>{{ $invoice->created_at->format('d/m/Y H:i') }}</span></div>
                <div class="flex justify-between"><span>Doctor:</span> <span>{{ $invoice->doctor_name }}</span></div>
                <div class="flex justify-between"><span>Patient:</span> <span>{{ $invoice->patient->name }}</span></div>
                @if($invoice->patient->id_number)
                    <div class="flex justify-between"><span>IC/ID:</span> <span>{{ $invoice->patient->id_number }}</span></div>
                @endif
            </div>

            <!-- Itemized Lines -->
            <div class="space-y-1.5 border-b border-dashed border-slate-400 pb-2">
                @foreach($invoice->items as $item)
                    <div class="flex justify-between items-start gap-2">
                        <div class="truncate max-w-[170px]">{{ $item->quantity }}x {{ $item->item_name }}</div>
                        <div class="text-right shrink-0 font-bold">RM {{ number_format($item->subtotal, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Totals -->
            <div class="space-y-1 border-b border-dashed border-slate-400 pb-2">
                <div class="flex justify-between"><span>Subtotal:</span> <span>RM {{ number_format($invoice->subtotal, 2) }}</span></div>
                @if($invoice->discount_amount > 0)
                    <div class="flex justify-between text-rose-600"><span>Discount:</span> <span>- RM {{ number_format($invoice->discount_amount, 2) }}</span></div>
                @endif
                <div class="flex justify-between">
                    <span>SST (Service Tax):</span> 
                    <span>RM {{ number_format($invoice->tax_amount, 2) }}{{ $invoice->tax_amount == 0 ? ' (Exempt)' : '' }}</span>
                </div>
                <div class="flex justify-between font-black text-xs pt-1"><span>TOTAL DUE:</span> <span>RM {{ number_format($invoice->total_amount, 2) }}</span></div>
                <div class="flex justify-between font-semibold"><span>PAID AMOUNT:</span> <span>RM {{ number_format($invoice->paid_amount, 2) }}</span></div>
                @if($invoice->due_amount > 0)
                    <div class="flex justify-between font-bold text-amber-600"><span>BALANCE OUTSTANDING:</span> <span>RM {{ number_format($invoice->due_amount, 2) }}</span></div>
                @endif
            </div>

            <!-- Footer Message -->
            <div class="text-center text-[9px] pt-1 text-slate-600">
                {{ $profile->receipt_footer ?? 'Thank you for choosing us. Please keep this receipt for records & insurance claims.' }}
            </div>
        </div>

        <x-slot:footer>
            <div class="flex items-center justify-between gap-3 w-full">
                <button
                    type="button"
                    onclick="document.getElementById('modal-thermal-receipt').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-white transition cursor-pointer"
                >
                    Close Preview
                </button>

                <button
                    type="button"
                    onclick="printElement('thermal-receipt-printable', 'Receipt-{{ $invoice->invoice_number }}')"
                    class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center gap-2 transition cursor-pointer shadow-md"
                >
                    <i class="bx bx-printer text-base"></i>
                    <span>Print 80mm Receipt</span>
                </button>
            </div>
        </x-slot:footer>
    </x-modal>

    <!-- 2. Modal: Formal A4 PDF Invoice Preview -->
    <x-modal name="a4-pdf" title="A4 Formal Tax Invoice Preview" size="4xl">
        <div id="a4-pdf-printable" class="p-8 rounded-2xl bg-white text-slate-900 border border-slate-200 shadow-inner space-y-6 text-xs font-sans select-text">
            <!-- Formal Header -->
            <div class="flex justify-between items-start border-b border-slate-200 pb-6">
                <div class="space-y-1">
                    <div class="text-xl font-black text-indigo-700 uppercase tracking-tight">{{ $profile->clinic_name ?? 'POLIKLINIK PRIMA CIS' }}</div>
                    <p class="text-slate-600 text-[11px]">SSM / Reg No: {{ $profile->registration_number ?? '202601004921' }}</p>
                    <p class="text-slate-500 text-[11px]">{{ $profile->address ?? 'No 12, Jalan Boulevard 3, 50480 Kuala Lumpur' }} &bull; Tel: {{ $profile->phone ?? '+603-8899 1234' }}</p>
                    <p class="text-slate-500 text-[11px]">Email: {{ $profile->email ?? 'info@poliklinikprima.my' }}</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-black text-slate-900 font-mono tracking-wider">TAX INVOICE</div>
                    <div class="font-mono font-bold text-indigo-600 text-sm mt-1">{{ $invoice->invoice_number }}</div>
                    <div class="text-slate-400 text-[11px] font-mono mt-0.5">Date: {{ $invoice->created_at->format('d M Y') }}</div>
                    <div class="text-slate-400 text-[10px] font-mono">Status: <span class="uppercase font-bold text-emerald-600">{{ $invoice->status }}</span></div>
                </div>
            </div>

            <!-- Billed To & Physician Info -->
            <div class="grid grid-cols-2 gap-8 py-2">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <div class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Billed To (Patient)</div>
                    <div class="font-extrabold text-slate-900 text-sm">{{ $invoice->patient->name }}</div>
                    <div class="text-slate-600 font-mono">IC / Passport: {{ $invoice->patient->id_number }}</div>
                    <div class="text-slate-600">Phone: {{ $invoice->patient->phone }}</div>
                    <div class="text-slate-500 text-[11px]">{{ $invoice->patient->address }}</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1 text-right">
                    <div class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Clinical Reference</div>
                    <div class="font-bold text-slate-900">Attending: {{ $invoice->doctor_name }}</div>
                    <div class="text-slate-600">Billing Creator: {{ $invoice->creator?->name }}</div>
                    <div class="text-slate-600">Printed Date: {{ now()->format('d M Y, h:i A') }}</div>
                    @if($invoice->patient->allergies && $invoice->patient->allergies !== 'None')
                        <div class="text-rose-600 font-bold text-[11px]">Allergies Alert: {{ $invoice->patient->allergies }}</div>
                    @endif
                </div>
            </div>

            <!-- Line Items Table -->
            <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 font-bold uppercase text-[10px] text-slate-600">
                    <tr>
                        <th class="py-3 px-4">Item &amp; SKU Description</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Unit Rate (RM)</th>
                        <th class="py-3 px-4 text-right">Discount</th>
                        <th class="py-3 px-4 text-right">Amount (RM)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $item->item_name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $item->item_code }}</div>
                            </td>
                            <td class="py-3 px-4 text-center font-mono">{{ $item->quantity }}</td>
                            <td class="py-3 px-4 text-right font-mono">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-4 text-right font-mono">{{ number_format($item->discount, 2) }}</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Grand Summary -->
            <div class="flex justify-between items-start pt-2">
                <div class="max-w-xs text-[11px] text-slate-500 space-y-1">
                    <div class="font-bold text-slate-700">Terms &amp; Instructions:</div>
                    <p>{{ $profile->invoice_terms ?? 'Payment is due upon service rendering. All clinical medications non-refundable once dispensed.' }}</p>
                </div>

                <div class="w-72 space-y-1.5 text-xs font-mono">
                    <div class="flex justify-between text-slate-600"><span>Subtotal:</span> <span>RM {{ number_format($invoice->subtotal, 2) }}</span></div>
                    @if($invoice->discount_amount > 0)
                        <div class="flex justify-between text-rose-600"><span>Discounts:</span> <span>- RM {{ number_format($invoice->discount_amount, 2) }}</span></div>
                    @endif
                    <div class="flex justify-between text-slate-600">
                        <span>Service Tax (SST):</span> 
                        <span>RM {{ number_format($invoice->tax_amount, 2) }}{{ $invoice->tax_amount == 0 ? ' (Exempt)' : '' }}</span>
                    </div>
                    <div class="flex justify-between font-black text-sm text-slate-900 pt-2 border-t border-slate-300">
                        <span>Total Payable:</span> <span>RM {{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-600 font-bold"><span>Amount Paid:</span> <span>RM {{ number_format($invoice->paid_amount, 2) }}</span></div>
                    @if($invoice->due_amount > 0)
                        <div class="flex justify-between text-amber-600 font-bold"><span>Balance Due:</span> <span>RM {{ number_format($invoice->due_amount, 2) }}</span></div>
                    @endif
                </div>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex items-center justify-between gap-3 w-full">
                <button
                    type="button"
                    onclick="document.getElementById('modal-a4-pdf').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-white transition cursor-pointer"
                >
                    Close Preview
                </button>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        onclick="printElement('a4-pdf-printable', 'Invoice-{{ $invoice->invoice_number }}')"
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs flex items-center gap-2 transition cursor-pointer shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bx bx-printer text-base"></i>
                        <span>Print / Save as PDF</span>
                    </button>
                </div>
            </div>
        </x-slot:footer>
    </x-modal>

    <!-- 3. Modal: Settle Payment -->
    <x-modal name="record-payment" title="Record Payment Settlement" size="md">
        <form action="{{ route('admin.invoices.settle', $invoice->id) }}" method="POST" class="space-y-4">
            @csrf
            <x-input
                label="Settlement Amount (RM)"
                name="amount"
                type="number"
                step="0.01"
                min="0.01"
                value="{{ $invoice->due_amount }}"
                icon="bx bx-wallet"
                required
            />

            <div class="space-y-1.5">
                <label for="payment_method" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Payment Method
                </label>
                <select name="payment_method" id="payment_method" class="w-full py-2.5 px-3.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs">
                    <option value="cash">Cash Settlement</option>
                    <option value="qr">DuitNow QR Pay</option>
                    <option value="card">Credit / Debit Card (EDC)</option>
                    <option value="transfer">Bank Online Transfer</option>
                    <option value="panel">Insurance Panel / GL</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <x-input label="Bank / Terminal" name="bank_name" placeholder="e.g. Maybank" />
                <x-input label="Audit Ref / RRN" name="transaction_ref" placeholder="RRN #123456" />
            </div>

            <x-button type="submit" variant="success" size="md" icon="bx bx-check" class="w-full mt-2">
                Confirm Settlement
            </x-button>
        </form>
    </x-modal>

    <!-- 4. Modal: Void Invoice -->
    <x-modal name="void-invoice" title="Void Invoice Justification" size="md">
        <form action="{{ route('admin.invoices.void', $invoice->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 text-xs">
                Warning: Voiding this invoice cannot be undone and will be logged in the immutable audit trail.
            </div>

            <div class="space-y-1.5">
                <label for="void_reason" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Reason for Voiding <span class="text-rose-500">*</span>
                </label>
                <textarea
                    name="void_reason"
                    id="void_reason"
                    rows="3"
                    required
                    placeholder="Provide justification (e.g. Wrong items entered, patient cancelled consultation)..."
                    class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:border-indigo-500 focus:outline-none"
                ></textarea>
            </div>

            <x-button type="submit" variant="danger" size="md" icon="bx bx-trash" class="w-full">
                Authorize Void Action
            </x-button>
        </form>
    </x-modal>

    <!-- Isolated Print Helper Script -->
    <script>
        function printElement(elementId, title) {
            const printContent = document.getElementById(elementId);
            if (!printContent) return;

            const printWindow = window.open('', '_blank', 'width=850,height=900');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${title || 'Print Document'}</title>
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <style>
                        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fff; color: #000; padding: 20px; }
                        @media print {
                            body { padding: 0; margin: 0; }
                            @page { margin: 10mm; }
                        }
                    </style>
                </head>
                <body>
                    ${printContent.outerHTML}
                    <script>
                        window.onload = function() {
                            window.focus();
                            window.print();
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>

</x-layouts.admin>
