@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.16) 0%, rgba(30, 27, 75, 0.04) 50%, transparent 80%);
        }
        .dark .glow-radial {
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.22) 0%, rgba(15, 23, 42, 0.05) 50%, transparent 80%);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-indigo-600 selection:text-white min-h-screen flex flex-col justify-between relative overflow-x-hidden transition-colors duration-300">

    <!-- Background Decorative Glow -->
    <div class="absolute inset-0 glow-radial pointer-events-none -z-10"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[350px] bg-gradient-to-tr from-indigo-500/15 via-purple-500/10 to-indigo-700/15 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <!-- Header / Brand Link -->
    <header class="w-full py-6 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 border border-indigo-400/30 group-hover:scale-105 transition-transform">
                    <i class="bx bx-receipt text-xl font-bold"></i>
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">
                        Clinic<span class="text-indigo-600 dark:text-indigo-400">Flow</span>
                    </span>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 dark:text-slate-400">
                        Invoicing &amp; Billing Portal
                    </span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <x-theme-toggle />
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors">
                    <i class="bx bx-arrow-back text-base"></i>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Slot Container -->
    <main class="flex-grow flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 border-t border-slate-200 dark:border-slate-800/80 text-center text-xs text-slate-500 dark:text-slate-500 font-mono transition-colors">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} Clinic Invoice System. All rights reserved. &bull; Enterprise RBAC Protected
        </div>
    </footer>

</body>
</html>
