<x-layouts.public title="Sign In - ClinicFlow Invoicing System">

<div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
    <div class="w-full max-w-md animate__animated animate__fadeInUp animate__faster">
        
        <!-- Card Container -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl shadow-indigo-500/10 p-8 sm:p-10 transition-colors">
            
            <!-- Header & Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 text-white shadow-lg shadow-indigo-600/30 border border-indigo-400/30 mb-4">
                    <i class="bx bx-receipt text-3xl"></i>
                </div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Staff Portal Sign In</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">Access clinic billing, patient invoicing, and cashier terminal</p>
            </div>

            <!-- Session Status / Flash Alert -->
            @if(session('status') || request()->has('logged_out'))
                <div id="logout-alert" class="mb-6 p-4 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-400 dark:border-emerald-600/60 text-emerald-800 dark:text-emerald-300 text-xs flex items-center justify-between gap-3 animate__animated animate__fadeIn">
                    <div class="flex items-center gap-3">
                        <i class="bx bx-check-circle text-xl text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                        <span class="font-medium text-[13px] text-emerald-800 dark:text-emerald-200">
                            {{ session('status') ?? (request()->has('logged_out') ? 'You have been logged out securely.' : 'You have been logged out securely.') }}
                        </span>
                    </div>
                    <button type="button" onclick="document.getElementById('logout-alert').remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-1 cursor-pointer" aria-label="Dismiss">
                        <i class="bx bx-x text-base"></i>
                    </button>
                </div>
            @endif

            <!-- CentraFlow SSO Primary Action -->
            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 text-center">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600/10 dark:bg-indigo-400/10 text-indigo-600 dark:text-indigo-400 mb-2">
                        <i class="bx bx-shield-quarter text-2xl"></i>
                    </div>
                    <p class="text-xs font-semibold text-indigo-950 dark:text-indigo-200">
                        Enterprise Identity Protection Enforced
                    </p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Authentication for ClinicFlow is centrally managed by CentraFlow Identity Hub. Click below to sign in with your corporate credentials.
                    </p>
                </div>

                @if($errors->has('oauth'))
                    <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-300 text-xs flex items-center gap-2">
                        <i class="bx bx-error-circle text-lg shrink-0"></i>
                        <span>{{ $errors->first('oauth') }}</span>
                    </div>
                @endif

                @if($errors->any() && !$errors->has('oauth'))
                    <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-300 text-xs flex items-center gap-2">
                        <i class="bx bx-error-circle text-lg shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <a href="{{ route('sso.login') }}" 
                   class="w-full inline-flex items-center justify-center gap-3 py-4 px-5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-700 hover:from-indigo-500 hover:to-blue-600 shadow-xl shadow-indigo-600/30 border border-indigo-400/30 active:scale-[0.99] transition-all cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Sign in with CentraFlow SSO</span>
                </a>

                <div class="pt-2 text-center">
                    <span class="text-[11px] text-slate-400 flex items-center justify-center gap-1.5 font-mono">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        CentraFlow OAuth 2.0 Server Active (:8004)
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>

</x-layouts.public>
