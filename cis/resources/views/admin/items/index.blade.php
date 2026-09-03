<x-layouts.admin title="Item Master Catalog">

    <div class="space-y-6">

        <!-- Page Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-5 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                            <i class="bx bx-capsule text-xl"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Treatment &amp; Item Master Catalog</h1>
                    </div>
                    <p class="text-xs text-slate-300">FR-2.1 &bull; Manage standard clinical consultation rates, procedure fees, diagnostics, and pharmaceutical drug inventory</p>
                </div>

                <div class="flex items-center gap-3">
                    <x-button href="{{ route('admin.items.create') }}" variant="success" size="md" icon="bx bx-plus-circle" class="shadow-lg shadow-emerald-600/30">
                        Add Catalog Item
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
            </x-alert>
        @endif
        @if(session('error'))
            <x-alert type="danger" dismissible="true">
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- Quick Summary Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card
                title="Master Catalog Items"
                value="{{ $stats['total_items'] }}"
                icon="bx bx-category"
                color="indigo"
                subtitle="All active clinical SKUs"
            />
            <x-stat-card
                title="Pharmacy Medications"
                value="{{ $stats['medications'] }}"
                icon="bx bx-capsule"
                color="emerald"
                subtitle="Pharmaceutical drugs in inventory"
            />
            <x-stat-card
                title="Medical Procedures"
                value="{{ $stats['procedures'] }}"
                icon="bx bx-pulse"
                color="purple"
                subtitle="Clinical treatment services"
            />
            <x-stat-card
                title="Low Stock Warning"
                value="{{ $stats['low_stock'] }}"
                icon="bx bx-error"
                color="amber"
                subtitle="Medicines under 50 units"
            />
        </div>

        <!-- Reusable Filter & Search Toolbar -->
        <x-filter-toolbar
            title="Search &amp; Filter Clinical Catalog"
            subtitle="Search catalog items by SKU code or treatment description, and filter by clinical category"
            :action="route('admin.items.index')"
            searchPlaceholder="Search catalog items by name or SKU code (e.g. MED-042, Consultation)..."
            :searchValue="request('search')"
            :resetUrl="route('admin.items.index')"
            :defaultOpen="true"
        >
            <x-slot:filters>
                <x-select label="Item Category" name="category">
                    <option value="all" {{ request('category') === 'all' ? 'selected' : '' }}>All Categories</option>
                    <option value="Consultation" {{ request('category') === 'Consultation' ? 'selected' : '' }}>Consultation</option>
                    <option value="Procedure" {{ request('category') === 'Procedure' ? 'selected' : '' }}>Procedures</option>
                    <option value="Medication" {{ request('category') === 'Medication' ? 'selected' : '' }}>Medications</option>
                    <option value="Lab" {{ request('category') === 'Lab' ? 'selected' : '' }}>Lab Tests</option>
                </x-select>
            </x-slot:filters>
        </x-filter-toolbar>

        <!-- 1. Mobile-Optimized Catalog Card List (Visible on phones < md) -->
        <div class="grid grid-cols-1 gap-3.5 md:hidden">
            @forelse($items as $item)
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-xs space-y-3">
                    
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $item->code }}
                            </div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white mt-0.5">
                                {{ $item->name }}
                            </h3>
                        </div>

                        @if($item->category === 'Consultation')
                            <x-badge variant="indigo" size="sm">{{ $item->category }}</x-badge>
                        @elseif($item->category === 'Medication')
                            <x-badge variant="emerald" size="sm">{{ $item->category }}</x-badge>
                        @elseif($item->category === 'Procedure')
                            <x-badge variant="purple" size="sm">{{ $item->category }}</x-badge>
                        @else
                            <x-badge variant="sky" size="sm">{{ $item->category }}</x-badge>
                        @endif
                    </div>

                    <!-- Stock & Price Footer -->
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Unit Rate</div>
                            <div class="font-mono text-xs font-bold text-slate-900 dark:text-white">
                                RM {{ number_format($item->unit_price, 2) }}
                                @if($item->category === 'Medication')
                                    <span class="text-[11px] font-normal {{ $item->stock_quantity < 50 ? 'text-amber-500' : 'text-slate-400' }}">
                                        &bull; {{ $item->stock_quantity }} in stock
                                    </span>
                                @endif
                            </div>
                        </div>

                        <x-button href="{{ route('admin.items.edit', $item->id) }}" variant="outline" size="sm" icon="bx bx-edit">
                            Edit
                        </x-button>
                    </div>

                </div>
            @empty
                <div class="p-8 text-center rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-400 text-xs">
                    <i class="bx bx-capsule text-3xl mb-2 text-slate-400"></i>
                    <p>No catalog items found matching your filters.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. Desktop High-Density Table Card (Visible on md and up) -->
        <x-card noPadding="true" class="hidden md:block">
            <x-table :headers="[
                'Code / SKU',
                'Item Description',
                'Category',
                ['label' => 'Unit Price', 'align' => 'right'],
                ['label' => 'Tax Rate', 'align' => 'center'],
                ['label' => 'Stock Qty', 'align' => 'center'],
                ['label' => 'Actions', 'align' => 'right'],
            ]">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-5 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $item->code }}
                        </td>

                        <td class="py-3.5 px-5 font-bold text-slate-900 dark:text-white">
                            {{ $item->name }}
                        </td>

                        <td class="py-3.5 px-5">
                            @if($item->category === 'Consultation')
                                <x-badge variant="indigo" size="sm">{{ $item->category }}</x-badge>
                            @elseif($item->category === 'Medication')
                                <x-badge variant="emerald" size="sm">{{ $item->category }}</x-badge>
                            @elseif($item->category === 'Procedure')
                                <x-badge variant="purple" size="sm">{{ $item->category }}</x-badge>
                            @else
                                <x-badge variant="sky" size="sm">{{ $item->category }}</x-badge>
                            @endif
                        </td>

                        <td class="py-3.5 px-5 text-right font-mono font-bold text-slate-900 dark:text-white">
                            RM {{ number_format($item->unit_price, 2) }}
                        </td>

                        <td class="py-3.5 px-5 text-center font-mono text-xs text-slate-500 dark:text-slate-400">
                            {{ $item->tax_rate > 0 ? $item->tax_rate . '%' : '0%' }}
                        </td>

                        <td class="py-3.5 px-5 text-center font-mono text-xs">
                            @if($item->category === 'Medication')
                                <span class="font-bold {{ $item->stock_quantity < 50 ? 'text-amber-500' : 'text-slate-700 dark:text-slate-300' }}">
                                    {{ $item->stock_quantity }} units
                                </span>
                            @else
                                <span class="text-slate-400">N/A (Service)</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-5 text-right space-x-2">
                            <x-button href="{{ route('admin.items.edit', $item->id) }}" variant="ghost" size="sm">
                                Edit
                            </x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                            <i class="bx bx-capsule text-3xl mb-2 text-slate-400"></i>
                            <p>No catalog items found matching your filters.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>

            <!-- Reusable Pagination & Per-Page Limit -->
            <x-pagination :paginator="$items" :perPageOptions="[12, 24, 48, 96]" />
        </x-card>

        <!-- Mobile Pagination -->
        <div class="md:hidden">
            <x-pagination :paginator="$items" :perPageOptions="[12, 24, 48, 96]" class="rounded-2xl border" />
        </div>

    </div>

</x-layouts.admin>
