<x-layouts.admin title="Edit Patient - {{ $patient->name }}">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.patients.show', $patient->id) }}" class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                    <i class="bx bx-arrow-back text-lg"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Edit Patient Record</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Update identification or medical allergy data for {{ $patient->name }}</p>
                </div>
            </div>
        </div>

        @if($errors->any())
            <x-alert type="danger" dismissible="true">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-card title="Edit Profile Form">
            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <x-input
                        label="Full Name (as per IC / Passport)"
                        name="name"
                        value="{{ $patient->name }}"
                        icon="bx bx-user"
                        required
                    />

                    <!-- IC / Passport -->
                    <x-input
                        label="IC Number / Passport No."
                        name="id_number"
                        value="{{ $patient->id_number }}"
                        icon="bx bx-id-card"
                        required
                    />

                    <!-- Phone Number -->
                    <x-input
                        label="Contact Phone"
                        name="phone"
                        value="{{ $patient->phone }}"
                        icon="bx bx-phone"
                        required
                    />

                    <!-- Date of Birth -->
                    <x-input
                        label="Date of Birth"
                        name="date_of_birth"
                        type="date"
                        value="{{ $patient->date_of_birth }}"
                        icon="bx bx-calendar"
                    />

                    <!-- Gender -->
                    <div class="space-y-1.5">
                        <label for="gender" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Gender
                        </label>
                        <select
                            name="gender"
                            id="gender"
                            class="w-full py-3 px-3.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition"
                        >
                            <option value="Male" {{ $patient->gender === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $patient->gender === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ $patient->gender === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Medical Allergies -->
                    <x-input
                        label="Medical Allergies Alert"
                        name="allergies"
                        value="{{ $patient->allergies }}"
                        icon="bx bx-error-alt"
                    />
                </div>

                <!-- Address -->
                <div class="space-y-1.5">
                    <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Residential Address
                    </label>
                    <textarea
                        name="address"
                        id="address"
                        rows="2"
                        class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition"
                    >{{ $patient->address }}</textarea>
                </div>

                <!-- Submit Action -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                    <x-button href="{{ route('admin.patients.show', $patient->id) }}" variant="ghost" size="md">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary" size="md" icon="bx bx-check">
                        Update Patient Profile
                    </x-button>
                </div>
            </form>
        </x-card>

    </div>

</x-layouts.admin>
