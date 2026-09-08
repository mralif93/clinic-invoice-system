<x-layouts.admin title="Edit Personnel - {{ $user->name }}">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Breadcrumbs & Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.show', $user->id) }}" class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition shadow-xs">
                    <i class="bx bx-arrow-back text-lg"></i>
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Edit Staff Personnel
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Updating profile &amp; security privileges for <span class="font-bold text-slate-800 dark:text-slate-200">{{ $user->name }}</span> ({{ $user->email }})
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <x-button href="{{ route('admin.users.show', $user->id) }}" variant="outline" size="sm" icon="bx bx-id-card">
                    View Profile
                </x-button>
                <x-button href="{{ route('admin.users.index') }}" variant="secondary" size="sm" icon="bx bx-list-ul">
                    Directory
                </x-button>
            </div>
        </div>

        @if($errors->any())
            <x-alert type="danger" dismissible="true">
                <div class="font-bold mb-1">Please fix the following validation errors:</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Account Identity -->
            <x-card title="Personal & Clinical Identity" subtitle="Update staff credentials and official records" icon="bx bx-id-card">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input 
                            label="Full Legal / Practitioner Name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}" 
                            required 
                            icon="bx bx-user"
                        />
                        <x-input 
                            label="Official Work Email" 
                            name="email" 
                            type="email" 
                            value="{{ old('email', $user->email) }}" 
                            required 
                            icon="bx bx-envelope"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-input 
                            label="Clinic Staff ID" 
                            name="staff_id" 
                            value="{{ old('staff_id', $user->staff_id) }}" 
                            icon="bx bx-badge"
                        />
                        <x-input 
                            label="Employee Code" 
                            name="employee_code" 
                            value="{{ old('employee_code', $user->employee_code) }}" 
                            icon="bx bx-hash"
                        />
                        <x-input 
                            label="Phone Number" 
                            name="phone" 
                            value="{{ old('phone', $user->phone) }}" 
                            icon="bx bx-phone"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input 
                            label="Department / Unit" 
                            name="department" 
                            value="{{ old('department', $user->department) }}" 
                            icon="bx bx-building"
                        />
                        <x-input 
                            label="Designation / Clinical Rank" 
                            name="designation" 
                            value="{{ old('designation', $user->designation) }}" 
                            icon="bx bx-briefcase"
                        />
                    </div>
                </div>
            </x-card>

            <!-- Section 2: Security Credentials & Access State -->
            <x-card title="Security & Access Status" subtitle="Password changes and account locks" icon="bx bx-lock-alt">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input 
                            label="Change Password (Optional)" 
                            name="password" 
                            type="password" 
                            placeholder="Leave blank to keep existing password" 
                            icon="bx bx-key"
                        />
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                                Account Access Status <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" required class="w-full py-2.5 px-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active - Operational Station</option>
                                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive - Restricted</option>
                                <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended - Station Locked</option>
                            </select>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Section 3: Role-Based Access Control (RBAC) -->
            <x-card title="Assigned Access Roles" subtitle="Configure operational role matrix" icon="bx bx-shield-quarter">
                @php
                    $assignedRoleIds = old('role_ids', $user->roles->pluck('id')->toArray());
                @endphp
                <div class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        @foreach($roles as $role)
                            <label class="relative flex items-start gap-3 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 hover:bg-white dark:hover:bg-slate-800/80 hover:border-indigo-300 dark:hover:border-indigo-800 transition cursor-pointer group">
                                <input 
                                    type="checkbox" 
                                    name="role_ids[]" 
                                    value="{{ $role->id }}" 
                                    {{ in_array($role->id, $assignedRoleIds) ? 'checked' : '' }}
                                    class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                            {{ $role->display_name }}
                                        </span>
                                        @if($role->is_system)
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                                                Core
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                                        {{ $role->description ?? 'Operational role permissions profile.' }}
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </x-card>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4">
                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Permanently delete account for {{ addslashes($user->name) }}?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger" size="md" icon="bx bx-trash">
                            Delete Account
                        </x-button>
                    </form>
                @else
                    <div></div>
                @endif

                <div class="flex items-center gap-3">
                    <x-button href="{{ route('admin.users.show', $user->id) }}" variant="secondary" size="md">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary" size="md" icon="bx bx-check">
                        Save Changes
                    </x-button>
                </div>
            </div>
        </form>

    </div>

</x-layouts.admin>
