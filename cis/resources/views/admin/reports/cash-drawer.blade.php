<x-layouts.admin title="Daily Cash Drawer & Shift Summary">

    <div class="space-y-6">

        <!-- Page Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-6 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300 font-bold border border-white/10">
                            <i class="bx bx-coin-stack text-lg"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Daily Cash Drawer &amp; Shift Reconciliation</h1>
                    </div>
                    <p class="text-xs text-slate-300">FR-5.1 &bull; End-of-shift collections summary grouped by payment channel &amp; receiving bank</p>
                </div>

                <!-- Date Picker Filter & Preview PDF Modal Trigger -->
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.reports.cash-drawer') }}" method="GET" class="flex items-center gap-2">
                        <input
                            type="date"
                            name="date"
                            value="{{ $selectedDate }}"
                            onchange="this.form.submit()"
                            class="py-2 px-3 rounded-xl bg-slate-800 border border-slate-700 text-white text-xs font-mono focus:outline-none focus:border-indigo-500"
                        >
                    </form>
                    <button
                        type="button"
                        onclick="document.getElementById('modal-cash-drawer-preview').classList.remove('hidden')"
                        class="px-3.5 py-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition cursor-pointer flex items-center gap-1.5 text-xs font-bold border border-white/10"
                        title="Preview &amp; Print Daily Settlement Summary"
                    >
                        <i class="bx bx-printer text-base"></i>
                        <span>Preview / Print Report</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Metric KPI Cards for Selected Date -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <x-stat-card
                title="Total Day Collections"
                value="RM {{ number_format($totalCollected, 2) }}"
                icon="bx bx-wallet"
                color="indigo"
                subtitle="{{ $payments->count() }} transaction(s) recorded"
            />
            <x-stat-card
                title="Cash Tendered in Drawer"
                value="RM {{ number_format($cashTotal, 2) }}"
                icon="bx bx-money"
                color="emerald"
                subtitle="Physical cash in front desk register"
            />
            <x-stat-card
                title="Card EDC Terminal Batch"
                value="RM {{ number_format($cardTotal, 2) }}"
                icon="bx bx-credit-card"
                color="purple"
                subtitle="Credit & Debit card slips"
            />
            <x-stat-card
                title="DuitNow QR / Online"
                value="RM {{ number_format($qrTotal, 2) }}"
                icon="bx bx-qr-scan"
                color="amber"
                subtitle="Instant digital collections"
            />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left: Breakdown by Channel and Bank -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Grouped by Channel -->
                <x-card title="Collection by Payment Channel">
                    <div class="space-y-3 text-xs">
                        @forelse($channelSummary as $ch)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60">
                                <div class="flex items-center gap-2">
                                    <x-badge variant="indigo" size="sm">
                                        {{ strtoupper($ch->payment_method) }}
                                    </x-badge>
                                    <span class="text-slate-500 dark:text-slate-400 font-mono">({{ $ch->count }} txns)</span>
                                </div>
                                <span class="font-mono font-bold text-slate-900 dark:text-white">
                                    RM {{ number_format($ch->total_amount, 2) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-slate-400">No collections for this date.</div>
                        @endforelse
                    </div>
                </x-card>

                <!-- Grouped by Bank / EDC Terminal -->
                <x-card title="Receiving Banks &amp; Terminals">
                    <div class="space-y-3 text-xs">
                        @forelse($bankSummary as $bk)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60">
                                <div class="flex items-center gap-2">
                                    <i class="bx bx-building-house text-slate-400"></i>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $bk->bank_name }}</span>
                                </div>
                                <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                    RM {{ number_format($bk->total_amount, 2) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-slate-400">No digital bank settlements on this date.</div>
                        @endforelse
                    </div>
                </x-card>
            </div>

            <!-- Right: Itemized Payment Log for the Shift -->
            <div class="lg:col-span-8 space-y-6">
                <x-card title="Shift Payments Ledger ({{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }})" noPadding="true">
                    <x-table :headers="[
                        'Invoice #',
                        'Patient Name',
                        'Method & Bank',
                        'Audit Ref',
                        ['label' => 'Amount', 'align' => 'right'],
                        'Cashier',
                    ]">
                        @forelse($payments as $p)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3 px-4 font-mono font-bold">
                                    <a href="{{ route('admin.invoices.show', $p->invoice_id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $p->invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white">
                                    {{ $p->invoice->patient->name }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-1.5">
                                        <x-badge variant="indigo" size="sm">{{ strtoupper($p->payment_method) }}</x-badge>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ $p->bank_name ?: '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                    {{ $p->transaction_ref ?: '-' }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    RM {{ number_format($p->amount, 2) }}
                                </td>
                                <td class="py-3 px-4 text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $p->cashier?->name ?? 'Frontdesk' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                                    <i class="bx bx-receipt text-3xl mb-2 text-slate-500"></i>
                                    <p>No payment settlements found for this selected date.</p>
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                </x-card>
            </div>

        </div>

    </div>

    <!-- Modal: Cash Drawer Daily Settlement PDF Preview -->
    <x-modal name="cash-drawer-preview" title="Daily Cash Drawer Settlement Summary Preview" size="4xl">
        <div id="cash-drawer-printable" class="p-8 rounded-2xl bg-white text-slate-900 border border-slate-200 shadow-inner space-y-6 text-xs font-sans select-text">
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-slate-200 pb-5">
                <div>
                    <div class="text-xl font-black text-indigo-700 uppercase tracking-tight">{{ $profile->clinic_name ?? 'POLIKLINIK PRIMA CIS' }}</div>
                    <div class="text-slate-500 text-[11px]">{{ $profile->address ?? 'No 12, Jalan Boulevard 3, Kuala Lumpur' }} &bull; Tel: {{ $profile->phone ?? '+603-8899 1234' }}</div>
                    <div class="text-slate-500 text-[11px]">SSM / Reg: {{ $profile->registration_number ?? '202601004921' }}</div>
                </div>
                <div class="text-right font-mono">
                    <div class="text-lg font-black text-slate-900">DAILY SHIFT RECONCILIATION</div>
                    <div class="text-slate-500 text-xs mt-0.5">Report Date: <strong class="text-slate-900">{{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</strong></div>
                    <div class="text-slate-400 text-[10px]">Generated: {{ now()->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <!-- Summary KPI Box -->
            <div class="grid grid-cols-4 gap-3">
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-[10px] font-bold uppercase text-slate-400">Total Collections</div>
                    <div class="font-mono font-black text-base text-indigo-600 mt-1">RM {{ number_format($totalCollected, 2) }}</div>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-[10px] font-bold uppercase text-slate-400">Physical Cash Drawer</div>
                    <div class="font-mono font-black text-base text-emerald-600 mt-1">RM {{ number_format($cashTotal, 2) }}</div>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-[10px] font-bold uppercase text-slate-400">EDC Terminal Cards</div>
                    <div class="font-mono font-black text-base text-blue-600 mt-1">RM {{ number_format($cardTotal, 2) }}</div>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-[10px] font-bold uppercase text-slate-400">DuitNow QR Pay</div>
                    <div class="font-mono font-black text-base text-purple-600 mt-1">RM {{ number_format($qrTotal, 2) }}</div>
                </div>
            </div>

            <!-- Channel Breakdown Table -->
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Collections by Tender Method</div>
                <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-100 font-bold uppercase text-[10px] text-slate-600">
                        <tr>
                            <th class="py-2.5 px-3">Payment Channel</th>
                            <th class="py-2.5 px-3 text-center">Txn Count</th>
                            <th class="py-2.5 px-3 text-right">Total Settled (RM)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($channelSummary as $c)
                            <tr>
                                <td class="py-2.5 px-3 font-semibold capitalize">{{ $c->payment_method }}</td>
                                <td class="py-2.5 px-3 text-center font-mono">{{ $c->count }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold">{{ number_format($c->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Sign-off signatures -->
            <div class="grid grid-cols-2 gap-12 pt-8 text-[11px] text-slate-600 border-t border-slate-200">
                <div class="space-y-10">
                    <div>Prepared by (Duty Cashier):</div>
                    <div class="border-t border-dashed border-slate-400 pt-1 font-semibold">
                        Signature &amp; Staff Stamp
                    </div>
                </div>
                <div class="space-y-10">
                    <div>Verified by (Clinic Supervisor / Doctor):</div>
                    <div class="border-t border-dashed border-slate-400 pt-1 font-semibold">
                        Signature &amp; Verification Date
                    </div>
                </div>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex items-center justify-between gap-3 w-full">
                <button
                    type="button"
                    onclick="document.getElementById('modal-cash-drawer-preview').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-white transition cursor-pointer"
                >
                    Close Preview
                </button>

                <button
                    type="button"
                    onclick="printElement('cash-drawer-printable', 'Cash-Drawer-{{ $selectedDate }}')"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs flex items-center gap-2 transition cursor-pointer shadow-lg shadow-indigo-600/30"
                >
                    <i class="bx bx-printer text-base"></i>
                    <span>Print / Save as PDF</span>
                </button>
            </div>
        </x-slot:footer>
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
