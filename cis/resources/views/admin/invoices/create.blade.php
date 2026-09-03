<x-layouts.admin title="Create POS Invoice">

    <div class="space-y-6">

        <!-- Page Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-5 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <!-- Ambient Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <a href="{{ route('admin.invoices.index') }}" class="p-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition backdrop-blur-xs">
                            <i class="bx bx-arrow-back text-lg"></i>
                        </a>
                        <h1 class="text-lg sm:text-2xl font-black tracking-tight text-white">Front-Desk POS Invoice Generator</h1>
                        <x-badge variant="emerald" dot="true" size="sm">Real-time Calculation</x-badge>
                    </div>
                    <p class="text-xs text-slate-300">Select patient records, click quick item pills, apply discounts, and tender front-desk payment</p>
                </div>

                <div class="flex items-center sm:flex-col sm:items-end justify-between border-t sm:border-t-0 border-white/10 pt-3 sm:pt-0 font-mono">
                    <span class="text-[10px] text-indigo-300 uppercase tracking-widest font-semibold">Sequence ID</span>
                    <span class="text-base sm:text-lg font-black text-emerald-400">{{ $invoiceNumber }}</span>
                </div>
            </div>
        </div>

        <!-- Invoice Form -->
        <form action="{{ route('admin.invoices.store') }}" method="POST" id="invoice-form" class="space-y-6">
            @csrf

            <!-- Hidden inputs holding selected patient ID and Doctor Name -->
            <input type="hidden" name="patient_id" id="selected_patient_id" value="" required>
            <input type="hidden" name="doctor_name" id="selected_doctor_name" value="Dr. Aiman Hakim" required>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left Column: Patient, Doctor & Line Items Builder (Span 8) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- 1. Interactive Patient & Doctor Pickers Card -->
                    <x-card title="Patient & Attendant Details" subtitle="Select patient and attending physician via interactive modal pickers">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <!-- Patient Selection Trigger Widget -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    Patient Record <span class="text-rose-500">*</span>
                                </label>
                                
                                <button
                                    type="button"
                                    onclick="openPatientModal()"
                                    id="patient_picker_btn"
                                    class="w-full text-left p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-md transition-all flex items-center justify-between gap-3 group cursor-pointer"
                                >
                                    <div class="flex items-center gap-3 min-w-0" id="patient_display_container">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold shrink-0 border border-indigo-200 dark:border-indigo-800">
                                            <i class="bx bx-user-plus text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate" id="patient_selected_name">
                                                Click to Choose Patient
                                            </div>
                                            <div class="text-[10px] text-slate-400 font-mono truncate" id="patient_selected_sub">
                                                Search by Name, IC, or Phone
                                            </div>
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-bold shrink-0">
                                        Browse
                                    </span>
                                </button>
                            </div>

                            <!-- Doctor Selection Trigger Widget -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    Attending Physician / Doctor <span class="text-rose-500">*</span>
                                </label>
                                
                                <button
                                    type="button"
                                    onclick="openDoctorModal()"
                                    id="doctor_picker_btn"
                                    class="w-full text-left p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-md transition-all flex items-center justify-between gap-3 group cursor-pointer"
                                >
                                    <div class="flex items-center gap-3 min-w-0" id="doctor_display_container">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shrink-0 border border-emerald-200 dark:border-emerald-800">
                                            <i class="bx bx-plus-medical text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate" id="doctor_selected_name">
                                                Dr. Aiman Hakim
                                            </div>
                                            <div class="text-[10px] text-slate-400 font-mono truncate" id="doctor_selected_sub">
                                                Consulting Physician &bull; ADM-001
                                            </div>
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-bold shrink-0">
                                        Change
                                    </span>
                                </button>
                            </div>

                        </div>
                    </x-card>

                    <!-- 2. Dynamic Line Items Builder Card -->
                    <x-card title="Invoice Line Items" subtitle="Select medications, consultations, or diagnostic tests" noPadding="true">
                        <x-slot:action>
                            <button
                                type="button"
                                onclick="addLineItem()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition-all transform active:scale-95 cursor-pointer"
                            >
                                <i class="bx bx-plus text-base"></i>
                                <span>Add Custom Line</span>
                            </button>
                        </x-slot:action>

                        <!-- Quick Catalog Shortcut: Table List with Checkboxes (Mobile Responsive & Searchable) -->
                        <div class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
                            
                            <!-- Filter & Search Toolbar -->
                            <div class="p-3 sm:p-4 space-y-2.5 border-b border-slate-100 dark:border-slate-800">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                                    
                                    <!-- Category Filter Tabs -->
                                    <div class="flex items-center gap-1 overflow-x-auto pb-1 sm:pb-0 custom-scrollbar text-xs font-semibold text-slate-500 dark:text-slate-400">
                                        <button
                                            type="button"
                                            onclick="filterPresetCategory('all')"
                                            id="preset-cat-all"
                                            class="preset-cat-tab px-3 py-1.5 rounded-xl bg-indigo-600 text-white shadow-xs font-bold transition whitespace-nowrap cursor-pointer"
                                        >
                                            All ({{ $items->count() }})
                                        </button>
                                        @php
                                            $groupedItems = $items->groupBy('category');
                                            $categoryIcons = [
                                                'Consultation' => 'bx-pulse',
                                                'Medication' => 'bx-capsule',
                                                'Procedure' => 'bx-plus-medical',
                                                'Lab' => 'bx-test-tube',
                                            ];
                                        @endphp
                                        @foreach($groupedItems as $categoryName => $catItems)
                                            <button
                                                type="button"
                                                onclick="filterPresetCategory('{{ strtolower($categoryName) }}')"
                                                id="preset-cat-{{ strtolower($categoryName) }}"
                                                class="preset-cat-tab px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700/70 transition whitespace-nowrap cursor-pointer flex items-center gap-1.5"
                                            >
                                                <i class="bx {{ $categoryIcons[$categoryName] ?? 'bx-tag' }} text-sm text-indigo-500"></i>
                                                <span>{{ $categoryName }}</span>
                                                <span class="text-[10px] opacity-70 font-mono">({{ $catItems->count() }})</span>
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Instant Search & Selected Counter Badge -->
                                    <div class="flex items-center gap-2">
                                        <span id="catalog-selected-badge" class="hidden text-[11px] font-bold px-2 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shrink-0">
                                            <span id="catalog-selected-count">0</span> selected
                                        </span>
                                        <div class="relative w-full sm:w-56">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                                <i class="bx bx-search text-sm"></i>
                                            </div>
                                            <input
                                                type="text"
                                                id="preset_catalog_search"
                                                oninput="searchPresetItems()"
                                                placeholder="Search treatment / drug..."
                                                class="w-full pl-8 pr-7 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"
                                            >
                                            <button
                                                type="button"
                                                id="preset_clear_search_btn"
                                                onclick="clearPresetSearch()"
                                                class="hidden absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                            >
                                                <i class="bx bx-x text-base"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Scrollable Table List with Checkboxes (Expanded to ~10 visible rows) -->
                            <div class="max-h-[440px] sm:max-h-[480px] overflow-y-auto custom-scrollbar">
                                <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                                    <thead class="bg-slate-100/80 dark:bg-slate-850/80 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px] sticky top-0 z-10 backdrop-blur-xs border-b border-slate-200 dark:border-slate-800">
                                        <tr>
                                            <th class="py-2 px-3 w-10 text-center">
                                                <i class="bx bx-check-square text-base text-slate-400"></i>
                                            </th>
                                            <th class="py-2 px-3">Item / Service</th>
                                            <th class="py-2 px-3 hidden sm:table-cell w-28">Category</th>
                                            <th class="py-2 px-3 w-28 text-right">Unit Price</th>
                                            <th class="py-2 px-3 w-16 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="preset_items_table_body" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                        @foreach($items as $masterItem)
                                            @php
                                                $catSlug = strtolower($masterItem->category ?? 'other');
                                                $catBadge = match($catSlug) {
                                                    'consultation' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border-blue-200 dark:border-blue-900',
                                                    'medication' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900',
                                                    'procedure' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border-purple-200 dark:border-purple-900',
                                                    'lab' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border-amber-200 dark:border-amber-900',
                                                    default => 'bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700'
                                                };
                                                $iconCat = $categoryIcons[$masterItem->category] ?? 'bx-plus';
                                            @endphp
                                            <tr
                                                id="preset-row-{{ $masterItem->id }}"
                                                data-id="{{ $masterItem->id }}"
                                                data-name="{{ strtolower($masterItem->name) }}"
                                                data-code="{{ strtolower($masterItem->code) }}"
                                                data-category="{{ $catSlug }}"
                                                class="preset-item-row hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20 transition-colors cursor-pointer"
                                                onclick="handleRowCheckboxClick(event, '{{ $masterItem->id }}', '{{ addslashes($masterItem->name) }}', '{{ $masterItem->code }}', {{ $masterItem->unit_price }})"
                                            >
                                                <!-- Checkbox Column -->
                                                <td class="py-2.5 px-3 text-center align-middle w-10">
                                                    <div class="flex items-center justify-center">
                                                        <input
                                                            type="checkbox"
                                                            id="preset-checkbox-{{ $masterItem->id }}"
                                                            class="preset-catalog-checkbox w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 dark:focus:ring-offset-slate-900 cursor-pointer accent-indigo-600 m-0"
                                                            onclick="event.stopPropagation(); toggleCatalogItemCheckbox('{{ $masterItem->id }}', '{{ addslashes($masterItem->name) }}', '{{ $masterItem->code }}', {{ $masterItem->unit_price }})"
                                                        >
                                                    </div>
                                                </td>

                                                <!-- Description & Code Column -->
                                                <td class="py-2.5 px-3 align-middle">
                                                    <div class="flex items-start gap-2.5">
                                                        <div class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 mt-0.5 border border-indigo-100 dark:border-indigo-900/50">
                                                            <i class="bx {{ $iconCat }} text-sm"></i>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <div class="font-semibold text-slate-900 dark:text-white text-xs leading-tight truncate max-w-[200px] sm:max-w-md">
                                                                {{ $masterItem->name }}
                                                            </div>
                                                            <div class="text-[10px] font-mono text-slate-400 dark:text-slate-500 mt-0.5 leading-none">
                                                                {{ $masterItem->code }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Category Badge Column (Hidden on tiny screens) -->
                                                <td class="py-2.5 px-3 hidden sm:table-cell align-middle">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold border {{ $catBadge }}">
                                                        {{ $masterItem->category }}
                                                    </span>
                                                </td>

                                                <!-- Price Column -->
                                                <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900 dark:text-white align-middle">
                                                    RM {{ number_format($masterItem->unit_price, 2) }}
                                                </td>

                                                <!-- 1-Click Add Action Column -->
                                                <td class="py-2.5 px-3 text-center align-middle w-16">
                                                    <div class="flex items-center justify-center">
                                                        <button
                                                            type="button"
                                                            onclick="event.stopPropagation(); addPresetItem('{{ $masterItem->id }}', '{{ addslashes($masterItem->name) }}', '{{ $masterItem->code }}', {{ $masterItem->unit_price }})"
                                                            title="Add to invoice"
                                                            class="p-1 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-950 transition cursor-pointer"
                                                        >
                                                            <i class="bx bx-plus-circle text-lg"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Empty Search Notice -->
                            <div id="no_preset_items_found" class="hidden text-center py-6 text-slate-400 dark:text-slate-500 text-xs">
                                <i class="bx bx-search-alt text-xl block mb-1"></i>
                                <span>No catalog items match your search or filter.</span>
                            </div>

                        </div>

                        <!-- Active Billed Invoice Items Table -->
                        <div class="p-3 sm:px-4 bg-slate-50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="bx bx-receipt text-indigo-600 dark:text-indigo-400 text-base"></i>
                                <span class="font-extrabold text-slate-900 dark:text-white text-xs uppercase tracking-wider">
                                    Billed Line Items
                                </span>
                            </div>
                            <span class="text-[10px] text-slate-400">Review &amp; adjust quantities or discounts</span>
                        </div>

                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300 min-w-[550px]" id="items-table">
                                <thead class="bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200 dark:border-slate-800">
                                    <tr>
                                        <th class="py-3 px-4">Description / Item</th>
                                        <th class="py-3 px-3 w-20 sm:w-24 text-center">Qty</th>
                                        <th class="py-3 px-3 w-24 sm:w-28 text-right">Price (RM)</th>
                                        <th class="py-3 px-3 w-24 sm:w-28 text-right">Disc (RM)</th>
                                        <th class="py-3 px-3 w-28 sm:w-32 text-right">Total (RM)</th>
                                        <th class="py-3 px-3 w-10 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="items-tbody" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                    <!-- Dynamic Rows Injected by JS -->
                                </tbody>
                            </table>
                        </div>
                    </x-card>

                    <!-- 3. Clinical Remarks Card -->
                    <x-card title="Invoice Remarks / Patient Instructions" subtitle="Optional instructions printed on the official invoice">
                        <textarea
                            name="notes"
                            rows="2"
                            placeholder="e.g., Take medication after meals. Follow-up consultation in 7 days..."
                            class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-xs sm:text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition"
                        ></textarea>
                    </x-card>
                </div>

                <!-- Right Column: Calculation Summary & Checkout Settlement (Span 4, Sticky on Desktop) -->
                <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-4">
                    
                    <!-- 1. Calculation Breakdown Card with SST Malaysian Law Compliance -->
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-black/40 overflow-hidden">
                        
                        <!-- Card Header -->
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800/80 bg-gradient-to-r from-slate-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-850 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-200/80 dark:border-indigo-800/80 flex items-center justify-center text-base shadow-xs">
                                    <i class="bx bx-calculator"></i>
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-sm text-slate-900 dark:text-white tracking-tight">Calculation Summary</h3>
                                    <p class="text-[10px] text-slate-400">Malaysian SST &amp; Discount Breakdown</p>
                                </div>
                            </div>
                            <!-- SST Compliance Badge -->
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                <i class="bx bx-shield-quarter text-xs text-emerald-500"></i>
                                <span>SST Act 2018</span>
                            </span>
                        </div>

                        <div class="p-5 space-y-3.5 text-xs">
                            <!-- Gross Line Items -->
                            <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                                <span class="font-medium">Gross Line Items</span>
                                <span class="font-mono font-bold text-slate-900 dark:text-white text-sm" id="summary-subtotal">RM 0.00</span>
                            </div>

                            <!-- Special Discount -->
                            <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                                <span class="font-medium">Special Discount</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs text-slate-400 font-mono">RM</span>
                                    <input
                                        type="number"
                                        name="discount_amount"
                                        id="overall_discount"
                                        value="0.00"
                                        step="0.01"
                                        min="0"
                                        oninput="calculateTotals()"
                                        class="w-24 py-1.5 px-2.5 text-right rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 text-xs font-mono font-bold text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none transition"
                                    >
                                </div>
                            </div>

                            <!-- Malaysian SST Tax Section -->
                            <div class="pt-2 border-t border-dashed border-slate-200 dark:border-slate-800">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">Service Tax (SST)</span>
                                    <div class="flex items-center gap-2">
                                        <select
                                            id="sst_rate_select"
                                            onchange="calculateTotals()"
                                            class="py-1 px-2 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[11px] font-mono font-bold text-slate-700 dark:text-slate-200 focus:outline-none cursor-pointer"
                                        >
                                            <option value="0" {{ ($profile->default_tax_rate ?? 0) == 0 ? 'selected' : '' }}>0% (Exempt)</option>
                                            <option value="6" {{ ($profile->default_tax_rate ?? 0) == 6 ? 'selected' : '' }}>6% (SST Rate)</option>
                                            <option value="8" {{ ($profile->default_tax_rate ?? 0) == 8 ? 'selected' : '' }}>8% (Standard SST)</option>
                                        </select>
                                        <span class="font-mono font-bold text-slate-900 dark:text-white text-xs w-20 text-right" id="summary-tax">RM 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Grand Total Banner -->
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-between items-baseline">
                                <div>
                                    <span class="text-sm font-black text-slate-900 dark:text-white tracking-tight">Grand Total</span>
                                    <div class="text-[10px] text-slate-400">Nett payable in MYR</div>
                                </div>
                                <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight" id="summary-grand-total">RM 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Front-Desk Settlement Tender Card -->
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-black/40 overflow-hidden">
                        <!-- Card Header -->
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800/80 bg-gradient-to-r from-slate-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-850 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/80 flex items-center justify-center text-base shadow-xs">
                                    <i class="bx bx-wallet"></i>
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-sm text-slate-900 dark:text-white tracking-tight">Front-Desk Settlement</h3>
                                    <p class="text-[10px] text-slate-400">Tender payment &amp; issue invoice</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300">
                                Real-Time POS
                            </span>
                        </div>

                        <div class="p-5 space-y-4">
                            <!-- Amount Tendered Input -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    <label for="payment_received">Payment Received</label>
                                    <button
                                        type="button"
                                        onclick="matchExactTender()"
                                        class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 px-2 py-0.5 rounded-lg border border-indigo-200/70 dark:border-indigo-800/70 cursor-pointer transition"
                                    >
                                        [exact amount]
                                    </button>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none font-mono font-bold text-slate-400 text-sm">
                                        RM
                                    </div>
                                    <input
                                        type="number"
                                        name="payment_received"
                                        id="payment_received"
                                        step="0.01"
                                        min="0"
                                        placeholder="0.00"
                                        class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-mono font-black text-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition"
                                    >
                                </div>
                            </div>

                            <!-- Payment Channel -->
                            <div class="space-y-1.5">
                                <label for="payment_method" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    Payment Channel
                                </label>
                                <div class="relative">
                                    <select
                                        name="payment_method"
                                        id="payment_method"
                                        class="w-full py-2.5 pl-3.5 pr-9 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs font-semibold focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none appearance-none transition cursor-pointer"
                                    >
                                        <option value="cash">Cash Tender (Register Drawer)</option>
                                        <option value="qr">DuitNow QR Pay</option>
                                        <option value="card">Credit / Debit Card (EDC Terminal)</option>
                                        <option value="transfer">Online Bank Transfer</option>
                                        <option value="panel">Insurance Panel / GL</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                        <i class="bx bx-chevron-down text-base"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank & Ref -->
                            <div class="grid grid-cols-2 gap-2.5">
                                <x-input
                                    name="bank_name"
                                    placeholder="Bank / EDC (e.g. Maybank)"
                                />
                                <x-input
                                    name="transaction_ref"
                                    placeholder="RRN / Ref #"
                                />
                            </div>

                            <!-- Submit Action Trigger -->
                            <button
                                type="button"
                                onclick="openInvoiceConfirmModal()"
                                class="w-full py-3.5 px-4 rounded-2xl font-bold text-sm bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transform active:scale-98 transition cursor-pointer"
                            >
                                <i class="bx bx-check-double text-xl"></i>
                                <span>Finalize &amp; Issue Invoice</span>
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>

    <!-- ========================================== -->
    <!-- 3. Invoice Finalization Confirmation Modal -->
    <!-- ========================================== -->
    <x-modal name="invoice-confirm-modal" title="Confirm Invoice Generation" size="lg">
        <div class="space-y-4">
            <div class="p-4 rounded-2xl bg-indigo-50/60 dark:bg-indigo-950/40 border border-indigo-200/80 dark:border-indigo-800/60 flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 font-bold text-lg shadow-md shadow-indigo-600/30">
                    <i class="bx bx-receipt"></i>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">Ready to finalize invoice?</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                        Please review the patient billing transaction summary below before issuing the formal clinic invoice and receipts.
                    </p>
                </div>
            </div>

            <!-- Summary Table Grid -->
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden text-xs">
                <div class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-slate-50/40 dark:bg-slate-900/40">
                    <div class="p-3 sm:px-4 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 font-semibold">Patient Record:</span>
                        <span class="font-bold text-slate-900 dark:text-white text-right" id="confirm_patient_name">-</span>
                    </div>
                    <div class="p-3 sm:px-4 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 font-semibold">Attending Doctor:</span>
                        <span class="font-bold text-slate-900 dark:text-white text-right" id="confirm_doctor_name">-</span>
                    </div>
                    <div class="p-3 sm:px-4 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 font-semibold">Total Line Items:</span>
                        <span class="font-mono font-bold text-slate-900 dark:text-white" id="confirm_item_count">0 item(s)</span>
                    </div>
                    <div class="p-3 sm:px-4 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 font-semibold">Payment Channel:</span>
                        <span class="font-bold text-slate-900 dark:text-white capitalize" id="confirm_pay_channel">Cash</span>
                    </div>
                    <div class="p-3 sm:px-4 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 font-semibold">Service Tax (SST):</span>
                        <span class="font-mono font-bold text-slate-900 dark:text-white" id="confirm_sst_amount">RM 0.00 (Exempt)</span>
                    </div>
                    <div class="p-3.5 sm:px-4 bg-emerald-50/50 dark:bg-emerald-950/30 flex justify-between items-center">
                        <span class="text-sm font-extrabold text-slate-900 dark:text-white">Grand Total Amount:</span>
                        <span class="text-lg font-black text-emerald-600 dark:text-emerald-400 font-mono" id="confirm_grand_total">RM 0.00</span>
                    </div>
                    <div class="p-3 sm:px-4 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400 font-semibold">Front-Desk Tender Received:</span>
                        <span class="font-mono font-bold text-slate-900 dark:text-white" id="confirm_paid_amount">RM 0.00</span>
                    </div>
                </div>
            </div>

            <div class="text-[11px] text-slate-400 flex items-center gap-1.5 px-1">
                <i class="bx bx-info-circle text-indigo-500 text-sm"></i>
                <span>This transaction will be recorded in the active cash drawer & audit trail logs.</span>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex items-center justify-end gap-3 w-full">
                <x-button
                    type="button"
                    variant="ghost"
                    size="md"
                    onclick="document.getElementById('modal-invoice-confirm-modal').classList.add('hidden')"
                >
                    Back to Edit
                </x-button>

                <x-button
                    type="button"
                    variant="success"
                    size="md"
                    icon="bx bx-printer"
                    onclick="submitInvoiceForm()"
                    class="shadow-lg shadow-emerald-600/30"
                >
                    Confirm &amp; Issue Invoice
                </x-button>
            </div>
        </x-slot:footer>
    </x-modal>

    <!-- ========================================== -->
    <!-- 1. Patient Selection Modal with Live Search -->
    <!-- ========================================== -->
    <x-modal name="patient-search-modal" title="Select Patient Record" size="2xl">
        <div class="space-y-4">
            
            <!-- Live Search Bar inside Modal -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="bx bx-search text-lg"></i>
                </div>
                <input
                    type="text"
                    id="modal_patient_search"
                    oninput="filterPatientList()"
                    placeholder="Search by Patient Name, IC / Passport Number, Phone..."
                    class="w-full pl-10 pr-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-xs sm:text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition"
                    autofocus
                >
            </div>

            <!-- Scrollable Patients List -->
            <div class="max-h-80 overflow-y-auto custom-scrollbar space-y-2 pr-1" id="patient_list_container">
                @foreach($patients as $patient)
                    <div
                        onclick="selectPatient('{{ $patient->id }}', '{{ addslashes($patient->name) }}', '{{ $patient->id_number }}', '{{ $patient->phone }}', '{{ addslashes($patient->allergies ?? '') }}')"
                        class="patient-list-item p-3.5 rounded-2xl bg-white dark:bg-slate-800/60 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 border border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 flex items-center justify-between gap-3 transition-all cursor-pointer group"
                        data-name="{{ strtolower($patient->name) }}"
                        data-ic="{{ strtolower($patient->id_number) }}"
                        data-phone="{{ strtolower($patient->phone) }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center font-bold text-xs shrink-0 border border-indigo-200 dark:border-indigo-800 transition-colors">
                                {{ strtoupper(substr($patient->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate">
                                    {{ $patient->name }}
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono flex items-center gap-1.5 flex-wrap mt-0.5">
                                    <span>IC: {{ $patient->id_number }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $patient->phone }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            @if($patient->allergies && $patient->allergies !== 'None')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40">
                                    Allergy Alert
                                </span>
                            @else
                                <span class="text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 text-xs font-semibold flex items-center gap-1">
                                    Select <i class="bx bx-check"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Empty State Search Placeholder -->
                <div id="no_patients_found" class="hidden text-center py-8 text-slate-400 text-xs">
                    <i class="bx bx-user-x text-3xl mb-1 text-slate-400"></i>
                    <p>No matching patient records found.</p>
                </div>
            </div>

        </div>

        <x-slot:footer>
            <div class="flex items-center justify-between w-full">
                <a
                    href="{{ route('admin.patients.create') }}"
                    target="_blank"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline"
                >
                    <i class="bx bx-user-plus text-base"></i>
                    <span>Register New Patient</span>
                </a>

                <x-button
                    type="button"
                    variant="ghost"
                    size="sm"
                    onclick="document.getElementById('modal-patient-search-modal').classList.add('hidden')"
                >
                    Close
                </x-button>
            </div>
        </x-slot:footer>
    </x-modal>

    <!-- ========================================== -->
    <!-- 2. Doctor Selection Modal with Live Search -->
    <!-- ========================================== -->
    <x-modal name="doctor-search-modal" title="Select Attending Physician / Doctor" size="lg">
        <div class="space-y-4">
            
            <!-- Live Search Bar inside Modal -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="bx bx-search text-lg"></i>
                </div>
                <input
                    type="text"
                    id="modal_doctor_search"
                    oninput="filterDoctorList()"
                    placeholder="Search doctor by Name, Staff ID, or Specialization..."
                    class="w-full pl-10 pr-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-xs sm:text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition"
                >
            </div>

            <!-- Scrollable Doctors List -->
            <div class="max-h-72 overflow-y-auto custom-scrollbar space-y-2 pr-1" id="doctor_list_container">
                @foreach($doctors as $doctor)
                    <div
                        onclick="selectDoctor('{{ addslashes($doctor->name) }}', '{{ $doctor->staff_id ?? 'ADM-001' }}', '{{ $doctor->role ?? 'Physician' }}')"
                        class="doctor-list-item p-3.5 rounded-2xl bg-white dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 border border-slate-200 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-emerald-600 flex items-center justify-between gap-3 transition-all cursor-pointer group"
                        data-name="{{ strtolower($doctor->name) }}"
                        data-id="{{ strtolower($doctor->staff_id ?? '') }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center font-bold text-xs shrink-0 border border-emerald-200 dark:border-emerald-800 transition-colors">
                                <i class="bx bx-plus-medical"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 truncate">
                                    {{ $doctor->name }}
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">
                                    Staff ID: {{ $doctor->staff_id ?? 'STF-001' }} &bull; {{ strtoupper($doctor->role ?? 'PHYSICIAN') }}
                                </div>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <span class="text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-300 text-xs font-semibold flex items-center gap-1">
                                Assign <i class="bx bx-check"></i>
                            </span>
                        </div>
                    </div>
                @endforeach

                <!-- Empty State Doctor Placeholder -->
                <div id="no_doctors_found" class="hidden text-center py-8 text-slate-400 text-xs">
                    <i class="bx bx-user-x text-3xl mb-1 text-slate-400"></i>
                    <p>No matching physician records found.</p>
                </div>
            </div>

        </div>

        <x-slot:footer>
            <div class="flex justify-end w-full">
                <x-button
                    type="button"
                    variant="ghost"
                    size="sm"
                    onclick="document.getElementById('modal-doctor-search-modal').classList.add('hidden')"
                >
                    Close
                </x-button>
            </div>
        </x-slot:footer>
    </x-modal>

    <!-- Dynamic Line Item, Patient & Doctor Picker Script -->
    <script>
        let rowCount = 0;

        // Patient Modal Helpers
        function openPatientModal() {
            const modal = document.getElementById('modal-patient-search-modal');
            const searchInput = document.getElementById('modal_patient_search');
            if (modal) {
                modal.classList.remove('hidden');
                if (searchInput) {
                    searchInput.value = '';
                    filterPatientList();
                    setTimeout(() => searchInput.focus(), 100);
                }
            }
        }

        function filterPatientList() {
            const query = (document.getElementById('modal_patient_search')?.value || '').toLowerCase().trim();
            const items = document.querySelectorAll('.patient-list-item');
            let visibleCount = 0;

            items.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                const ic = item.getAttribute('data-ic') || '';
                const phone = item.getAttribute('data-phone') || '';

                if (name.includes(query) || ic.includes(query) || phone.includes(query)) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            const emptyState = document.getElementById('no_patients_found');
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        }

        function selectPatient(id, name, ic, phone, allergies) {
            const idInput = document.getElementById('selected_patient_id');
            if (idInput) idInput.value = id;

            const nameEl = document.getElementById('patient_selected_name');
            const subEl = document.getElementById('patient_selected_sub');

            if (nameEl) {
                nameEl.innerHTML = `${name} ${allergies && allergies !== 'None' ? '<span class="ml-1 text-[10px] px-1.5 py-0.5 rounded bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-300 font-normal">Allergy: ' + allergies + '</span>' : ''}`;
            }
            if (subEl) {
                subEl.innerText = `IC: ${ic} • ${phone}`;
            }

            document.getElementById('modal-patient-search-modal')?.classList.add('hidden');
        }

        // Doctor Modal Helpers
        function openDoctorModal() {
            const modal = document.getElementById('modal-doctor-search-modal');
            const searchInput = document.getElementById('modal_doctor_search');
            if (modal) {
                modal.classList.remove('hidden');
                if (searchInput) {
                    searchInput.value = '';
                    filterDoctorList();
                    setTimeout(() => searchInput.focus(), 100);
                }
            }
        }

        function filterDoctorList() {
            const query = (document.getElementById('modal_doctor_search')?.value || '').toLowerCase().trim();
            const items = document.querySelectorAll('.doctor-list-item');
            let visibleCount = 0;

            items.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                const id = item.getAttribute('data-id') || '';

                if (name.includes(query) || id.includes(query)) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            const emptyState = document.getElementById('no_doctors_found');
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        }

        function selectDoctor(name, staffId, role) {
            const doctorInput = document.getElementById('selected_doctor_name');
            if (doctorInput) doctorInput.value = name;

            const nameEl = document.getElementById('doctor_selected_name');
            const subEl = document.getElementById('doctor_selected_sub');

            if (nameEl) nameEl.innerText = name;
            if (subEl) subEl.innerText = `Consulting Physician • ${staffId}`;

            document.getElementById('modal-doctor-search-modal')?.classList.add('hidden');
        }

        // Line Item Helpers
        function addLineItem(itemId = '', name = '', code = '', price = 0) {
            rowCount++;
            const tbody = document.getElementById('items-tbody');
            const row = document.createElement('tr');
            row.id = `item-row-${rowCount}`;
            row.setAttribute('data-master-id', itemId ? String(itemId) : '');
            row.className = 'hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors';

            row.innerHTML = `
                <td class="py-2.5 px-4">
                    <input type="hidden" name="items[${rowCount}][item_id]" value="${itemId}">
                    <input type="hidden" name="items[${rowCount}][item_code]" value="${code}">
                    <input type="hidden" name="items[${rowCount}][tax]" id="tax-${rowCount}" value="0.00">
                    <input
                        type="text"
                        name="items[${rowCount}][item_name]"
                        value="${name}"
                        required
                        placeholder="Item / Treatment description"
                        class="w-full py-2 px-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white focus:border-indigo-500 focus:outline-none"
                    >
                </td>
                <td class="py-2.5 px-3">
                    <input
                        type="number"
                        name="items[${rowCount}][quantity]"
                        id="qty-${rowCount}"
                        value="1"
                        min="1"
                        required
                        oninput="calculateRow(${rowCount})"
                        class="w-full py-2 px-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-center font-mono font-bold focus:border-indigo-500 focus:outline-none"
                    >
                </td>
                <td class="py-2.5 px-3 text-right">
                    <input
                        type="number"
                        name="items[${rowCount}][unit_price]"
                        id="price-${rowCount}"
                        value="${price.toFixed(2)}"
                        step="0.01"
                        min="0"
                        required
                        oninput="calculateRow(${rowCount})"
                        class="w-full py-2 px-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-right font-mono focus:border-indigo-500 focus:outline-none"
                    >
                </td>
                <td class="py-2.5 px-3 text-right">
                    <input
                        type="number"
                        name="items[${rowCount}][discount]"
                        id="disc-${rowCount}"
                        value="0.00"
                        step="0.01"
                        min="0"
                        oninput="calculateRow(${rowCount})"
                        class="w-full py-2 px-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-right font-mono focus:border-indigo-500 focus:outline-none"
                    >
                </td>
                <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900 dark:text-white text-xs" id="row-total-${rowCount}">
                    RM ${price.toFixed(2)}
                </td>
                <td class="py-2.5 px-3 text-center">
                    <button
                        type="button"
                        onclick="removeLineItem(${rowCount})"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer"
                        title="Remove Line"
                    >
                        <i class="bx bx-trash text-base"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(row);
            calculateTotals();
        }

        let activePresetCategory = 'all';

        function filterPresetCategory(category) {
            activePresetCategory = category;

            // Update tab styles
            document.querySelectorAll('.preset-cat-tab').forEach(tab => {
                tab.classList.remove('bg-indigo-600', 'text-white', 'shadow-xs', 'font-bold');
                tab.classList.add('bg-white', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300', 'border', 'border-slate-200', 'dark:border-slate-700/70');
            });

            const activeTab = document.getElementById(`preset-cat-${category}`);
            if (activeTab) {
                activeTab.classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300', 'border', 'border-slate-200', 'dark:border-slate-700/70');
                activeTab.classList.add('bg-indigo-600', 'text-white', 'shadow-xs', 'font-bold');
            }

            applyPresetFilters();
        }

        function searchPresetItems() {
            const query = (document.getElementById('preset_catalog_search')?.value || '').trim();
            const clearBtn = document.getElementById('preset_clear_search_btn');
            if (clearBtn) {
                if (query.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }
            applyPresetFilters();
        }

        function clearPresetSearch() {
            const input = document.getElementById('preset_catalog_search');
            if (input) {
                input.value = '';
                document.getElementById('preset_clear_search_btn')?.classList.add('hidden');
                input.focus();
            }
            applyPresetFilters();
        }

        function applyPresetFilters() {
            const query = (document.getElementById('preset_catalog_search')?.value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.preset-item-row');
            let matchCount = 0;

            rows.forEach(row => {
                const category = row.getAttribute('data-category') || '';
                const name = row.getAttribute('data-name') || '';
                const code = row.getAttribute('data-code') || '';

                const categoryMatches = (activePresetCategory === 'all') || (category === activePresetCategory);
                const queryMatches = !query || name.includes(query) || code.includes(query);

                if (categoryMatches && queryMatches) {
                    row.classList.remove('hidden');
                    matchCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            const emptyState = document.getElementById('no_preset_items_found');
            if (emptyState) {
                if (matchCount === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        }

        function handleRowCheckboxClick(event, id, name, code, price) {
            // Prevent triggering if clicked on an interactive element inside the row
            if (['INPUT', 'BUTTON', 'I'].includes(event.target.tagName)) {
                return;
            }
            const checkbox = document.getElementById(`preset-checkbox-${id}`);
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                toggleCatalogItemCheckbox(id, name, code, price);
            }
        }

        function toggleCatalogItemCheckbox(id, name, code, price) {
            const checkbox = document.getElementById(`preset-checkbox-${id}`);
            const isChecked = checkbox ? checkbox.checked : false;

            if (isChecked) {
                // Add item to billed table if not already added
                const existingRow = document.querySelector(`#items-tbody tr[data-master-id="${id}"]`);
                if (!existingRow) {
                    addLineItem(id, name, code, price);
                }
            } else {
                // Remove item from billed table if unchecked
                const existingRow = document.querySelector(`#items-tbody tr[data-master-id="${id}"]`);
                if (existingRow) {
                    const rowId = existingRow.id.replace('item-row-', '');
                    removeLineItem(rowId);
                }
            }
            syncCatalogCheckboxes();
        }

        function syncCatalogCheckboxes() {
            const activeMasterIds = new Set();
            document.querySelectorAll('#items-tbody tr').forEach(row => {
                const masterId = row.getAttribute('data-master-id');
                if (masterId) {
                    activeMasterIds.add(String(masterId));
                }
            });

            document.querySelectorAll('.preset-catalog-checkbox').forEach(cb => {
                const masterId = cb.id.replace('preset-checkbox-', '');
                const isSelected = activeMasterIds.has(masterId);
                cb.checked = isSelected;

                const tr = document.getElementById(`preset-row-${masterId}`);
                if (tr) {
                    if (isSelected) {
                        tr.classList.add('bg-indigo-50/80', 'dark:bg-indigo-950/40');
                    } else {
                        tr.classList.remove('bg-indigo-50/80', 'dark:bg-indigo-950/40');
                    }
                }
            });

            // Update selected badge
            const badge = document.getElementById('catalog-selected-badge');
            const countEl = document.getElementById('catalog-selected-count');
            if (badge && countEl) {
                countEl.innerText = activeMasterIds.size;
                if (activeMasterIds.size > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        }

        function addPresetItem(id, name, code, price) {
            addLineItem(id, name, code, price);
            syncCatalogCheckboxes();
        }

        function removeLineItem(rowId) {
            const row = document.getElementById(`item-row-${rowId}`);
            if (row) {
                row.remove();
                calculateTotals();
                syncCatalogCheckboxes();
            }
        }

        function calculateRow(rowId) {
            const qty = parseFloat(document.getElementById(`qty-${rowId}`)?.value || 0);
            const price = parseFloat(document.getElementById(`price-${rowId}`)?.value || 0);
            const disc = parseFloat(document.getElementById(`disc-${rowId}`)?.value || 0);

            const total = Math.max(0, (qty * price) - disc);
            const cell = document.getElementById(`row-total-${rowId}`);
            if (cell) cell.innerText = `RM ${total.toFixed(2)}`;

            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            const rows = document.querySelectorAll('#items-tbody tr');

            rows.forEach(row => {
                const rowId = row.id.replace('item-row-', '');
                const qty = parseFloat(document.getElementById(`qty-${rowId}`)?.value || 0);
                const price = parseFloat(document.getElementById(`price-${rowId}`)?.value || 0);
                const disc = parseFloat(document.getElementById(`disc-${rowId}`)?.value || 0);
                subtotal += Math.max(0, (qty * price) - disc);
            });

            const overallDisc = parseFloat(document.getElementById('overall_discount')?.value || 0);
            const taxableAmount = Math.max(0, subtotal - overallDisc);
            const sstRate = parseFloat(document.getElementById('sst_rate_select')?.value || 0);
            const totalTax = (taxableAmount * (sstRate / 100));
            const grandTotal = taxableAmount + totalTax;

            // Synchronize hidden line tax inputs so backend InvoiceController receives tax per item
            const activeRowCount = rows.length;
            if (activeRowCount > 0) {
                const taxPerRow = totalTax / activeRowCount;
                rows.forEach(row => {
                    const rowId = row.id.replace('item-row-', '');
                    const taxHidden = document.getElementById(`tax-${rowId}`);
                    if (taxHidden) taxHidden.value = taxPerRow.toFixed(2);
                });
            }

            document.getElementById('summary-subtotal').innerText = `RM ${subtotal.toFixed(2)}`;
            document.getElementById('summary-tax').innerText = `RM ${totalTax.toFixed(2)}`;
            document.getElementById('summary-grand-total').innerText = `RM ${grandTotal.toFixed(2)}`;

            const tenderInput = document.getElementById('payment_received');
            if (tenderInput && (!tenderInput.value || tenderInput.value === '0.00')) {
                tenderInput.value = grandTotal.toFixed(2);
            }
        }

        function matchExactTender() {
            let grandTotalText = document.getElementById('summary-grand-total')?.innerText || 'RM 0.00';
            let amount = grandTotalText.replace('RM', '').trim();
            const tenderInput = document.getElementById('payment_received');
            if (tenderInput) {
                tenderInput.value = amount;
            }
        }

        // Confirmation Modal Logic
        function openInvoiceConfirmModal() {
            const patientId = document.getElementById('selected_patient_id')?.value;
            const patientName = document.getElementById('patient_selected_name')?.innerText?.trim() || '';
            const doctorName = document.getElementById('selected_doctor_name')?.value || 'Dr. Aiman Hakim';
            const rowCount = document.querySelectorAll('#items-tbody tr').length;
            const grandTotal = document.getElementById('summary-grand-total')?.innerText || 'RM 0.00';
            const taxText = document.getElementById('summary-tax')?.innerText || 'RM 0.00';
            const sstRate = document.getElementById('sst_rate_select')?.value || '0';
            const paidAmount = parseFloat(document.getElementById('payment_received')?.value || 0).toFixed(2);
            const payMethodSelect = document.getElementById('payment_method');
            const payMethodText = payMethodSelect ? payMethodSelect.options[payMethodSelect.selectedIndex]?.text : 'Cash';

            if (!patientId) {
                alert('Please select a registered patient before issuing the invoice.');
                openPatientModal();
                return;
            }

            if (rowCount === 0) {
                alert('Please add at least one line item (treatment, consultation, or medication) to the invoice.');
                return;
            }

            // Populate confirmation modal fields
            document.getElementById('confirm_patient_name').innerText = patientName.split('\n')[0];
            document.getElementById('confirm_doctor_name').innerText = doctorName;
            document.getElementById('confirm_item_count').innerText = `${rowCount} item(s)`;
            document.getElementById('confirm_pay_channel').innerText = payMethodText;
            document.getElementById('confirm_sst_amount').innerText = `${taxText} (${sstRate}% ${sstRate == 0 ? 'Exempt' : 'SST'})`;
            document.getElementById('confirm_grand_total').innerText = grandTotal;
            document.getElementById('confirm_paid_amount').innerText = `RM ${paidAmount}`;

            // Show confirmation modal
            document.getElementById('modal-invoice-confirm-modal')?.classList.remove('hidden');
        }

        function submitInvoiceForm() {
            const form = document.getElementById('invoice-form');
            if (form) {
                form.submit();
            }
        }

        // Initialize with default Consultation row on load & auto-select first patient
        document.addEventListener('DOMContentLoaded', () => {
            addLineItem(1, 'Standard General Consultation', 'CON-01', 45.00);
            syncCatalogCheckboxes();

            @if(isset($patients) && $patients->isNotEmpty())
                const firstPatient = @json($patients->first());
                selectPatient(firstPatient.id, firstPatient.name, firstPatient.id_number, firstPatient.phone, firstPatient.allergies || '');
            @endif
        });
    </script>

</x-layouts.admin>
