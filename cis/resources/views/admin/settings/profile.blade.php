<x-layouts.admin title="Clinic Identity & Invoicing Profile">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Page Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-6 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300 font-bold border border-white/10">
                            <i class="bx bx-clinic text-lg"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Clinic Profile &amp; Governance Settings</h1>
                    </div>
                    <p class="text-xs text-slate-300">FR-6.1 &bull; Manage clinic legal identity, official SSM/MOH registration, and default invoice/receipt terms</p>
                </div>
            </div>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
            </x-alert>
        @endif

        @if($errors->any())
            <x-alert type="danger" dismissible="true">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <!-- Profile Form -->
        <form action="{{ route('admin.settings.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Legal Entity & Clinic Contact Info -->
            <x-card title="Official Clinic Identification" subtitle="Details printed in the header of all tax invoices and thermal receipts">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input
                            label="Clinic Name"
                            name="clinic_name"
                            value="{{ $profile->clinic_name }}"
                            icon="bx bx-building"
                            required
                        />

                        <x-input
                            label="SSM / MOH Registration No."
                            name="registration_number"
                            value="{{ $profile->registration_number }}"
                            icon="bx bx-id-card"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input
                            label="Official Contact Phone"
                            name="phone"
                            value="{{ $profile->phone }}"
                            icon="bx bx-phone"
                            required
                        />

                        <x-input
                            label="Official Clinic Email"
                            name="email"
                            type="email"
                            value="{{ $profile->email }}"
                            icon="bx bx-envelope"
                            required
                        />
                    </div>

                    <div class="space-y-1.5">
                        <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Clinic Street Address <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            name="address"
                            id="address"
                            rows="2"
                            required
                            class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:outline-none transition"
                        >{{ $profile->address }}</textarea>
                    </div>
                </div>
            </x-card>

            <!-- 2. Financial & Receipt Footers -->
            <x-card title="Receipt & Invoicing Parameters" subtitle="FR-6.2 &bull; Customize footer disclaimers, insurance terms, and currency">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input
                            label="Currency Symbol"
                            name="currency_symbol"
                            value="{{ $profile->currency_symbol }}"
                            icon="bx bx-dollar"
                            required
                        />

                        <x-input
                            label="Default Tax / SST Rate (%)"
                            name="default_tax_rate"
                            type="number"
                            step="0.01"
                            min="0"
                            value="{{ $profile->default_tax_rate }}"
                            icon="bx bx-receipt"
                        />
                    </div>

                    <div class="space-y-1.5">
                        <label for="invoice_terms" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            A4 Formal Invoice Terms &amp; Medical Disclaimer
                        </label>
                        <textarea
                            name="invoice_terms"
                            id="invoice_terms"
                            rows="2"
                            class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:outline-none transition"
                        >{{ $profile->invoice_terms }}</textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label for="receipt_footer" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            80mm Thermal Receipt Footer Message
                        </label>
                        <textarea
                            name="receipt_footer"
                            id="receipt_footer"
                            rows="2"
                            class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:outline-none transition"
                        >{{ $profile->receipt_footer }}</textarea>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                    <x-button type="submit" variant="primary" size="md" icon="bx bx-save">
                        Save Governance Settings
                    </x-button>
                </div>
            </x-card>
        </form>

    </div>

</x-layouts.admin>
