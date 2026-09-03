<x-layouts.admin title="Patient Profile - {{ $patient->name }}">

    <div class="space-y-6">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.patients.index') }}" class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                    <i class="bx bx-arrow-back text-lg"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                            {{ $patient->name }}
                        </h1>
                        @if($patient->allergies && $patient->allergies !== 'None')
                            <x-badge variant="rose" dot="true">Allergy: {{ $patient->allergies }}</x-badge>
                        @endif
                        @if($patient->outstanding_balance > 0)
                            <x-badge variant="amber" dot="true">Unpaid: RM {{ number_format($patient->outstanding_balance, 2) }}</x-badge>
                        @else
                            <x-badge variant="emerald">Account Clear</x-badge>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-mono">
                        IC: {{ $patient->id_number }} &bull; Phone: {{ $patient->phone }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2.5">
                <x-button href="{{ route('admin.invoices.create') }}" variant="success" size="sm" icon="bx bx-plus-circle">
                    New Invoice
                </x-button>
                <x-button href="{{ route('admin.patients.edit', $patient->id) }}" variant="outline" size="sm" icon="bx bx-edit">
                    Edit Profile
                </x-button>
            </div>
        </div>

        <!-- Flash Notice -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
            </x-alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left: Patient Details & Medical Alert Card -->
            <div class="lg:col-span-4 space-y-6">
                <x-card title="Patient Profile Info">
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Gender</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $patient->gender ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Date of Birth</span>
                            <span class="font-mono text-slate-800 dark:text-slate-200">{{ $patient->date_of_birth ?: 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="text-slate-400">Registered Since</span>
                            <span class="font-mono text-slate-800 dark:text-slate-200">{{ $patient->created_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-1">Residential Address</span>
                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed font-normal">
                                {{ $patient->address ?: 'No address specified' }}
                            </p>
                        </div>
                    </div>
                </x-card>

                <!-- Medical Allergy Box -->
                <div class="p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 space-y-2">
                    <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold text-xs">
                        <i class="bx bx-error-alt text-base"></i>
                        <span>Medical Allergies &amp; Contraindications</span>
                    </div>
                    <p class="text-xs text-rose-800 dark:text-rose-300 leading-relaxed font-medium">
                        {{ $patient->allergies ?: 'No known drug allergies reported by patient.' }}
                    </p>
                </div>
            </div>

            <!-- Right: Billing & Treatment Invoices History -->
            <div class="lg:col-span-8 space-y-6">
                <x-card title="Patient Invoices &amp; Billing History" subtitle="{{ $patient->invoices->count() }} invoice(s) recorded on file" noPadding="true">
                    <x-table :headers="[
                        'Invoice #',
                        'Attending Doctor',
                        ['label' => 'Total', 'align' => 'right'],
                        ['label' => 'Paid', 'align' => 'right'],
                        ['label' => 'Status', 'align' => 'center'],
                        ['label' => 'Date', 'align' => 'center'],
                        ['label' => 'Action', 'align' => 'right'],
                    ]">
                        @forelse($patient->invoices as $inv)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-5 font-mono font-bold">
                                    <a href="{{ route('admin.invoices.show', $inv->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $inv->invoice_number }}
                                    </a>
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
                                <td class="py-3.5 px-5 text-center font-mono text-[11px] text-slate-400">
                                    {{ $inv->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <a href="{{ route('admin.invoices.show', $inv->id) }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                        View Bill
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                    No past invoices found for this patient.
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                </x-card>
            </div>

        </div>

    </div>

</x-layouts.admin>
