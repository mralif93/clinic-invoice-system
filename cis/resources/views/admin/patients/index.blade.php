<x-layouts.admin title="Patients Master">

    <div class="space-y-6">

        <!-- Page Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-5 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                            <i class="bx bx-user-pin text-xl"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Clinical Patients Master</h1>
                    </div>
                    <p class="text-xs text-slate-300">FR-1.1 &bull; Register patients, manage medical allergies, track billing history &amp; credit debt</p>
                </div>

                <div class="flex items-center gap-3">
                    <x-button href="{{ route('admin.patients.create') }}" variant="success" size="md" icon="bx bx-user-plus" class="shadow-lg shadow-emerald-600/30">
                        Register Patient
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
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

        <!-- Quick Summary Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card
                title="Registered Patients"
                value="{{ $stats['total_patients'] }}"
                icon="bx bx-user-check"
                color="indigo"
                subtitle="Active patient records on file"
            />
            <x-stat-card
                title="Patients with Known Allergies"
                value="{{ $stats['with_allergies'] }}"
                icon="bx bx-error-alt"
                color="rose"
                subtitle="Requires clinical allergy tagging"
            />
            <x-stat-card
                title="Total Outstanding Debt"
                value="RM {{ number_format($stats['total_unpaid_balance'], 2) }}"
                icon="bx bx-time-five"
                color="amber"
                subtitle="Pending collection across all accounts"
            />
        </div>

        <!-- Reusable Search Toolbar -->
        <x-filter-toolbar
            title="Search Patient Records"
            subtitle="Look up patient profiles by full name, NRIC/Passport, phone number, or known drug allergies"
            :action="route('admin.patients.index')"
            searchPlaceholder="Search patients by name, IC/Passport number, phone, or allergies..."
            :searchValue="request('search')"
            :resetUrl="route('admin.patients.index')"
            :defaultOpen="true"
        />

        <!-- 1. Mobile-Optimized Patient Card List (Visible on phones < md) -->
        <div class="grid grid-cols-1 gap-3.5 md:hidden">
            @forelse($patients as $patient)
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-xs space-y-3">
                    
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0 border border-indigo-200 dark:border-indigo-800">
                                {{ strtoupper(substr($patient->name, 0, 2)) }}
                            </div>
                            <div>
                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="font-bold text-sm text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                                    {{ $patient->name }}
                                </a>
                                <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400">
                                    IC: {{ $patient->id_number }} &bull; {{ $patient->phone }}
                                </div>
                            </div>
                        </div>

                        @if($patient->allergies && $patient->allergies !== 'None')
                            <x-badge variant="rose" size="sm" dot="true">
                                {{ $patient->allergies }}
                            </x-badge>
                        @endif
                    </div>

                    <!-- Balance & Actions -->
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Unpaid Debt</div>
                            <div class="font-mono text-xs font-bold {{ $patient->outstanding_balance > 0 ? 'text-amber-500' : 'text-emerald-500' }}">
                                RM {{ number_format($patient->outstanding_balance, 2) }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <x-button href="{{ route('admin.patients.edit', $patient->id) }}" variant="ghost" size="sm">
                                Edit
                            </x-button>
                            <x-button href="{{ route('admin.patients.show', $patient->id) }}" variant="outline" size="sm" icon="bx bx-receipt">
                                Profile
                            </x-button>
                        </div>
                    </div>

                </div>
            @empty
                <div class="p-8 text-center rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 text-xs">
                    <i class="bx bx-user-x text-3xl mb-2 text-slate-400"></i>
                    <p>No patient records match your search criteria.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. Desktop High-Density Table Card (Visible on md and up) -->
        <x-card noPadding="true" class="hidden md:block">
            <x-table :headers="[
                'Patient Name & Contact',
                'IC / Passport',
                'Gender & DOB',
                'Medical Allergies',
                ['label' => 'Outstanding Balance', 'align' => 'right'],
                ['label' => 'Actions', 'align' => 'right'],
            ]">
                @forelse($patients as $patient)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-5">
                            <a href="{{ route('admin.patients.show', $patient->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                                {{ $patient->name }}
                            </a>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">{{ $patient->phone }}</div>
                        </td>

                        <td class="py-3.5 px-5 font-mono text-slate-700 dark:text-slate-300 font-semibold">
                            {{ $patient->id_number }}
                        </td>

                        <td class="py-3.5 px-5 text-slate-600 dark:text-slate-400 text-xs">
                            <div>{{ $patient->gender ?: 'Not specified' }}</div>
                            <div class="text-[11px] font-mono text-slate-400">{{ $patient->date_of_birth ?: 'DOB: N/A' }}</div>
                        </td>

                        <td class="py-3.5 px-5">
                            @if($patient->allergies && $patient->allergies !== 'None')
                                <x-badge variant="rose" size="sm" dot="true">
                                    {{ $patient->allergies }}
                                </x-badge>
                            @else
                                <span class="text-slate-400 text-xs">None declared</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-5 text-right font-mono font-bold">
                            @if($patient->outstanding_balance > 0)
                                <span class="text-amber-500">RM {{ number_format($patient->outstanding_balance, 2) }}</span>
                            @else
                                <span class="text-emerald-500 font-medium">RM 0.00</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-5 text-right space-x-2">
                            <x-button href="{{ route('admin.patients.show', $patient->id) }}" variant="outline" size="sm">
                                Profile &amp; Bills
                            </x-button>
                            <x-button href="{{ route('admin.patients.edit', $patient->id) }}" variant="ghost" size="sm">
                                Edit
                            </x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                            <i class="bx bx-user-x text-3xl mb-2 text-slate-400"></i>
                            <p>No patient records match your criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>

            <!-- Reusable Pagination & Per-Page Limit -->
            <x-pagination :paginator="$patients" :perPageOptions="[10, 25, 50, 100]" />
        </x-card>

        <!-- Mobile Pagination -->
        <div class="md:hidden">
            <x-pagination :paginator="$patients" :perPageOptions="[10, 25, 50, 100]" class="rounded-2xl border" />
        </div>

    </div>

</x-layouts.admin>
