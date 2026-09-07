<x-layouts.public title="Modern Medical Billing & Invoicing">

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16 lg:pt-16 lg:pb-24" id="overview">
        
        <!-- Executive Hero Banner with Signature Deep Indigo Gradient -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-8 sm:p-12 lg:p-14 shadow-2xl shadow-indigo-950/40 border border-indigo-800/40 mb-12 text-white">
            <!-- Background Decorative Glow Elements -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-24 w-64 h-64 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 bottom-0 w-48 h-48 bg-indigo-600/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Hero Headline & Value Proposition -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 font-bold text-lg shadow-xs border border-white/10">
                            <i class="bx bx-pulse"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Clinic Operations Ready
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                            SRS Compliant
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-[1.18]">
                        Precision Medical Invoicing &amp; Cashier Settlement
                    </h1>

                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl font-normal">
                        Streamline patient billing, itemized catalog pricing, multi-channel payment reconciliation (Cash, Card, QR Pay / DuitNow), and instant PDF/80mm thermal receipt generation.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-xl shadow-indigo-600/40 border border-indigo-400/30 transition-all transform hover:-translate-y-0.5">
                                <i class="bx bxs-dashboard text-lg"></i>
                                <span>Enter Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-xl shadow-indigo-600/40 border border-indigo-400/30 transition-all transform hover:-translate-y-0.5">
                                <i class="bx bx-shield-quarter text-lg"></i>
                                <span>Staff Login</span>
                            </a>
                            <a href="#modules" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm bg-slate-800/90 text-slate-200 hover:text-white hover:bg-slate-700/90 border border-slate-700 shadow-md transition-all">
                                <i class="bx bx-layer text-lg text-indigo-400"></i>
                                <span>Explore Modules</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right: Live System Status / Metrics Cards -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="p-5 rounded-2xl bg-slate-800/90 dark:bg-slate-800/80 backdrop-blur-md border border-indigo-700/30 shadow-xl space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-700/60 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-300 flex items-center justify-center text-lg">
                                    <i class="bx bx-file-blank"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white uppercase tracking-wider">Live Invoice Benchmark</div>
                                    <div class="text-[11px] text-slate-400 font-mono">INV-20260901-0042</div>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 flex items-center gap-1">
                                <i class="bx bx-check-circle"></i> Paid
                            </span>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between text-slate-300">
                                <span>Patient</span>
                                <span class="font-semibold text-white">Sarah Jenkins (IC: 920412-14-5542)</span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span>Attending Doctor</span>
                                <span class="font-semibold text-white">Dr. Aiman Hakim</span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span>Items (Consultation + Rx)</span>
                                <span class="font-mono text-slate-300">3 line items</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-slate-700/60 text-slate-200">
                                <span class="font-bold text-white">Total Settled</span>
                                <span class="font-mono text-base font-extrabold text-emerald-400">RM 74.70</span>
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-700/60">
                            <span class="flex items-center gap-1"><i class="bx bx-qr-scan text-indigo-400"></i> DuitNow QR Pay</span>
                            <span class="font-mono text-slate-400">Ref: #RRN-84920412</span>
                        </div>
                    </div>

                    <!-- Mini stats banner -->
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-slate-800/80 dark:bg-slate-800/60 border border-slate-700/60">
                            <div class="text-slate-400 text-[11px] font-medium">Response SLA</div>
                            <div class="text-base font-mono font-bold text-indigo-300">&lt; 300 ms</div>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-800/80 dark:bg-slate-800/60 border border-slate-700/60">
                            <div class="text-slate-400 text-[11px] font-medium">Print Outputs</div>
                            <div class="text-base font-bold text-indigo-300">A4 PDF &amp; 80mm</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Stats Bar Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-16">
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 shadow-xs dark:shadow-md transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase text-slate-400 dark:text-slate-500">Module 1</span>
                    <i class="bx bx-user-pin text-xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="text-lg font-extrabold text-slate-900 dark:text-white">Patient Master</div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Real-time IC &amp; phone lookup with allergy tagging.</p>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 shadow-xs dark:shadow-md transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase text-slate-400 dark:text-slate-500">Module 2 &amp; 3</span>
                    <i class="bx bx-calculator text-xl text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div class="text-lg font-extrabold text-slate-900 dark:text-white">Item Master &amp; Billing</div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Real-time dynamic taxes, discounts, and SKU pricing.</p>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 shadow-xs dark:shadow-md transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase text-slate-400 dark:text-slate-500">Module 4</span>
                    <i class="bx bx-credit-card text-xl text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="text-lg font-extrabold text-slate-900 dark:text-white">Multi-Channel Pay</div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">QR DuitNow, card EDC terminals, cash &amp; panel guarantees.</p>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 shadow-xs dark:shadow-md transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase text-slate-400 dark:text-slate-500">Module 5 &amp; 7</span>
                    <i class="bx bx-check-shield text-xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="text-lg font-extrabold text-slate-900 dark:text-white">Audit &amp; Reconciliation</div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">End-of-day bank reconciliation and void action logs.</p>
            </div>
        </div>

        <!-- SRS Modules Feature Suite -->
        <div id="modules" class="space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
                <div>
                    <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wider">
                        <i class="bx bx-layer"></i>
                        <span>System Architecture</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">Complete Clinical Workflow Modules</h2>
                </div>
                <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                    Laravel 12 &bull; Tailwind CSS &bull; RBAC Protected
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 shadow-xs dark:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="bx bx-user-check text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Patient Records Management</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Register and instantly query patient records by IC/Passport number, name, or phone. View complete billing histories and unpaid balances.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-300 font-mono">
                        <i class="bx bx-badge-check"></i> FR-1.1 to FR-1.3
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 shadow-xs dark:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="bx bx-capsule text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Treatment &amp; Item Master</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Organize clinical catalog by Consultation, Procedures, Lab Diagnostics, and Medication stocks with unit rates and tax configurations.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-300 font-mono">
                        <i class="bx bx-badge-check"></i> FR-2.1 to FR-2.2
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 shadow-xs dark:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="bx bx-receipt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Invoice Generation &amp; Lifecycles</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Dynamic calculation engine for line totals, discount rules, and SST. Supports Draft, Unpaid, Partially Paid, Paid, and Void states.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-300 font-mono">
                        <i class="bx bx-badge-check"></i> FR-3.1 to FR-3.3
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 shadow-xs dark:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="bx bx-wallet text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Multi-Channel Settlement</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Accept Cash, EDC Debit/Credit cards, DuitNow QR Pay, and Insurance Panels with split payments and audit reference capturing.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-300 font-mono">
                        <i class="bx bx-badge-check"></i> FR-4.1 to FR-4.4
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 shadow-xs dark:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="bx bx-printer text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">A4 PDF &amp; Thermal Receipts</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        Interactive in-browser PDF preview with zoom/page controls alongside 80mm thermal receipt printing for rapid front-desk discharge.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-300 font-mono">
                        <i class="bx bx-badge-check"></i> FR-3.4 to FR-3.5
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 shadow-xs dark:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="bx bx-shield-quarter text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Bank Reconciliation &amp; Audit Logs</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                        End-of-day EDC card settlement tally, cash drawer reconciliation, and immutable audit logs capturing every invoice void or price alteration.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-300 font-mono">
                        <i class="bx bx-badge-check"></i> FR-5.1 to FR-7.2
                    </div>
                </div>
            </div>
        </div>

        <!-- Settlement & POS Drawer Section -->
        <div id="settlement" class="mt-16 p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wider">
                        <i class="bx bx-transfer-alt"></i>
                        <span>Cashier &amp; POS Operations</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Multi-Channel Settlement &amp; Bank Reconciliation</h3>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-400/30 inline-flex items-center gap-1.5 self-start sm:self-auto">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Terminal Synced
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                        <i class="bx bx-money text-base text-emerald-600 dark:text-emerald-400"></i>
                        <span>Cash Float &amp; Drawer</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">Track opening float balances, physical till counts, and daily variance reports with automatic audit triggers.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                        <i class="bx bx-credit-card-front text-base text-indigo-600 dark:text-indigo-400"></i>
                        <span>EDC Card Batch Settlement</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">Capture card batch receipts, bank approval codes, and reconcile terminal settlement records against merchant accounts.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                        <i class="bx bx-qr text-base text-purple-600 dark:text-purple-400"></i>
                        <span>DuitNow QR &amp; Panel Guarantees</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">Instant DuitNow QR reference verification and corporate panel guarantee letter (GL) co-payment tracking.</p>
                </div>
            </div>
        </div>

        <!-- Governance & Security Section -->
        <div id="governance" class="mt-12 p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-indigo-800/40 shadow-2xl text-white space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-indigo-800/50 pb-5">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-indigo-300 text-xs font-bold uppercase tracking-wider">
                        <i class="bx bx-shield-quarter"></i>
                        <span>Clinical Security &amp; Compliance</span>
                    </div>
                    <h3 class="text-xl font-bold text-white">Central Identity &amp; Immutable Audit Trail</h3>
                </div>
                <div class="text-xs text-indigo-300 font-mono">
                    CentraFlow SSO (:8004) &bull; Enterprise RBAC
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
                    <div class="font-bold text-white mb-1">CentraFlow SSO</div>
                    <p class="text-slate-300">Centralized OAuth 2.0 single sign-on across HRMS, Payroll, and Invoicing portals.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
                    <div class="font-bold text-white mb-1">Strict Role Isolation</div>
                    <p class="text-slate-300">Doctors, cashiers, and administrators operate with compartmentalized access privileges.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
                    <div class="font-bold text-white mb-1">Void Governance</div>
                    <p class="text-slate-300">Mandatory supervisor reason code recording for every invoice cancellation or item removal.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
                    <div class="font-bold text-white mb-1">Immutable Log Vault</div>
                    <p class="text-slate-300">Tamper-evident logs recording IP addresses, session IDs, and timestamped payloads.</p>
                </div>
            </div>
        </div>
    </section>

</x-layouts.public>
