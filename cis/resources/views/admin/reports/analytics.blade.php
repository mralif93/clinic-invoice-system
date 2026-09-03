<x-layouts.admin title="Revenue Analytics & Aging Reports">

    <div class="space-y-6">

        <!-- Page Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-6 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300 font-bold border border-white/10">
                            <i class="bx bx-bar-chart-square text-lg"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Revenue Analytics &amp; Aging Debt</h1>
                    </div>
                    <p class="text-xs text-slate-300">FR-5.4 &bull; Monthly revenue trajectory, patient credit aging analysis, and top revenue-generating treatments</p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        onclick="document.getElementById('modal-analytics-preview').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/10 transition cursor-pointer"
                    >
                        <i class="bx bx-printer text-base"></i>
                        <span>Preview / Print Report</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Overall KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card
                title="Gross Invoiced Billings"
                value="RM {{ number_format($stats['gross_billed'], 2) }}"
                icon="bx bx-receipt"
                color="indigo"
                subtitle="Total medical services rendered"
            />
            <x-stat-card
                title="Settled Collections"
                value="RM {{ number_format($stats['total_collected'], 2) }}"
                icon="bx bx-wallet"
                color="emerald"
                subtitle="Net realized clinic revenue"
            />
            <x-stat-card
                title="Cumulative Aging Debt"
                value="RM {{ number_format($stats['total_outstanding'], 2) }}"
                icon="bx bx-error-alt"
                color="amber"
                subtitle="Unsettled patient balances"
            />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left: Top Treatments & Monthly Trajectory -->
            <div class="lg:col-span-6 space-y-6">
                <!-- Top Billed Treatments -->
                <x-card title="Top Billed Treatments &amp; Medications" subtitle="Highest revenue-generating catalog items">
                    <div class="space-y-3">
                        @forelse($topBilled as $top)
                            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 flex items-center justify-between gap-4">
                                <div class="space-y-0.5 min-w-0">
                                    <div class="font-bold text-xs text-slate-900 dark:text-white truncate">{{ $top->item_name }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">{{ $top->total_qty }} units / procedures dispensed</div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400">
                                        RM {{ number_format($top->total_sales, 2) }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 text-xs">No billing line items recorded yet.</div>
                        @endforelse
                    </div>
                </x-card>

                <!-- Monthly Trajectory Table -->
                <x-card title="Monthly Collections Ledger">
                    <div class="space-y-2">
                        @forelse($monthlyRevenue as $m)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60">
                                <span class="font-mono font-bold text-xs text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($m->month . '-01')->format('F Y') }}</span>
                                <span class="font-mono font-bold text-sm text-indigo-600 dark:text-indigo-400">RM {{ number_format($m->total, 2) }}</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-slate-400 text-xs">No historical revenue data.</div>
                        @endforelse
                    </div>
                </x-card>
            </div>

            <!-- Right: Aging Unpaid Debt Analysis (0-30, 31-60, 61-90, 90+ days) -->
            <div class="lg:col-span-6 space-y-6">
                <!-- Aging Buckets Card -->
                <x-card title="Patient Accounts Receivable Aging" subtitle="Aging analysis of overdue patient invoices">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        <div class="p-3 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 text-center">
                            <span class="text-[10px] uppercase font-bold text-emerald-700 dark:text-emerald-400">0 - 30 Days</span>
                            <div class="font-mono font-bold text-sm text-emerald-800 dark:text-emerald-300 mt-1">
                                RM {{ number_format($aging['0_30'], 2) }}
                            </div>
                        </div>
                        <div class="p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/40 text-center">
                            <span class="text-[10px] uppercase font-bold text-amber-700 dark:text-amber-400">31 - 60 Days</span>
                            <div class="font-mono font-bold text-sm text-amber-800 dark:text-amber-300 mt-1">
                                RM {{ number_format($aging['31_60'], 2) }}
                            </div>
                        </div>
                        <div class="p-3 rounded-2xl bg-orange-50 dark:bg-orange-950/20 border border-orange-200 dark:border-orange-900/40 text-center">
                            <span class="text-[10px] uppercase font-bold text-orange-700 dark:text-orange-400">61 - 90 Days</span>
                            <div class="font-mono font-bold text-sm text-orange-800 dark:text-orange-300 mt-1">
                                RM {{ number_format($aging['61_90'], 2) }}
                            </div>
                        </div>
                        <div class="p-3 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 text-center">
                            <span class="text-[10px] uppercase font-bold text-rose-700 dark:text-rose-400">&gt; 90 Days</span>
                            <div class="font-mono font-bold text-sm text-rose-800 dark:text-rose-300 mt-1">
                                RM {{ number_format($aging['90_plus'], 2) }}
                            </div>
                        </div>
                    </div>

                    <!-- Overdue Patient Invoices Table -->
                    <div class="pt-2">
                        <div class="text-xs font-bold text-slate-900 dark:text-white mb-2">Unsettled Patient Accounts</div>
                        <div class="space-y-2 text-xs">
                            @forelse($unpaidInvoices as $unpaid)
                                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('admin.invoices.show', $unpaid->id) }}" class="font-mono font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $unpaid->invoice_number }}
                                        </a>
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ $unpaid->patient->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">Issued {{ $unpaid->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-right space-y-1">
                                        <div class="font-mono font-bold text-rose-600 dark:text-rose-400">
                                            RM {{ number_format($unpaid->due_amount, 2) }}
                                        </div>
                                        <x-button href="{{ route('admin.invoices.show', $unpaid->id) }}" variant="outline" size="sm">
                                            Settle
                                        </x-button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400">No overdue patient accounts. All balances settled!</div>
                            @endforelse
                        </div>
                    </div>
                </x-card>
            </div>

        </div>

    </div>

    <!-- Modal: Executive Revenue Analytics PDF Preview -->
    <x-modal name="analytics-preview" title="Executive Financial &amp; Aging Debt Report Preview" size="4xl">
        <div id="analytics-printable" class="p-8 rounded-2xl bg-white text-slate-900 border border-slate-200 shadow-inner space-y-6 text-xs font-sans select-text">
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-slate-200 pb-5">
                <div>
                    <div class="text-xl font-black text-indigo-700 uppercase tracking-tight">{{ $profile->clinic_name ?? 'POLIKLINIK PRIMA CIS' }}</div>
                    <div class="text-slate-500 text-[11px]">{{ $profile->address ?? 'No 12, Jalan Boulevard 3, Kuala Lumpur' }} &bull; Tel: {{ $profile->phone ?? '+603-8899 1234' }}</div>
                    <div class="text-slate-500 text-[11px]">SSM / Reg: {{ $profile->registration_number ?? '202601004921' }}</div>
                </div>
                <div class="text-right font-mono">
                    <div class="text-lg font-black text-slate-900">EXECUTIVE CLINIC REPORT</div>
                    <div class="text-slate-500 text-xs mt-0.5">Scope: <strong>Financial Velocity &amp; AR Debt</strong></div>
                    <div class="text-slate-400 text-[10px]">Generated: {{ now()->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-[10px] font-bold uppercase text-slate-400">Total Billed Billings</div>
                    <div class="font-mono font-black text-lg text-indigo-600 mt-1">RM {{ number_format($stats['gross_billed'], 2) }}</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-[10px] font-bold uppercase text-slate-400">Settled Realized Revenue</div>
                    <div class="font-mono font-black text-lg text-emerald-600 mt-1">RM {{ number_format($stats['total_collected'], 2) }}</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-[10px] font-bold uppercase text-slate-400">Cumulative Aging Debt</div>
                    <div class="font-mono font-black text-lg text-amber-600 mt-1">RM {{ number_format($stats['total_outstanding'], 2) }}</div>
                </div>
            </div>

            <!-- Aging Debt Table -->
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Accounts Receivable (AR) Aging Buckets</div>
                <div class="grid grid-cols-4 gap-3 text-center">
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                        <div class="text-[10px] font-bold text-emerald-700 uppercase">Current (0-30 Days)</div>
                        <div class="font-mono font-bold text-sm text-emerald-900 mt-1">RM {{ number_format($aging['0_30'], 2) }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200">
                        <div class="text-[10px] font-bold text-amber-700 uppercase">31 - 60 Days</div>
                        <div class="font-mono font-bold text-sm text-amber-900 mt-1">RM {{ number_format($aging['31_60'], 2) }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-orange-50 border border-orange-200">
                        <div class="text-[10px] font-bold text-orange-700 uppercase">61 - 90 Days</div>
                        <div class="font-mono font-bold text-sm text-orange-900 mt-1">RM {{ number_format($aging['61_90'], 2) }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200">
                        <div class="text-[10px] font-bold text-rose-700 uppercase">90+ Days (Critical)</div>
                        <div class="font-mono font-bold text-sm text-rose-900 mt-1">RM {{ number_format($aging['90_plus'], 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Top Revenue Treatments Table -->
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Top Clinical Revenue Generators</div>
                <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-100 font-bold uppercase text-[10px] text-slate-600">
                        <tr>
                            <th class="py-2.5 px-3">Item / Treatment Name</th>
                            <th class="py-2.5 px-3 text-center">Units Dispensed</th>
                            <th class="py-2.5 px-3 text-right">Total Revenue (RM)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($topBilled as $tb)
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $tb->item_name }}</td>
                                <td class="py-2.5 px-3 text-center font-mono">{{ $tb->total_qty }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-indigo-600">RM {{ number_format($tb->total_sales, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Sign-off signatures -->
            <div class="grid grid-cols-2 gap-12 pt-8 text-[11px] text-slate-600 border-t border-slate-200">
                <div class="space-y-10">
                    <div>Report Prepared by:</div>
                    <div class="border-t border-dashed border-slate-400 pt-1 font-semibold">
                        {{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})
                    </div>
                </div>
                <div class="space-y-10">
                    <div>Director Review &amp; Endorsement:</div>
                    <div class="border-t border-dashed border-slate-400 pt-1 font-semibold">
                        Medical Director Signature &amp; Stamp
                    </div>
                </div>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex items-center justify-between gap-3 w-full">
                <button
                    type="button"
                    onclick="document.getElementById('modal-analytics-preview').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-white transition cursor-pointer"
                >
                    Close Preview
                </button>

                <button
                    type="button"
                    onclick="printElement('analytics-printable', 'Executive-Report-{{ now()->format('Ymd') }}')"
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
