<x-layouts.admin title="Clinic Operations Dashboard">

    <div class="space-y-6">

        @if(auth()->user()?->isAdmin())
            <!-- ============================================================== -->
            <!-- 👑 ADMINISTRATOR DASHBOARD: FINANCIAL VELOCITY & MULTI-PERIOD -->
            <!-- ============================================================== -->

            <!-- Admin Executive Header Banner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-6 sm:p-8 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute right-1/3 -bottom-20 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                    <div class="space-y-2 max-w-2xl">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 font-bold text-base shadow-xs border border-white/10">
                                <i class="bx bx-crown"></i>
                            </div>
                            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Executive Management Command Center</h1>
                            <x-badge variant="indigo">Administrator Portal</x-badge>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                            Welcome, <strong class="text-white">{{ auth()->user()->name }}</strong>. Review clinical financial performance across Daily, Weekly, and Monthly cycles.
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                        <x-button href="{{ route('admin.reports.analytics') }}" variant="primary" size="sm" icon="bx bx-line-chart">
                            AR Aging Debt
                        </x-button>
                        <x-button href="{{ route('admin.reports.bank-recon') }}" variant="secondary" size="sm" icon="bx bx-building-house">
                            Bank Recon
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- Multi-Period Financial Velocity Tracking (Daily / Weekly / Monthly / Yearly) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 1. Daily Collection -->
                <x-stat-card
                    title="Today's Revenue"
                    value="RM {{ number_format($metrics['today_revenue'], 2) }}"
                    icon="bx bx-calendar-event"
                    color="emerald"
                    subtitle="{{ $metrics['today_invoices'] }} invoice(s) settled today"
                    change="Today's Velocity"
                    changeType="increase"
                />

                <!-- 2. Weekly Collection -->
                <x-stat-card
                    title="This Week's Revenue"
                    value="RM {{ number_format($metrics['weekly_revenue'], 2) }}"
                    icon="bx bx-calendar-week"
                    color="indigo"
                    subtitle="{{ $metrics['weekly_invoices'] }} invoice(s) this week"
                    change="Week-to-Date"
                    changeType="increase"
                />

                <!-- 3. Monthly Collection -->
                <x-stat-card
                    title="This Month's Revenue"
                    value="RM {{ number_format($metrics['monthly_revenue'], 2) }}"
                    icon="bx bx-calendar"
                    color="purple"
                    subtitle="{{ $metrics['monthly_invoices'] }} invoice(s) in {{ now()->format('M Y') }}"
                    change="Month-to-Date"
                    changeType="increase"
                />

                <!-- 4. Outstanding Debt / AR Aging -->
                <x-stat-card
                    title="Total Outstanding Debt"
                    value="RM {{ number_format($metrics['outstanding_debt'], 2) }}"
                    icon="bx bx-time-five"
                    color="amber"
                    subtitle="Uncollected balance from patients"
                />
            </div>

            <!-- Financial Distribution & Operational Highlights -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left: Multi-Period Payment Channel Distribution (Span 7) -->
                <div class="lg:col-span-7 space-y-6">
                    <x-card title="Payment Channels Velocity" subtitle="Multi-period collection breakdown across tender methods">
                        <x-slot:action>
                            <!-- Period Selector Switcher Tabs -->
                            <div class="flex items-center p-1 bg-slate-100 dark:bg-slate-800 rounded-xl text-xs">
                                <button
                                    type="button"
                                    onclick="switchChannelPeriod('daily')"
                                    id="tab-btn-daily"
                                    class="px-2.5 py-1 rounded-lg font-bold transition-all text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-900 shadow-xs"
                                >
                                    Today
                                </button>
                                <button
                                    type="button"
                                    onclick="switchChannelPeriod('weekly')"
                                    id="tab-btn-weekly"
                                    class="px-2.5 py-1 rounded-lg font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all"
                                >
                                    Weekly
                                </button>
                                <button
                                    type="button"
                                    onclick="switchChannelPeriod('monthly')"
                                    id="tab-btn-monthly"
                                    class="px-2.5 py-1 rounded-lg font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all"
                                >
                                    Monthly
                                </button>
                            </div>
                        </x-slot:action>

                        <!-- Multi-Period Tab Panels -->
                        @foreach(['daily' => 'Today (' . now()->format('d M') . ')', 'weekly' => 'This Week (Mon - Sun)', 'monthly' => 'This Month (' . now()->format('F Y') . ')'] as $period => $periodLabel)
                            @php
                                $totalPeriodRevenue = $period === 'daily' ? $metrics['today_revenue'] : ($period === 'weekly' ? $metrics['weekly_revenue'] : $metrics['monthly_revenue']);
                                $periodData = $channelBreakdown[$period];
                            @endphp

                            <div id="channel-panel-{{ $period }}" class="space-y-4 {{ $period === 'daily' ? '' : 'hidden' }}">
                                <div class="text-[11px] font-semibold text-slate-400 flex items-center justify-between pb-1 border-b border-slate-100 dark:border-slate-800">
                                    <span>Period: <strong class="text-slate-700 dark:text-slate-200">{{ $periodLabel }}</strong></span>
                                    <span>Total Settled: <strong class="font-mono text-slate-900 dark:text-white">RM {{ number_format($totalPeriodRevenue, 2) }}</strong></span>
                                </div>

                                <!-- 1. Cash Tender -->
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                            <i class="bx bx-money text-emerald-500 text-base"></i>
                                            <span>Cash Tender (Counter Drawer)</span>
                                        </span>
                                        <span class="font-mono font-bold text-slate-900 dark:text-white">
                                            RM {{ number_format($periodData['cash'], 2) }}
                                            <span class="text-[10px] font-normal text-slate-400">({{ $totalPeriodRevenue > 0 ? round(($periodData['cash'] / $totalPeriodRevenue) * 100) : 0 }}%)</span>
                                        </span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $totalPeriodRevenue > 0 ? ($periodData['cash'] / $totalPeriodRevenue) * 100 : 0 }}%"></div>
                                    </div>
                                </div>

                                <!-- 2. EDC Terminal Cards -->
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                            <i class="bx bx-credit-card text-indigo-500 text-base"></i>
                                            <span>Credit / Debit Card (EDC Terminals)</span>
                                        </span>
                                        <span class="font-mono font-bold text-slate-900 dark:text-white">
                                            RM {{ number_format($periodData['card'], 2) }}
                                            <span class="text-[10px] font-normal text-slate-400">({{ $totalPeriodRevenue > 0 ? round(($periodData['card'] / $totalPeriodRevenue) * 100) : 0 }}%)</span>
                                        </span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 rounded-full transition-all duration-500" style="width: {{ $totalPeriodRevenue > 0 ? ($periodData['card'] / $totalPeriodRevenue) * 100 : 0 }}%"></div>
                                    </div>
                                </div>

                                <!-- 3. DuitNow QR Pay -->
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                            <i class="bx bx-qr-scan text-purple-500 text-base"></i>
                                            <span>DuitNow QR Pay &amp; E-Wallets</span>
                                        </span>
                                        <span class="font-mono font-bold text-slate-900 dark:text-white">
                                            RM {{ number_format($periodData['qr'], 2) }}
                                            <span class="text-[10px] font-normal text-slate-400">({{ $totalPeriodRevenue > 0 ? round(($periodData['qr'] / $totalPeriodRevenue) * 100) : 0 }}%)</span>
                                        </span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-purple-500 rounded-full transition-all duration-500" style="width: {{ $totalPeriodRevenue > 0 ? ($periodData['qr'] / $totalPeriodRevenue) * 100 : 0 }}%"></div>
                                    </div>
                                </div>

                                <!-- 4. Bank Transfer & Insurance Panels -->
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                            <i class="bx bx-building text-sky-500 text-base"></i>
                                            <span>Bank Transfer &amp; Insurance Panels</span>
                                        </span>
                                        <span class="font-mono font-bold text-slate-900 dark:text-white">
                                            RM {{ number_format($periodData['transfer'] + $periodData['panel'], 2) }}
                                            <span class="text-[10px] font-normal text-slate-400">({{ $totalPeriodRevenue > 0 ? round((($periodData['transfer'] + $periodData['panel']) / $totalPeriodRevenue) * 100) : 0 }}%)</span>
                                        </span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-sky-500 rounded-full transition-all duration-500" style="width: {{ $totalPeriodRevenue > 0 ? (($periodData['transfer'] + $periodData['panel']) / $totalPeriodRevenue) * 100 : 0 }}%"></div>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </x-card>
                </div>

                <!-- Right: Clinical Operations & Inventory Status (Span 5) -->
                <div class="lg:col-span-5 space-y-6">
                    <x-card title="Operational Quick Stats" subtitle="Clinic master data health indicators">
                        <div class="space-y-3.5 text-xs">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                                <div class="flex items-center gap-2.5">
                                    <i class="bx bx-user-pin text-xl text-indigo-500"></i>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">Total Patient Accounts</span>
                                </div>
                                <span class="font-mono font-black text-slate-900 dark:text-white">{{ $metrics['total_patients'] }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                                <div class="flex items-center gap-2.5">
                                    <i class="bx bx-error text-xl text-amber-500"></i>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">Low Drug Stocks (&lt;50 units)</span>
                                </div>
                                <span class="font-mono font-black {{ $metrics['low_stock_items'] > 0 ? 'text-amber-500' : 'text-emerald-500' }}">
                                    {{ $metrics['low_stock_items'] }} SKUs
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                                <div class="flex items-center gap-2.5">
                                    <i class="bx bx-shield-quarter text-xl text-emerald-500"></i>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">Security Audit Trail</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">
                                    Active &amp; Immutable
                                </span>
                            </div>
                        </div>
                    </x-card>
                </div>

            </div>

        @else
            <!-- ============================================================== -->
            <!-- 💳 CASHIER / FRONT-DESK DASHBOARD: POS SPEED & DISCHARGE QUEUE -->
            <!-- ============================================================== -->

            <!-- Cashier Shift Header Banner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-950 via-slate-900 to-indigo-950 p-6 sm:p-8 border border-emerald-800/40 shadow-xl shadow-emerald-950/40 text-white">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                    <div class="space-y-2 max-w-2xl">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-emerald-300 font-bold text-base shadow-xs border border-white/10">
                                <i class="bx bx-store-alt"></i>
                            </div>
                            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Front-Desk Cashier Workstation</h1>
                            <x-badge variant="emerald" dot="true">Shift Active</x-badge>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                            Logged in as <strong class="text-white">{{ auth()->user()->name }}</strong> (Staff ID: <span class="font-mono text-emerald-300">{{ auth()->user()->staff_id ?? 'STF-001' }}</span>). Issue invoices, tender payments, and print thermal receipts.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <x-button href="{{ route('admin.invoices.create') }}" variant="success" size="md" icon="bx bx-plus-circle" class="shadow-lg shadow-emerald-600/30">
                            New POS Invoice
                        </x-button>
                        <x-button href="{{ route('admin.reports.cash-drawer') }}" variant="secondary" size="md" icon="bx bx-coin-stack">
                            My Cash Drawer
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- Cashier Shift Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card
                    title="Shift Invoices"
                    value="{{ $metrics['today_invoices'] }}"
                    icon="bx bx-file-blank"
                    color="indigo"
                    subtitle="Invoices issued today"
                />
                <x-stat-card
                    title="Today's Collections"
                    value="RM {{ number_format($metrics['today_revenue'], 2) }}"
                    icon="bx bx-wallet"
                    color="emerald"
                    subtitle="Cash, Card, QR payments"
                />
                <x-stat-card
                    title="Pending Settlements"
                    value="RM {{ number_format($metrics['outstanding_debt'], 2) }}"
                    icon="bx bx-time-five"
                    color="amber"
                    subtitle="Unpaid discharge queue"
                />
                <x-stat-card
                    title="Registered Patients"
                    value="{{ $metrics['total_patients'] }} records"
                    icon="bx bx-user-pin"
                    color="purple"
                    subtitle="Quick patient lookups"
                />
            </div>

            <!-- Cashier Action Launchpad -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('admin.invoices.create') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 shadow-xs hover:shadow-md transition-all group flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center text-2xl shrink-0 transition-colors">
                        <i class="bx bx-receipt"></i>
                    </div>
                    <div>
                        <div class="text-sm font-extrabold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400">Issue POS Invoice</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Quick itemized patient billing</div>
                    </div>
                </a>

                <a href="{{ route('admin.patients.create') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-500 dark:hover:border-indigo-500 shadow-xs hover:shadow-md transition-all group flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center text-2xl shrink-0 transition-colors">
                        <i class="bx bx-user-plus"></i>
                    </div>
                    <div>
                        <div class="text-sm font-extrabold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Register New Patient</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Record IC, phone &amp; allergy tags</div>
                    </div>
                </a>

                <a href="{{ route('admin.reports.cash-drawer') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-purple-500 dark:hover:border-purple-500 shadow-xs hover:shadow-md transition-all group flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 group-hover:bg-purple-600 group-hover:text-white flex items-center justify-center text-2xl shrink-0 transition-colors">
                        <i class="bx bx-coin-stack"></i>
                    </div>
                    <div>
                        <div class="text-sm font-extrabold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400">Daily Cash Drawer</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">End-of-shift cash drawer audit</div>
                    </div>
                </a>
            </div>

        @endif

        <!-- ============================================================== -->
        <!-- SHARED SECTION: RECENT INVOICE ACTIVITY LIST                   -->
        <!-- ============================================================== -->
        <x-card title="Recent Invoices Activity" subtitle="Latest clinical billing transactions across all reception counters" noPadding="true">
            <x-slot:action>
                <a href="{{ route('admin.invoices.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 flex items-center gap-1">
                    <span>View all directory</span>
                    <i class="bx bx-chevron-right"></i>
                </a>
            </x-slot:action>

            <!-- 1. Mobile Cards (< md) -->
            <div class="grid grid-cols-1 divide-y divide-slate-100 dark:divide-slate-800 md:hidden">
                @forelse($recentInvoices as $inv)
                    <div class="p-4 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.invoices.show', $inv->id) }}" class="font-mono font-bold text-xs text-indigo-600 dark:text-indigo-400">
                                {{ $inv->invoice_number }}
                            </a>
                            @if($inv->status === 'paid')
                                <x-badge variant="emerald" size="sm" dot="true">Paid</x-badge>
                            @elseif($inv->status === 'partial')
                                <x-badge variant="amber" size="sm" dot="true">Partial</x-badge>
                            @elseif($inv->status === 'void')
                                <x-badge variant="rose" size="sm">Void</x-badge>
                            @else
                                <x-badge variant="rose" size="sm" dot="true">Unpaid</x-badge>
                            @endif
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-900 dark:text-white">{{ $inv->patient->name }}</span>
                            <span class="font-mono font-bold text-slate-900 dark:text-white">RM {{ number_format($inv->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px] text-slate-400 pt-1">
                            <span>Dr: {{ $inv->doctor_name }}</span>
                            <a href="{{ route('admin.invoices.show', $inv->id) }}" class="font-semibold text-indigo-600 dark:text-indigo-400">View &amp; Print &rarr;</a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        No transactions recorded today.
                    </div>
                @endforelse
            </div>

            <!-- 2. Desktop Table (>= md) -->
            <div class="hidden md:block">
                <x-table :headers="[
                    'Invoice #',
                    'Patient Name',
                    'Doctor / Attendant',
                    ['label' => 'Total', 'align' => 'right'],
                    ['label' => 'Paid', 'align' => 'right'],
                    ['label' => 'Status', 'align' => 'center'],
                    ['label' => 'Actions', 'align' => 'right'],
                ]">
                    @forelse($recentInvoices as $inv)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-5 font-mono font-bold">
                                <a href="{{ route('admin.invoices.show', $inv->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-5 font-semibold text-slate-900 dark:text-white">
                                {{ $inv->patient->name }} <span class="text-slate-400 text-[10px] font-mono">({{ $inv->patient->id_number }})</span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300">
                                {{ $inv->doctor_name }}
                            </td>
                            <td class="py-3.5 px-5 text-right font-mono font-bold text-slate-900 dark:text-white">
                                RM {{ number_format($inv->total_amount, 2) }}
                            </td>
                            <td class="py-3.5 px-5 text-right font-mono text-emerald-600 dark:text-emerald-400 font-semibold">
                                RM {{ number_format($inv->paid_amount, 2) }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                @if($inv->status === 'paid')
                                    <x-badge variant="emerald" size="sm" dot="true">Paid</x-badge>
                                @elseif($inv->status === 'partial')
                                    <x-badge variant="amber" size="sm" dot="true">Partial</x-badge>
                                @elseif($inv->status === 'void')
                                    <x-badge variant="rose" size="sm">Void</x-badge>
                                @else
                                    <x-badge variant="rose" size="sm" dot="true">Unpaid</x-badge>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right space-x-2">
                                <x-button href="{{ route('admin.invoices.show', $inv->id) }}" variant="outline" size="sm">
                                    View &amp; Print
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                No invoices recorded yet. Click "Create Invoice" to start.
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </div>
        </x-card>

    </div>

    <!-- Interactive Period Tab Switcher Script -->
    <script>
        function switchChannelPeriod(period) {
            const periods = ['daily', 'weekly', 'monthly'];
            
            periods.forEach(p => {
                const panel = document.getElementById(`channel-panel-${p}`);
                const btn = document.getElementById(`tab-btn-${p}`);
                
                if (p === period) {
                    if (panel) panel.classList.remove('hidden');
                    if (btn) {
                        btn.className = 'px-2.5 py-1 rounded-lg font-bold transition-all text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-900 shadow-xs';
                    }
                } else {
                    if (panel) panel.classList.add('hidden');
                    if (btn) {
                        btn.className = 'px-2.5 py-1 rounded-lg font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all';
                    }
                }
            });
        }
    </script>

</x-layouts.admin>
