@php
    $user = auth()->user();
    $isAdmin = $user?->isAdmin() ?? false;
    $isCashier = $user ? !$isAdmin : false;
@endphp

<!-- Interactive Workflow Modal strictly isolated to the authenticated role session -->
<x-modal name="system-workflows" title="{{ $isAdmin ? 'Administrator & Lead Doctor Operating Workflow' : 'Front-Desk Cashier & Nurse Operating Workflow' }}" size="4xl">
    
    <div class="space-y-6 text-xs text-slate-700 dark:text-slate-300 select-text font-sans">

        <!-- Role Session Context Banner -->
        <div class="p-4 rounded-2xl {{ $isAdmin ? 'bg-indigo-50/80 dark:bg-indigo-950/40 border border-indigo-200/80 dark:border-indigo-900/50' : 'bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-900/50' }} flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl {{ $isAdmin ? 'bg-indigo-600 text-white shadow-indigo-600/30' : 'bg-emerald-600 text-white shadow-emerald-600/30' }} flex items-center justify-center font-bold text-base shadow-md">
                    <i class="bx {{ $isAdmin ? 'bx-shield-quarter' : 'bx-receipt' }}"></i>
                </div>
                <div>
                    <div class="font-black text-slate-900 dark:text-white text-sm">
                        {{ $isAdmin ? 'Administrator & Governance Station' : 'Front-Desk Cashier & POS Station' }}
                    </div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                        Logged in as: <strong class="text-slate-800 dark:text-slate-200">{{ $user?->name ?? 'Clinical Staff' }}</strong> 
                        &bull; Staff ID: <span class="font-mono">{{ $user?->staff_id ?? 'STF-001' }}</span>
                        &bull; Clearance: <span class="uppercase font-bold {{ $isAdmin ? 'text-indigo-600 dark:text-indigo-400' : 'text-emerald-600 dark:text-emerald-400' }}">{{ $user?->role ?? 'Staff' }}</span>
                    </div>
                </div>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $isAdmin ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $isAdmin ? 'bg-indigo-500' : 'bg-emerald-500' }} animate-ping"></span>
                <span>Active Shift</span>
            </span>
        </div>

        @if($isCashier)
            <!-- ========================================== -->
            <!-- 💳 CASHIER ONLY WORKFLOW (STEP-BY-STEP)    -->
            <!-- ========================================== -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                            <i class="bx bx-check-circle text-emerald-500 text-base"></i>
                            <span>Front-Desk Shift Standard Operating Procedure (SOP)</span>
                        </h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Step-by-step cashier checklist from patient arrival to daily balancing</p>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg border border-emerald-200/60 dark:border-emerald-800/60">
                        6 Operational Steps
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <!-- Step 1 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-2">
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                            <div class="w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center font-mono text-xs">1</div>
                            <span>Shift Sign-in &amp; Terminal Verification</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Sign into your cashier terminal. Verify that your daily cash float is ready in the drawer before attending to queued patients.
                        </p>
                        <div class="text-[10px] font-mono text-slate-400 bg-slate-50 dark:bg-slate-800/60 p-2 rounded-xl">
                            Target: Verify counter cash float &amp; active receipt roll
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-2">
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                            <div class="w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center font-mono text-xs">2</div>
                            <span>Patient Registration &amp; Allergy Tagging</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            For new walk-ins, open <strong>Patients Master</strong> and record NRIC, phone, and <strong>critical drug allergies</strong> (e.g. Penicillin, Aspirin).
                        </p>
                        <div class="text-[10px] font-mono text-rose-500 bg-rose-50 dark:bg-rose-950/40 p-2 rounded-xl border border-rose-200 dark:border-rose-900/40">
                            Safety Alert: Allergy flags display in bright red banners
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-2">
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                            <div class="w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center font-mono text-xs">3</div>
                            <span>POS Fast Invoicing</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Go to <strong>New Invoice (POS)</strong>. Select patient and attending doctor via instant search modal. Use fast shortcut pills for 1-click line item entry.
                        </p>
                        <div class="text-[10px] font-mono text-slate-400 bg-slate-50 dark:bg-slate-800/60 p-2 rounded-xl">
                            Shortcut: Click pills for Consultation, Panadol &amp; Wound Dressing
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-2">
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                            <div class="w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center font-mono text-xs">4</div>
                            <span>Multi-Channel Payment Tender</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Accept <strong>Cash</strong>, <strong>EDC Card</strong>, <strong>DuitNow QR</strong>, or <strong>Panel/GL</strong>. Click <strong>[Exact Amount]</strong> to match total. Review pre-submit modal.
                        </p>
                        <div class="text-[10px] font-mono text-slate-400 bg-slate-50 dark:bg-slate-800/60 p-2 rounded-xl">
                            Tip: Record Card RRN / Batch # for reconciliation
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-2">
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                            <div class="w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center font-mono text-xs">5</div>
                            <span>Modal Popup Preview &amp; Print Slip</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Click <strong>80mm Thermal Receipt</strong> or <strong>A4 Formal Invoice</strong>. Verify on the pop-up modal preview before dispatching to physical printer.
                        </p>
                        <div class="text-[10px] font-mono text-indigo-500 bg-indigo-50 dark:bg-indigo-950/40 p-2 rounded-xl">
                            Rule: Every document must be previewed before printing
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-2">
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                            <div class="w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center font-mono text-xs">6</div>
                            <span>Shift End Cash Drawer Balancing</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Open <strong>Daily Cash Drawer</strong>. Preview and print the shift reconciliation summary with sign-off signature lines, balance drawer cash, and handover to supervisor.
                        </p>
                        <div class="text-[10px] font-mono text-slate-400 bg-slate-50 dark:bg-slate-800/60 p-2 rounded-xl">
                            Handover: Sign printed daily settlement reconciliation
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- ========================================== -->
            <!-- 👑 ADMINISTRATOR ONLY WORKFLOW             -->
            <!-- ========================================== -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                            <i class="bx bx-shield-alt2 text-indigo-500 text-base"></i>
                            <span>Clinical Governance &amp; Financial Supervision SOP</span>
                        </h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Executive supervisory workflows exclusively accessible to clinic administrators &amp; doctors</p>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-2.5 py-1 rounded-lg border border-indigo-200/60 dark:border-indigo-800/60">
                        4 Supervisory Domains
                    </span>
                </div>

                <div class="space-y-3.5">
                    <!-- Section 1 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-mono font-bold shrink-0 text-sm">1</div>
                        <div class="space-y-1">
                            <div class="font-bold text-slate-900 dark:text-white">Multi-Period Revenue Velocity &amp; Channel Share Tracking</div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                On the Executive Dashboard, toggle the period tabs between <strong>Today</strong>, <strong>Weekly</strong>, and <strong>Monthly</strong> to track clinical gross billings, realized collections, and payment channel market shares (Cash vs EDC Terminal vs DuitNow QR Pay).
                            </p>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-mono font-bold shrink-0 text-sm">2</div>
                        <div class="space-y-1">
                            <div class="font-bold text-slate-900 dark:text-white">Clinical Master Catalog &amp; Pharmaceutical Stock Maintenance</div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                Under <strong>Clinical Master Data > Item &amp; Drug Master</strong>, configure consultation tiers, minor procedure rates, laboratory test packages, and monitor low-stock pharmaceutical inventory (&lt; 50 units alert).
                            </p>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-mono font-bold shrink-0 text-sm">3</div>
                        <div class="space-y-1">
                            <div class="font-bold text-slate-900 dark:text-white">EDC Terminal &amp; Bank Account Reconciliation</div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                Under <strong>Bank Reconciliation</strong>, match electronic payments against merchant bank statements (Maybank, CIMB, RHB). Toggle the reconciliation switch per transaction to ensure total balance between counter terminal batch slips and banking records.
                            </p>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-mono font-bold shrink-0 text-sm">4</div>
                        <div class="space-y-1">
                            <div class="font-bold text-slate-900 dark:text-white">Authorized Invoice Voids &amp; Tamper-Proof Audit Inspection</div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                Cashiers cannot void invoices. In exceptional circumstances (e.g. duplicate queue, patient left prior to consultation), administrators authorize voids with mandatory written justification. All actions are indelibly recorded in the <strong>Audit Trail Logs</strong> with IP tracking.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <x-slot:footer>
        <button
            type="button"
            onclick="document.getElementById('modal-system-workflows').classList.add('hidden')"
            class="px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white font-bold text-xs transition cursor-pointer"
        >
            Close Guide
        </button>
    </x-slot:footer>
</x-modal>
