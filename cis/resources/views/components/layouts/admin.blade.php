@props([
    'title' => null,
    'hideHeader' => false,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' : '' }}CIS Admin Portal</title>

    <!-- Theme Initialization to avoid FOUC -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Google Fonts & Boxicons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <script>
            tailwind.config = {
                darkMode: 'class',
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6366f1; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-indigo-600 selection:text-white h-full flex overflow-hidden transition-colors duration-300">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div
        id="sidebar-backdrop"
        onclick="toggleMobileSidebar()"
        class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden transition-opacity"
    ></div>

    <!-- Sidebar Navigation -->
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800/90 flex flex-col shrink-0 select-none transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static"
    >
        <!-- Sidebar Brand Header -->
        <div class="h-16 px-5 border-b border-slate-200 dark:border-slate-800/80 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white shadow-md shadow-indigo-600/30 border border-indigo-400/30 group-hover:scale-105 transition-transform">
                    <i class="bx bx-receipt text-xl font-bold"></i>
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 dark:text-white text-base tracking-tight">
                        Clinic<span class="text-indigo-600 dark:text-indigo-400">Flow</span>
                    </span>
                    <span class="block text-[9px] uppercase font-bold tracking-wider text-slate-400">
                        Admin Portal
                    </span>
                </div>
            </a>

            <!-- Mobile Close Sidebar Button -->
            <button
                type="button"
                onclick="toggleMobileSidebar()"
                class="lg:hidden p-1.5 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition"
            >
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>

        <!-- Navigation Menu List -->
        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 custom-scrollbar text-xs">
            
            <!-- Group 1: Core Operations -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                    Core Operations
                </div>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bxs-dashboard text-lg"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Group 2: Billing & Invoicing (SRS Module 3 & 4) -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                    Billing &amp; Invoices
                </div>
                <a href="{{ route('admin.invoices.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.invoices.create') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-file-blank text-lg text-emerald-600 dark:text-emerald-400"></i>
                    <span>New Invoice</span>
                    <span class="ml-auto px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-400/30">POS</span>
                </a>
                <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.invoices.index') || request()->routeIs('admin.invoices.show') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-receipt text-lg"></i>
                    <span>Invoices Directory</span>
                </a>
            </div>

            <!-- Group 3: Clinical Master Data (SRS Module 1 & 2) -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                    Clinical Master Data
                </div>
                <a href="{{ route('admin.patients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.patients.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-user-pin text-lg"></i>
                    <span>Patients Master</span>
                </a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('admin.items.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.items.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="bx bx-capsule text-lg"></i>
                        <span>Item &amp; Drug Master</span>
                    </a>
                @endif
            </div>

            <!-- Group 4: Accounting & Drawer (SRS Module 5) -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                    Reconciliation &amp; Reports
                </div>
                <a href="{{ route('admin.reports.cash-drawer') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.reports.cash-drawer') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-coin-stack text-lg"></i>
                    <span>Daily Cash Drawer</span>
                </a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('admin.reports.bank-recon') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.reports.bank-recon') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="bx bx-building-house text-lg"></i>
                        <span>Bank Reconciliation</span>
                    </a>
                    <a href="{{ route('admin.reports.analytics') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.reports.analytics') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="bx bx-bar-chart-square text-lg"></i>
                        <span>Revenue Analytics</span>
                    </a>
                @endif
            </div>

            <!-- Group 5: Governance & Clinic Profile (SRS Module 6 & 7) - Admin Only -->
            @if(auth()->user()?->isAdmin())
                <div class="space-y-1">
                    <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                        Governance &amp; Settings
                    </div>
                    <a href="{{ route('admin.settings.audit-logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.settings.audit-logs') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="bx bx-history text-lg text-amber-500 dark:text-amber-400"></i>
                        <span>Audit Trail Logs</span>
                    </a>
                    <a href="{{ route('admin.settings.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.settings.profile') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="bx bx-clinic text-lg"></i>
                        <span>Clinic Profile</span>
                    </a>
                    <a href="{{ route('admin.settings.templates') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('admin.settings.templates') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="bx bx-slider-alt text-lg"></i>
                        <span>Invoice Templates</span>
                    </a>
                </div>
            @endif

        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors">
        
        <!-- Top App Bar -->
        <header class="h-16 px-4 sm:px-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800/80 flex items-center justify-between shrink-0 z-30 transition-colors">
            
            <!-- Left: Mobile Menu Trigger & Station Status -->
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    onclick="toggleMobileSidebar()"
                    class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                    title="Toggle Menu"
                >
                    <i class="bx bx-menu text-2xl"></i>
                </button>

                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shadow-sm shadow-emerald-500/50"></span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200 hidden sm:inline">Station Active</span>
                    <span class="hidden sm:inline">&bull;</span>
                    <span class="font-mono text-slate-500 dark:text-slate-400">{{ now()->format('d M Y') }}</span>
                </div>
            </div>

            <!-- Right: Theme Toggle, Public View & User Dropdown Menu -->
            <div class="flex items-center gap-2 sm:gap-4">
                
                <!-- Theme Toggle Button -->
                <x-theme-toggle />

                <!-- System Guide / Role Workflow Modal Trigger -->
                <button
                    type="button"
                    onclick="document.getElementById('modal-system-workflows').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-200/80 dark:border-indigo-800/80 transition cursor-pointer shadow-xs"
                    title="Open System Workflows & Role Documentation"
                >
                    <i class="bx bx-help-circle text-base"></i>
                    <span class="hidden sm:inline">Role Workflows</span>
                </button>

                <div class="h-5 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                <!-- User Dropdown Menu -->
                <div class="relative" id="user-menu-container">
                    <button
                        type="button"
                        id="user-menu-button"
                        onclick="document.getElementById('user-dropdown').classList.toggle('hidden')"
                        class="flex items-center gap-2.5 p-1.5 sm:px-2.5 sm:py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 hover:bg-slate-200 dark:hover:bg-slate-700/80 border border-slate-200 dark:border-slate-700/80 transition focus:outline-none cursor-pointer"
                    >
                        <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                        </div>
                        <div class="text-left hidden md:block">
                            <div class="text-xs font-bold text-slate-800 dark:text-white leading-tight">
                                {{ auth()->user()->name ?? 'Dr. Aiman' }}
                            </div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono flex items-center gap-1">
                                <span>{{ auth()->user()->staff_id ?? 'ADM-001' }}</span>
                                <span>&bull;</span>
                                <span class="capitalize text-indigo-600 dark:text-indigo-400 font-semibold">{{ auth()->user()->role ?? 'Admin' }}</span>
                            </div>
                        </div>
                        <i class="bx bx-chevron-down text-slate-400 text-base"></i>
                    </button>

                    <!-- Dropdown Modal Popup -->
                    <div
                        id="user-dropdown"
                        class="hidden absolute right-0 mt-2 w-60 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl shadow-slate-400/20 dark:shadow-black/60 py-2 z-50 animate__animated animate__fadeIn animate__faster text-xs"
                    >
                        <!-- User Card Info Header -->
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800">
                            <p class="font-bold text-slate-900 dark:text-white text-xs truncate">{{ auth()->user()->name ?? 'Dr. Aiman' }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-mono truncate">{{ auth()->user()->email ?? 'admin@clinic.my' }}</p>
                            <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ auth()->user()?->isAdmin() ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-400/30' : 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-400/30' }}">
                                <i class="bx {{ auth()->user()?->isAdmin() ? 'bx-shield-alt2' : 'bx-user-check' }}"></i>
                                <span>{{ auth()->user()->role ?? 'Admin' }} Station</span>
                            </div>
                        </div>

                        <!-- Dropdown Actions -->
                        <div class="py-1.5 px-2 space-y-0.5">
                            @if(auth()->user()?->isAdmin())
                                <a href="{{ route('admin.settings.profile') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-indigo-600 dark:hover:text-white transition">
                                    <i class="bx bx-clinic text-base text-slate-400"></i>
                                    <span>Clinic Settings</span>
                                </a>
                                <a href="{{ route('admin.settings.audit-logs') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-indigo-600 dark:hover:text-white transition">
                                    <i class="bx bx-history text-base text-slate-400"></i>
                                    <span>Activity Audit</span>
                                </a>
                            @else
                                <a href="{{ route('admin.reports.cash-drawer') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-indigo-600 dark:hover:text-white transition">
                                    <i class="bx bx-coin-stack text-base text-slate-400"></i>
                                    <span>My Cash Drawer</span>
                                </a>
                            @endif
                            <button
                                type="button"
                                onclick="document.getElementById('user-dropdown').classList.add('hidden'); document.getElementById('modal-system-workflows').classList.remove('hidden');"
                                class="w-full text-left flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-indigo-600 dark:hover:text-white transition cursor-pointer"
                            >
                                <i class="bx bx-book-open text-base text-slate-400"></i>
                                <span>Role Workflows Guide</span>
                            </button>
                        </div>

                        <!-- Logout Form -->
                        <div class="pt-1.5 px-2 border-t border-slate-100 dark:border-slate-800">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 font-semibold transition cursor-pointer">
                                    <i class="bx bx-log-out text-base"></i>
                                    <span>Sign Out Station</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- Main Body Scroll Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 custom-scrollbar">
            {{ $slot }}
        </main>
    </div>

    <!-- Reusable System Workflows Modal Component for All Roles -->
    <x-workflow-modal />

    <!-- Interactive Mobile Drawer & Dropdown Script -->
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar && backdrop) {
                const isHidden = sidebar.classList.contains('-translate-x-full');
                if (isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }
        }

        document.addEventListener('click', function(e) {
            const container = document.getElementById('user-menu-container');
            const dropdown = document.getElementById('user-dropdown');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
