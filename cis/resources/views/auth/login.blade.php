<x-layouts.public title="Staff & Admin Login">

    <div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="w-full max-w-md animate__animated animate__fadeInUp animate__faster">

            <!-- Login Card -->
            <x-card class="p-2 sm:p-4 shadow-2xl shadow-indigo-500/10">
                
                <!-- Card Header -->
                <div class="space-y-2 text-center mb-6 pt-2">
                    <div class="inline-flex p-3 rounded-2xl bg-indigo-50 dark:bg-indigo-600/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 shadow-inner mb-1">
                        <i class="bx bx-shield-quarter text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Staff Authentication</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Enter your official clinic credentials to access billing</p>
                </div>

                <!-- Flash Alerts -->
                @if (session('success'))
                    <x-alert type="success" class="mb-4">
                        {{ session('success') }}
                    </x-alert>
                @endif

                @if (session('status'))
                    <x-alert type="info" class="mb-4">
                        {{ session('status') }}
                    </x-alert>
                @endif

                @if ($errors->any())
                    <x-alert type="danger" class="mb-4">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email or Staff ID Input Component -->
                    <x-input
                        label="Email Address or Staff ID"
                        name="email"
                        id="email"
                        value="admin@clinic.my"
                        icon="bx bx-user"
                        placeholder="admin@clinic.my or ADM-001"
                        required
                    />

                    <!-- Password Input Component -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <i class="bx bx-lock-alt text-lg"></i>
                            </div>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                placeholder="••••••••"
                                value="password"
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition"
                            >
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-white dark:focus:ring-offset-slate-900">
                            <span class="text-xs text-slate-600 dark:text-slate-400">Remember this station</span>
                        </label>
                    </div>

                    <!-- Submit Button Component -->
                    <x-button type="submit" variant="primary" size="md" icon="bx bx-log-in-circle" class="w-full mt-2">
                        Authenticate Station
                    </x-button>
                </form>

                <!-- Pre-configured Test Accounts Quick Fill -->
                <div class="mt-6 pt-5 border-t border-slate-200 dark:border-slate-800 space-y-2">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                        <i class="bx bx-key text-indigo-600 dark:text-indigo-400"></i>
                        <span>Default Clinic Credentials (Seeded)</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button
                            type="button"
                            onclick="document.getElementById('email').value='admin@clinic.my'; document.getElementById('password').value='password';"
                            class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-left hover:border-indigo-500 transition cursor-pointer"
                        >
                            <div class="font-bold text-slate-900 dark:text-white flex items-center justify-between">
                                <span>Admin / Doctor</span>
                                <i class="bx bx-check text-xs text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">admin@clinic.my</div>
                        </button>
                        <button
                            type="button"
                            onclick="document.getElementById('email').value='cashier@clinic.my'; document.getElementById('password').value='password';"
                            class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-left hover:border-indigo-500 transition cursor-pointer"
                        >
                            <div class="font-bold text-slate-900 dark:text-white flex items-center justify-between">
                                <span>Cashier / Staff</span>
                                <i class="bx bx-check text-xs text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">cashier@clinic.my</div>
                        </button>
                    </div>
                </div>

            </x-card>

        </div>
    </div>

</x-layouts.public>
