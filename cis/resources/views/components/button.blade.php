@props([
    'variant' => 'primary', // primary, secondary, success, danger, warning, ghost, outline
    'size' => 'md',         // sm, md, lg
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold rounded-xl transition-all transform active:scale-[0.98] cursor-pointer gap-2 focus:outline-none';

    $variants = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 border border-indigo-500/30',
        'secondary' => 'bg-slate-800/90 text-slate-200 hover:text-white hover:bg-slate-700/90 border border-slate-700 shadow-md',
        'success' => 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 border border-emerald-500/30',
        'danger' => 'bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-600/30 border border-rose-500/30',
        'warning' => 'bg-amber-600 hover:bg-amber-500 text-white shadow-lg shadow-amber-600/30 border border-amber-500/30',
        'outline' => 'bg-transparent text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white border border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600 hover:bg-slate-100/50 dark:hover:bg-slate-800/50',
        'ghost' => 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/80',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-xs sm:text-sm',
        'lg' => 'px-6 py-3.5 text-sm sm:text-base',
    ];

    $classes = "{$baseClasses} " . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <i class="{{ $icon }} text-base"></i>
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <i class="{{ $icon }} text-base"></i>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
