@props([
    'type' => 'info', // success, danger, warning, info
    'title' => null,
    'dismissible' => false,
])

@php
    $typeConfig = [
        'success' => [
            'card' => 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300',
            'icon' => 'bx bx-check-circle text-emerald-600 dark:text-emerald-400',
        ],
        'danger' => [
            'card' => 'bg-rose-50 dark:bg-rose-500/10 border-rose-200 dark:border-rose-500/20 text-rose-800 dark:text-rose-300',
            'icon' => 'bx bx-error-circle text-rose-600 dark:text-rose-400',
        ],
        'warning' => [
            'card' => 'bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20 text-amber-800 dark:text-amber-300',
            'icon' => 'bx bx-error text-amber-600 dark:text-amber-400',
        ],
        'info' => [
            'card' => 'bg-indigo-50 dark:bg-indigo-500/10 border-indigo-200 dark:border-indigo-500/20 text-indigo-800 dark:text-indigo-300',
            'icon' => 'bx bx-info-circle text-indigo-600 dark:text-indigo-400',
        ],
    ];

    $cfg = $typeConfig[$type] ?? $typeConfig['info'];
@endphp

<div {{ $attributes->merge(['class' => 'p-4 rounded-2xl border text-xs flex items-start gap-3 relative transition-colors ' . $cfg['card']]) }}>
    <i class="{{ $cfg['icon'] }} text-lg shrink-0 mt-0.5"></i>
    
    <div class="flex-1 space-y-0.5">
        @if($title)
            <div class="font-bold text-sm leading-tight">{{ $title }}</div>
        @endif
        <div class="leading-relaxed">
            {{ $slot }}
        </div>
    </div>

    @if($dismissible)
        <button
            type="button"
            onclick="this.parentElement.remove()"
            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 -mr-1 -mt-1 rounded-lg transition"
            title="Dismiss"
        >
            <i class="bx bx-x text-base"></i>
        </button>
    @endif
</div>
