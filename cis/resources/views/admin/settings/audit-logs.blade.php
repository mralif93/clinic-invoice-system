<x-layouts.admin title="Audit Trail & Security Logs">

    <div class="space-y-6">

        <!-- Page Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-6 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-amber-300 font-bold border border-white/10">
                            <i class="bx bx-history text-lg"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Immutable Audit Trail &amp; Activity Log</h1>
                    </div>
                    <p class="text-xs text-slate-300">FR-7.1 &amp; FR-7.2 &bull; Automatically track sensitive clinical events, void justifications, payments &amp; security logs</p>
                </div>
            </div>
        </div>

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card
                title="Total Logged Events"
                value="{{ $stats['total_logs'] }}"
                icon="bx bx-receipt"
                color="indigo"
                subtitle="Permanent system audit records"
            />
            <x-stat-card
                title="Sensitive Void Audits"
                value="{{ $stats['sensitive_voids'] }}"
                icon="bx bx-shield-x"
                color="rose"
                subtitle="Voided invoice transactions"
            />
            <x-stat-card
                title="Governance Profile Changes"
                value="{{ $stats['governance_updates'] }}"
                icon="bx bx-cog"
                color="amber"
                subtitle="Clinic identity parameter edits"
            />
        </div>

        <!-- Reusable Collapsible Filter Toolbar -->
        <x-filter-toolbar
            title="Search &amp; Filter Audit Trail"
            subtitle="Filter tamper-proof clinical activity logs by audit reference, staff name, system module, or event type"
            :action="route('admin.settings.audit-logs')"
            searchPlaceholder="Search audit reference, staff user, IP address, or event description..."
            :searchValue="request('search')"
            :resetUrl="route('admin.settings.audit-logs')"
            :defaultOpen="true"
        >
            <x-slot:filters>
                <x-select label="System Module" name="module">
                    <option value="all">All Modules</option>
                    <option value="Billing" {{ request('module') === 'Billing' ? 'selected' : '' }}>Billing &amp; Invoices</option>
                    <option value="MasterData" {{ request('module') === 'MasterData' ? 'selected' : '' }}>Clinical Master Data</option>
                    <option value="Governance" {{ request('module') === 'Governance' ? 'selected' : '' }}>Governance &amp; Settings</option>
                </x-select>

                <x-select label="Event Action" name="action">
                    <option value="all">All Event Actions</option>
                    <option value="INVOICE_CREATED" {{ request('action') === 'INVOICE_CREATED' ? 'selected' : '' }}>INVOICE_CREATED</option>
                    <option value="INVOICE_VOIDED" {{ request('action') === 'INVOICE_VOIDED' ? 'selected' : '' }}>INVOICE_VOIDED</option>
                    <option value="PAYMENT_RECEIVED" {{ request('action') === 'PAYMENT_RECEIVED' ? 'selected' : '' }}>PAYMENT_RECEIVED</option>
                    <option value="SETTINGS_UPDATED" {{ request('action') === 'SETTINGS_UPDATED' ? 'selected' : '' }}>SETTINGS_UPDATED</option>
                </x-select>
            </x-slot:filters>
        </x-filter-toolbar>

        <!-- Audit Trail Table Card -->
        <x-card noPadding="true">
            <x-table :headers="[
                'Timestamp & IP',
                'User & Staff ID',
                'Event Action',
                'Reference ID',
                'Description & Context',
            ]">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <!-- Timestamp -->
                        <td class="py-3.5 px-5 font-mono text-xs">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $log->created_at->format('d M Y, H:i:s') }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $log->ip_address ?: '127.0.0.1' }}</div>
                        </td>

                        <!-- User -->
                        <td class="py-3.5 px-5">
                            <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $log->user_name }}</div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $log->user?->staff_id ?? 'SYS-AUTH' }}</div>
                        </td>

                        <!-- Action Badge -->
                        <td class="py-3.5 px-5">
                            @if(str_contains($log->action, 'VOID'))
                                <x-badge variant="rose" size="sm" dot="true">{{ $log->action }}</x-badge>
                            @elseif(str_contains($log->action, 'CREATED'))
                                <x-badge variant="emerald" size="sm">{{ $log->action }}</x-badge>
                            @elseif(str_contains($log->action, 'PAYMENT'))
                                <x-badge variant="indigo" size="sm">{{ $log->action }}</x-badge>
                            @else
                                <x-badge variant="slate" size="sm">{{ $log->action }}</x-badge>
                            @endif
                        </td>

                        <!-- Target Ref -->
                        <td class="py-3.5 px-5 font-mono font-bold text-xs text-indigo-600 dark:text-indigo-400">
                            {{ $log->target_reference ?: '-' }}
                        </td>

                        <!-- Description -->
                        <td class="py-3.5 px-5 text-xs text-slate-700 dark:text-slate-300">
                            <div>{{ $log->description }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                            <i class="bx bx-shield text-3xl mb-2 text-slate-500"></i>
                            <p>No audit trail records found matching criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>

            <!-- Reusable Pagination & Per-Page Limit -->
            <x-pagination :paginator="$logs" :perPageOptions="[15, 30, 50, 100]" />
        </x-card>

    </div>

</x-layouts.admin>
