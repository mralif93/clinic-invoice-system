@props([
    'title' => 'Search & Filter Records',
    'subtitle' => 'Filter directory listings by keywords, categories, and date ranges',
    'action' => '',
    'method' => 'GET',
    'searchPlaceholder' => 'Search records by keyword, name, or code...',
    'searchValue' => '',
    'searchName' => 'search',
    'resetUrl' => null,
    'id' => 'filter-section-' . uniqid(),
    'defaultOpen' => true,
])

@php
    $hasActiveFilters = request()->hasAny(['search', 'status', 'category', 'date_from', 'date_to', 'payment_method', 'doctor', 'patient_id']) || request()->filled($searchName);
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-xs dark:shadow-md overflow-hidden transition-all']) }} id="{{ $id }}">
    
    <!-- Collapsible Header Section -->
    <div
        onclick="toggleFilterCollapse('{{ $id }}')"
        class="px-5 py-3.5 sm:px-6 sm:py-4 border-b border-slate-100 dark:border-slate-800/80 bg-slate-50/40 dark:bg-slate-900/40 flex items-center justify-between gap-4 cursor-pointer select-none group"
    >
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-200/80 dark:border-indigo-800/80 flex items-center justify-center text-base group-hover:scale-105 transition-transform">
                <i class="bx bx-filter-alt"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white tracking-tight">
                        {!! $title !!}
                    </h3>
                    @if($hasActiveFilters)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500 text-white shadow-xs">
                            Active
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 hidden sm:block font-normal">
                    {!! $subtitle !!}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-[11px] font-semibold text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition hidden sm:inline">
                <span id="{{ $id }}-label">{{ $defaultOpen ? 'Collapse' : 'Expand' }}</span>
            </span>
            <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-950 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">
                <i class="bx bx-chevron-up text-lg transition-transform duration-200 {{ $defaultOpen ? '' : 'rotate-180' }}" id="{{ $id }}-icon"></i>
            </div>
        </div>
    </div>

    <!-- Filter Form & Body (Collapsible Area) -->
    <form action="{{ $action }}" method="{{ $method }}" id="{{ $id }}-body" class="{{ $defaultOpen ? '' : 'hidden' }}">
        
        <div class="p-4 sm:p-5 space-y-4">
            <!-- Grid Layout for Search + Custom Slots -->
            <div class="grid grid-cols-1 {{ isset($filters) ? 'md:grid-cols-12' : '' }} gap-3.5 items-end">
                
                <!-- Main Search Input -->
                <div class="{{ isset($filters) ? 'md:col-span-7' : 'w-full' }} space-y-1.5">
                    <label for="{{ $id }}-search" class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                        Search Query
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="bx bx-search text-base"></i>
                        </div>
                        <input
                            type="text"
                            name="{{ $searchName }}"
                            id="{{ $id }}-search"
                            value="{{ $searchValue }}"
                            placeholder="{{ $searchPlaceholder }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-xs sm:text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all"
                        >
                    </div>
                </div>

                <!-- Custom Filter Dropdowns (e.g. Status, Category, Date) -->
                @if(isset($filters))
                    <div class="md:col-span-5 grid grid-cols-1 sm:grid-cols-2 gap-3 items-end">
                        {{ $filters }}
                    </div>
                @endif

            </div>
        </div>

        <!-- Filter Footer Action Toolbar -->
        <div class="px-5 py-3 sm:px-6 bg-slate-50/70 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800/80 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <i class="bx bx-info-circle text-indigo-500"></i>
                <span>Use keyword combinations for more refined results.</span>
            </div>

            <div class="flex items-center gap-2 justify-end">
                @if($resetUrl && $hasActiveFilters)
                    <a
                        href="{{ $resetUrl }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 transition cursor-pointer"
                        title="Reset all applied filters"
                    >
                        <i class="bx bx-reset text-base"></i>
                        <span>Reset Filters</span>
                    </a>
                @endif

                <x-button type="submit" variant="primary" size="sm" icon="bx bx-filter-alt" class="px-5 py-2 shadow-md shadow-indigo-600/30">
                    Apply Filter
                </x-button>
            </div>
        </div>

    </form>
</div>

<!-- Reusable Collapsible Script -->
<script>
    if (typeof window.toggleFilterCollapse !== 'function') {
        window.toggleFilterCollapse = function(containerId) {
            const body = document.getElementById(`${containerId}-body`);
            const icon = document.getElementById(`${containerId}-icon`);
            const label = document.getElementById(`${containerId}-label`);

            if (body) {
                const isHidden = body.classList.contains('hidden');
                if (isHidden) {
                    body.classList.remove('hidden');
                    if (icon) icon.classList.remove('rotate-180');
                    if (label) label.innerText = 'Collapse';
                } else {
                    body.classList.add('hidden');
                    if (icon) icon.classList.add('rotate-180');
                    if (label) label.innerText = 'Expand';
                }
            }
        };
    }
</script>
