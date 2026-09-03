<x-layouts.admin title="Invoices Directory">

    <div class="space-y-6">

        <!-- Page Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-5 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                            <i class="bx bx-receipt text-xl"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Billing &amp; Invoices Directory</h1>
                    </div>
                    <p class="text-xs text-slate-300">Browse, filter, settle outstanding payments, and issue formal medical receipts</p>
                </div>

                <div class="flex items-center gap-3">
                    <x-button href="{{ route('admin.invoices.create') }}" variant="success" size="md" icon="bx bx-plus-circle" class="shadow-lg shadow-emerald-600/30">
                        New POS Invoice
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
            </x-alert>
        @endif

        <!-- Quick Summary Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card
                title="Total Invoices"
                value="{{ $stats['total_invoices'] }}"
                icon="bx bx-file-blank"
                color="indigo"
                subtitle="All-time recorded transactions"
            />
            <x-stat-card
                title="Collected Revenue"
                value="RM {{ number_format($stats['total_revenue'], 2) }}"
                icon="bx bx-wallet"
                color="emerald"
                subtitle="{{ $stats['paid_count'] }} invoices fully settled"
            />
            <x-stat-card
                title="Outstanding Unpaid"
                value="RM {{ number_format($stats['outstanding'], 2) }}"
                icon="bx bx-time-five"
                color="amber"
                subtitle="Pending collection from patients"
            />
            <x-stat-card
                title="Print Engines"
                value="A4 &amp; 80mm"
                icon="bx bx-printer"
                color="purple"
                subtitle="Dual receipt generation active"
            />
        </div>

        <!-- Reusable Filter & Search Toolbar -->
        <x-filter-toolbar
            title="Search &amp; Filter Invoices"
            subtitle="Search across invoice numbers, patient identities, attending doctors, and settlement states"
            :action="route('admin.invoices.index')"
            searchPlaceholder="Search by invoice number (e.g. INV-2026...), patient name, IC, or doctor..."
            :searchValue="request('search')"
            :resetUrl="route('admin.invoices.index')"
            :defaultOpen="true"
        >
            <x-slot:filters>
                <x-select label="Invoice Status" name="status">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid &amp; Settled</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid / Outstanding</option>
                    <option value="void" {{ request('status') === 'void' ? 'selected' : '' }}>Voided</option>
                </x-select>
            </x-slot:filters>
        </x-filter-toolbar>

        <!-- 1. Mobile-Optimized Card View (Visible on phones & tablets < md) -->
        <div class="grid grid-cols-1 gap-3.5 md:hidden">
            @forelse($invoices as $inv)
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-xs space-y-3">
                    
                    <!-- Top Row: Invoice Number & Status Badge -->
                    <div class="flex items-center justify-between gap-2">
                        <a href="{{ route('admin.invoices.show', $inv->id) }}" class="font-mono font-bold text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
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

                    <!-- Patient & Doctor Details -->
                    <div class="text-xs space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center justify-between">
                            <span>{{ $inv->patient->name }}</span>
                            <span class="text-[11px] font-mono text-slate-400 font-normal">{{ $inv->created_at->format('d M, h:i A') }}</span>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                            IC: {{ $inv->patient->id_number }} &bull; Attendant: {{ $inv->doctor_name }}
                        </div>
                    </div>

                    <!-- Financial Summary & Action Button -->
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total / Paid</div>
                            <div class="font-mono text-xs font-bold text-slate-900 dark:text-white">
                                RM {{ number_format($inv->total_amount, 2) }}
                                <span class="text-emerald-600 dark:text-emerald-400 font-normal">/ RM {{ number_format($inv->paid_amount, 2) }}</span>
                            </div>
                        </div>

                        <x-button href="{{ route('admin.invoices.show', $inv->id) }}" variant="outline" size="sm" icon="bx bx-printer">
                            View
                        </x-button>
                    </div>

                </div>
            @empty
                <div class="p-8 text-center rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 text-xs">
                    <i class="bx bx-receipt text-3xl mb-2 text-slate-400"></i>
                    <p>No invoices match the selected filter criteria.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. Desktop High-Density Table Card (Visible on md and up) -->
        <x-card noPadding="true" class="hidden md:block">
            <x-table :headers="[
                'Invoice #',
                'Patient & IC',
                'Attending Doctor',
                ['label' => 'Total Amount', 'align' => 'right'],
                ['label' => 'Paid Amount', 'align' => 'right'],
                ['label' => 'Status', 'align' => 'center'],
                ['label' => 'Date', 'align' => 'center'],
                ['label' => 'Actions', 'align' => 'right'],
            ]">
                @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <!-- Invoice # -->
                        <td class="py-3.5 px-5">
                            <a href="{{ route('admin.invoices.show', $inv->id) }}" class="font-mono font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $inv->invoice_number }}
                            </a>
                            <div class="text-[10px] text-slate-400 font-mono">By {{ $inv->creator?->name ?? 'Staff' }}</div>
                        </td>

                        <!-- Patient Info -->
                        <td class="py-3.5 px-5">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $inv->patient->name }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                                IC: {{ $inv->patient->id_number }} &bull; {{ $inv->patient->phone }}
                            </div>
                        </td>

                        <!-- Attending Doctor -->
                        <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300">
                            {{ $inv->doctor_name }}
                        </td>

                        <!-- Total -->
                        <td class="py-3.5 px-5 text-right font-mono font-bold text-slate-900 dark:text-white">
                            RM {{ number_format($inv->total_amount, 2) }}
                        </td>

                        <!-- Paid -->
                        <td class="py-3.5 px-5 text-right font-mono font-medium {{ $inv->paid_amount >= $inv->total_amount ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-300' }}">
                            RM {{ number_format($inv->paid_amount, 2) }}
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-5 text-center">
                            @if($inv->status === 'paid')
                                <x-badge variant="emerald" size="sm" dot="true">Paid</x-badge>
                            @elseif($inv->status === 'partial')
                                <x-badge variant="amber" size="sm" dot="true">Partial (RM {{ number_format($inv->due_amount, 2) }} due)</x-badge>
                            @elseif($inv->status === 'void')
                                <x-badge variant="rose" size="sm">Void</x-badge>
                            @else
                                <x-badge variant="rose" size="sm" dot="true">Unpaid</x-badge>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="py-3.5 px-5 text-center font-mono text-[11px] text-slate-500 dark:text-slate-400">
                            {{ $inv->created_at->format('d M Y, h:i A') }}
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-5 text-right space-x-2">
                            <x-button href="{{ route('admin.invoices.show', $inv->id) }}" variant="outline" size="sm">
                                View / Print
                            </x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400 text-xs">
                            <i class="bx bx-receipt text-3xl mb-2 text-slate-400"></i>
                            <p>No invoices match the selected filter criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>

            <!-- Reusable Pagination & Per-Page Limit -->
            <x-pagination :paginator="$invoices" :perPageOptions="[10, 25, 50, 100]" />
        </x-card>

        <!-- Mobile Pagination -->
        <div class="md:hidden">
            <x-pagination :paginator="$invoices" :perPageOptions="[10, 25, 50, 100]" class="rounded-2xl border" />
        </div>

    </div>

</x-layouts.admin>
