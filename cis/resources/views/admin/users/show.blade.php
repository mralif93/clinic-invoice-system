<x-layouts.admin title="Staff Profile - {{ $user->name }}">

    <div class="space-y-6">

        <!-- Header & Breadcrumb -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition shadow-xs">
                    <i class="bx bx-arrow-back text-lg"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                            {{ $user->name }}
                        </h1>
                        @if($user->status === 'active')
                            <x-badge variant="emerald" dot="true">Active Personnel</x-badge>
                        @elseif($user->status === 'suspended')
                            <x-badge variant="rose" dot="true">Account Suspended</x-badge>
                        @else
                            <x-badge variant="slate">Inactive</x-badge>
                        @endif

                        @if($user->id === auth()->id())
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 border border-indigo-300 dark:border-indigo-800">
                                Current Active Station
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-mono">
                        {{ $user->email }} &bull; Staff ID: {{ $user->staff_id ?? 'N/A' }} &bull; Emp Code: {{ $user->employee_code ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Action Buttons Suite -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <x-button href="{{ route('admin.users.edit', $user->id) }}" variant="primary" size="sm" icon="bx bx-edit">
                    Edit Account
                </x-button>

                <x-button 
                    type="button" 
                    variant="outline" 
                    size="sm" 
                    icon="bx bx-key"
                    onclick="openResetPasswordModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                >
                    Reset Password
                </x-button>

                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                        @csrf
                        <x-button 
                            type="submit" 
                            variant="{{ $user->status === 'active' ? 'secondary' : 'success' }}" 
                            size="sm" 
                            icon="bx {{ $user->status === 'active' ? 'bx-lock' : 'bx-lock-open' }}"
                            onclick="return confirm('Toggle status for {{ addslashes($user->name) }}?')"
                        >
                            {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                        </x-button>
                    </form>

                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Permanently delete account for {{ addslashes($user->name) }}?');">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger" size="sm" icon="bx bx-trash">
                            Delete
                        </x-button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Flash Notices -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
            </x-alert>
        @endif
        @if(session('error'))
            <x-alert type="danger" dismissible="true">
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- Operational Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Invoices Created"
                value="{{ $metrics['total_invoices_generated'] }}"
                icon="bx bx-receipt"
                color="indigo"
                subtitle="Clinical invoices authored"
            />
            <x-stat-card 
                title="Total Invoiced"
                value="RM {{ number_format($metrics['total_invoiced_amount'], 2) }}"
                icon="bx bx-wallet"
                color="purple"
                subtitle="Gross billing value"
            />
            <x-stat-card 
                title="Payments Collected"
                value="RM {{ number_format($metrics['total_payments_collected'], 2) }}"
                icon="bx bx-coin-stack"
                color="emerald"
                subtitle="Cashier collections processed"
            />
            <x-stat-card 
                title="Assigned Roles"
                value="{{ $user->roles->count() }}"
                icon="bx bx-shield-quarter"
                color="blue"
                subtitle="RBAC security profiles"
            />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left 4 Cols: Identity & Security Profile -->
            <div class="lg:col-span-4 space-y-6">

                <!-- User Profile Card -->
                <x-card title="Personnel Identity" icon="bx bx-id-card">
                    <div class="space-y-3.5 text-xs">
                        <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-base shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ $user->name }}</div>
                                <div class="text-[11px] text-slate-400 font-mono truncate">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Department</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $user->department ?: 'General Operations' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Designation / Title</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $user->designation ?: 'Clinical Staff' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Staff Identifier</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $user->staff_id ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Employee Code</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $user->employee_code ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Direct Phone</span>
                            <span class="font-mono text-slate-800 dark:text-slate-200">{{ $user->phone ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Account Registered</span>
                            <span class="font-mono text-slate-800 dark:text-slate-200">{{ $user->created_at ? $user->created_at->format('d M Y, h:i A') : '—' }}</span>
                        </div>
                        <div class="flex justify-between pb-1">
                            <span class="text-slate-400">Last Station Login</span>
                            <span class="font-mono text-slate-800 dark:text-slate-200">{{ $user->last_login_at ? $user->last_login_at->format('d M Y, h:i A') : 'Never' }}</span>
                        </div>
                    </div>
                </x-card>

                <!-- Assigned RBAC Roles Card -->
                <x-card title="Active Role Permissions" icon="bx bx-shield-quarter">
                    <div class="space-y-3">
                        @forelse($user->roles as $role)
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/70 dark:border-slate-800 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-1.5">
                                        <i class="bx bx-check-shield text-indigo-600 dark:text-indigo-400"></i>
                                        <span>{{ $role->display_name }}</span>
                                    </span>
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">
                                        {{ $role->name }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug">
                                    {{ $role->description }}
                                </p>
                                <div class="pt-1 flex flex-wrap gap-1">
                                    @foreach($role->permissions as $perm)
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-mono bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700" title="{{ $perm->display_name }}">
                                            {{ $perm->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">No explicit roles assigned. Falling back to default role: {{ $user->role }}</p>
                        @endforelse
                    </div>
                </x-card>

            </div>

            <!-- Right 8 Cols: Operational History & Audit Trail -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Recent Invoices Created by User -->
                <x-card title="Invoices Authored by Staff" subtitle="Recent 10 billing encounters" icon="bx bx-receipt" noPadding="true">
                    <x-table :headers="[
                        'Invoice #',
                        'Patient',
                        ['label' => 'Total Amount', 'align' => 'right'],
                        ['label' => 'Paid Amount', 'align' => 'right'],
                        ['label' => 'Status', 'align' => 'center'],
                        ['label' => 'Date', 'align' => 'center'],
                        ['label' => 'Action', 'align' => 'right'],
                    ]">
                        @forelse($user->invoices as $inv)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-xs text-indigo-600 dark:text-indigo-400">
                                    <a href="{{ route('admin.invoices.show', $inv->id) }}" class="hover:underline">
                                        {{ $inv->invoice_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-white text-xs">
                                    {{ $inv->patient->name ?? 'Unknown Patient' }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-slate-800 dark:text-slate-200 text-xs">
                                    RM {{ number_format($inv->total_amount, 2) }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-emerald-600 dark:text-emerald-400 text-xs">
                                    RM {{ number_format($inv->paid_amount, 2) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <x-badge :variant="$inv->status === 'paid' ? 'emerald' : ($inv->status === 'partial' ? 'amber' : ($inv->status === 'void' ? 'rose' : 'secondary'))" size="xs">
                                        {{ ucfirst($inv->status) }}
                                    </x-badge>
                                </td>
                                <td class="py-3 px-4 text-center font-mono text-[11px] text-slate-400">
                                    {{ $inv->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.invoices.show', $inv->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950 transition">
                                        <i class="bx bx-show text-base"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                    No invoices created by this practitioner yet.
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                </x-card>

                <!-- Activity & Audit Logs -->
                <x-card title="System Security & Activity Audit" subtitle="Recent action events recorded for this account" icon="bx bx-history">
                    <div class="space-y-3">
                        @forelse($activityLogs as $log)
                            <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800 text-xs">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0">
                                    <i class="bx bx-shield-check"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-slate-900 dark:text-white font-mono text-[11px]">{{ $log->action }}</span>
                                        <span class="text-[10px] font-mono text-slate-400">{{ $log->created_at ? $log->created_at->diffForHumans() : '—' }}</span>
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-300 mt-0.5">{{ $log->description }}</p>
                                    @if($log->ip_address)
                                        <span class="text-[10px] font-mono text-slate-400 mt-1 inline-block">IP: {{ $log->ip_address }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic text-center py-4">No audit activity logs recorded for this personnel.</p>
                        @endforelse
                    </div>
                </x-card>

            </div>

        </div>

    </div>

    <!-- PASSWORD RESET MODAL -->
    <x-modal name="reset-password" title="Direct Password Reset" size="md">
        <form id="reset-password-form" method="POST" action="" class="p-6 space-y-4 text-left">
            @csrf
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Resetting security credentials for: <span id="reset-password-user-name" class="font-bold text-slate-900 dark:text-white"></span>
            </p>
            <x-input label="New Password" name="password" type="password" required placeholder="Minimum 6 characters" />
            <x-input label="Confirm New Password" name="password_confirmation" type="password" required placeholder="Repeat new password" />
            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="document.getElementById('modal-reset-password').classList.add('hidden')">
                    Cancel
                </x-button>
                <x-button variant="warning" size="sm" type="submit">
                    Update Password
                </x-button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function openResetPasswordModal(userId, userName) {
            const form = document.getElementById('reset-password-form');
            form.action = `/admin/users/${userId}/reset-password`;
            document.getElementById('reset-password-user-name').textContent = userName;
            document.getElementById('modal-reset-password').classList.remove('hidden');
        }
    </script>
    @endpush

</x-layouts.admin>
