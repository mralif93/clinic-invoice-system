<x-layouts.admin title="Bank Reconciliation & EDC Settlement">

    <div class="space-y-6">

        <!-- Page Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-6 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300 font-bold border border-white/10">
                            <i class="bx bx-building-house text-lg"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Bank Reconciliation &amp; Terminal Settlements</h1>
                    </div>
                    <p class="text-xs text-slate-300">FR-5.2 &amp; FR-5.3 &bull; Match digital EDC credit card batches &amp; DuitNow QR slips with bank statements</p>
                </div>
            </div>
        </div>

        <!-- Flash Notice -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
            </x-alert>
        @endif

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <x-stat-card
                title="Total Digital Receipts"
                value="RM {{ number_format($stats['total_electronic'], 2) }}"
                icon="bx bx-credit-card-front"
                color="indigo"
                subtitle="All-time Card, QR & Transfers"
            />
            <x-stat-card
                title="Reconciled with Bank"
                value="RM {{ number_format($stats['reconciled_amount'], 2) }}"
                icon="bx bx-check-double"
                color="emerald"
                subtitle="Confirmed against bank ledger"
            />
            <x-stat-card
                title="Pending Reconciliation"
                value="RM {{ number_format($stats['pending_amount'], 2) }}"
                icon="bx bx-time-five"
                color="amber"
                subtitle="{{ $stats['pending_count'] }} transactions to match"
            />
            <x-stat-card
                title="Audit Status"
                value="{{ $stats['pending_count'] == 0 ? 'Balanced' : 'Action Req' }}"
                icon="bx bx-shield-quarter"
                color="{{ $stats['pending_count'] == 0 ? 'emerald' : 'purple' }}"
                subtitle="Terminal settlement sync"
            />
        </div>

        <!-- Reusable Collapsible Filter Toolbar -->
        <x-filter-toolbar
            title="Search &amp; Filter Bank Settlements"
            subtitle="Match EDC credit card terminal batches, DuitNow QR references, and bank deposits against recorded collections"
            :action="route('admin.reports.bank-recon')"
            searchPlaceholder="Search RRN, transaction ref, batch number, or bank..."
            :searchValue="request('search')"
            :resetUrl="route('admin.reports.bank-recon')"
            :defaultOpen="true"
        >
            <x-slot:filters>
                <x-select label="Recon Status" name="status">
                    <option value="">All Reconciliation States</option>
                    <option value="reconciled" {{ request('status') === 'reconciled' ? 'selected' : '' }}>Reconciled (Matched)</option>
                    <option value="unreconciled" {{ request('status') === 'unreconciled' ? 'selected' : '' }}>Unreconciled (Pending)</option>
                </x-select>

                <x-select label="Payment Tender" name="method">
                    <option value="all">All Methods</option>
                    <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Card (EDC)</option>
                    <option value="qr" {{ request('method') === 'qr' ? 'selected' : '' }}>DuitNow QR</option>
                    <option value="transfer" {{ request('method') === 'transfer' ? 'selected' : '' }}>Online Transfer</option>
                    <option value="panel" {{ request('method') === 'panel' ? 'selected' : '' }}>Panel GL</option>
                </x-select>
            </x-slot:filters>
        </x-filter-toolbar>

        <!-- Electronic Settlements Table Card -->
        <x-card noPadding="true">
            <x-table :headers="[
                'Transaction Date',
                'Invoice & Patient',
                'Method & Bank',
                'RRN / Ref / Batch No.',
                ['label' => 'Amount', 'align' => 'right'],
                ['label' => 'Recon Status', 'align' => 'center'],
                ['label' => 'Reconcile Toggle', 'align' => 'right'],
            ]">
                @forelse($electronicPayments as $p)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <!-- Date -->
                        <td class="py-3.5 px-5 font-mono text-xs text-slate-600 dark:text-slate-400">
                            {{ $p->payment_date->format('d M Y') }}
                        </td>

                        <!-- Invoice & Patient -->
                        <td class="py-3.5 px-5">
                            <a href="{{ route('admin.invoices.show', $p->invoice_id) }}" class="font-mono font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $p->invoice->invoice_number }}
                            </a>
                            <div class="text-[11px] text-slate-700 dark:text-slate-300 font-semibold">{{ $p->invoice->patient->name }}</div>
                        </td>

                        <!-- Method & Bank -->
                        <td class="py-3.5 px-5">
                            <div class="font-semibold text-slate-900 dark:text-white capitalize">{{ $p->payment_method }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $p->bank_name ?: 'Standard Terminal' }}</div>
                        </td>

                        <!-- RRN & Batch -->
                        <td class="py-3.5 px-5 font-mono text-xs">
                            <div class="text-slate-900 dark:text-white font-bold">{{ $p->transaction_ref ?: 'NO-REF' }}</div>
                            @if($p->batch_number)
                                <div class="text-[10px] text-slate-400">Batch: {{ $p->batch_number }}</div>
                            @endif
                        </td>

                        <!-- Amount -->
                        <td class="py-3.5 px-5 text-right font-mono font-bold text-slate-900 dark:text-white">
                            RM {{ number_format($p->amount, 2) }}
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-5 text-center">
                            @if($p->is_reconciled)
                                <x-badge variant="emerald" size="sm" dot="true">Reconciled</x-badge>
                            @else
                                <x-badge variant="amber" size="sm" dot="true">Unmatched</x-badge>
                            @endif
                        </td>

                        <!-- Toggle Recon Action -->
                        <td class="py-3.5 px-5 text-right">
                            <form action="{{ route('admin.reports.reconcile-toggle', $p->id) }}" method="POST">
                                @csrf
                                @if($p->is_reconciled)
                                    <button
                                        type="submit"
                                        title="Click to unmatch"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer"
                                    >
                                        <i class="bx bx-undo"></i>
                                        <span>Unmatch</span>
                                    </button>
                                @else
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-400/30 hover:bg-emerald-100 transition cursor-pointer"
                                    >
                                        <i class="bx bx-check"></i>
                                        <span>Match Bank</span>
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                            <i class="bx bx-check-circle text-3xl mb-2 text-slate-500"></i>
                            <p>No digital payment transactions match the selected filter.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>

            <!-- Reusable Pagination & Per-Page Limit -->
            <x-pagination :paginator="$electronicPayments" :perPageOptions="[12, 24, 48, 96]" />
        </x-card>

    </div>

</x-layouts.admin>
