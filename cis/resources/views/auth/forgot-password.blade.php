<x-layouts.public title="Reset Password">

    <div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="w-full max-w-md animate__animated animate__fadeInUp animate__faster">

            <!-- Forgot Password Card -->
            <div class="relative overflow-hidden rounded-3xl bg-white/90 dark:bg-gradient-to-b dark:from-slate-800/90 dark:to-slate-900/90 backdrop-blur-xl border border-slate-200 dark:border-indigo-900/60 p-7 sm:p-9 shadow-2xl shadow-slate-300/50 dark:shadow-slate-950/60 transition-colors">
                
                <!-- Top Subtle Glow -->
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/15 rounded-full blur-2xl pointer-events-none"></div>

                <!-- Card Header -->
                <div class="space-y-2 text-center mb-7">
                    <div class="inline-flex p-3 rounded-2xl bg-indigo-50 dark:bg-indigo-600/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 shadow-inner mb-1">
                        <i class="bx bx-lock-open text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Password Recovery</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">Enter your verified clinic email address and we'll dispatch password reset instructions</p>
                </div>

                <!-- Flash Alerts -->
                @if (session('status'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-xs flex items-center gap-2">
                        <i class="bx bx-check-circle text-base"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-300 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <i class="bx bx-error-circle text-base text-rose-500 dark:text-rose-400"></i>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Registered Clinic Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <i class="bx bx-envelope text-lg"></i>
                            </div>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="admin@clinic.my"
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition"
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full mt-3 py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-xl shadow-indigo-600/30 border border-indigo-500/30 transition-all flex items-center justify-center gap-2 transform active:scale-[0.98] cursor-pointer"
                    >
                        <i class="bx bx-mail-send text-lg"></i>
                        <span>Send Reset Authorization</span>
                    </button>
                </form>

                <!-- Back to Login Navigation -->
                <div class="mt-6 pt-5 border-t border-slate-200 dark:border-slate-700/60 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition cursor-pointer">
                        <i class="bx bx-arrow-back"></i>
                        <span>Return to Authentication Screen</span>
                    </a>
                </div>

            </div>

        </div>
    </div>

</x-layouts.public>
