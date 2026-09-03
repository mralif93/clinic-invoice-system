@props([
    'name',
    'title' => null,
    'size' => 'md', // sm, md, lg, xl, 2xl
])

@php
    $sizeClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '4xl' => 'max-w-4xl',
    ][$size] ?? 'max-w-md';
@endphp

<div
    id="modal-{{ $name }}"
    class="hidden fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title-{{ $name }}"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop Blur & Dark Dim -->
    <div
        class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"
        onclick="document.getElementById('modal-{{ $name }}').classList.add('hidden')"
    ></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-left shadow-2xl transition-all sm:my-8 w-full {{ $sizeClasses }} p-6 sm:p-7">
            
            @if($title || isset($header))
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 mb-5">
                    @if(isset($header))
                        {{ $header }}
                    @else
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white" id="modal-title-{{ $name }}">
                            {{ $title }}
                        </h3>
                    @endif

                    <button
                        type="button"
                        onclick="document.getElementById('modal-{{ $name }}').classList.add('hidden')"
                        class="p-1 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                    >
                        <i class="bx bx-x text-xl"></i>
                    </button>
                </div>
            @endif

            <div class="space-y-4">
                {{ $slot }}
            </div>

            @if(isset($footer))
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
