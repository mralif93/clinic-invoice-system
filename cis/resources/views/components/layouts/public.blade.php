@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Clinic Invoice System') }}</title>

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
        .glow-radial {
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.18) 0%, rgba(30, 27, 75, 0.04) 50%, transparent 80%);
        }
        .dark .glow-radial {
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.22) 0%, rgba(15, 23, 42, 0.05) 50%, transparent 80%);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-indigo-600 selection:text-white min-h-screen flex flex-col justify-between relative overflow-x-hidden transition-colors duration-300">

    <!-- Background Decorative Glow -->
    <div class="absolute inset-0 glow-radial pointer-events-none -z-10"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[400px] bg-gradient-to-tr from-indigo-500/15 via-purple-500/10 to-indigo-700/15 blur-[130px] rounded-full pointer-events-none -z-10"></div>

    <!-- Navigation Header -->
    <header class="w-full border-b border-slate-200 dark:border-indigo-950/80 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md sticky top-0 z-50 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
                <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 border border-indigo-400/30 group-hover:scale-105 transition-transform">
                    <i class="bx bx-receipt text-2xl font-bold"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-slate-900 dark:from-white via-indigo-950 dark:via-indigo-100 to-indigo-600 dark:to-indigo-300 bg-clip-text text-transparent">
                        Clinic<span class="text-indigo-600 dark:text-indigo-400">Flow</span>
                    </span>
                    <span class="hidden sm:inline-block ml-2 px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 rounded-full border border-indigo-200 dark:border-indigo-400/30">
                        Invoice &amp; Billing
                    </span>
                </div>
            </a>

            <!-- Header Quick Actions -->
            <div class="flex items-center gap-3 sm:gap-4">
                
                <!-- Theme Toggle Button -->
                <x-theme-toggle />

                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 border border-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        <i class="bx bxs-dashboard text-lg"></i>
                        <span>Admin Portal</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 border border-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        <i class="bx bx-shield-quarter text-lg"></i>
                        <span>Staff Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 dark:border-indigo-950 bg-white/80 dark:bg-slate-900/90 py-8 text-xs text-slate-500 dark:text-slate-400 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-indigo-600 flex items-center justify-center text-white text-sm">
                    <i class="bx bx-plus-medical"></i>
                </div>
                <span class="font-semibold text-slate-900 dark:text-white">Clinic Invoice System (CIS)</span>
                <span>&bull;</span>
                <span>Medical Billing &amp; Invoicing Platform</span>
            </div>
            <div class="text-slate-400 dark:text-slate-500 font-mono">
                &copy; {{ date('Y') }} CIS. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
