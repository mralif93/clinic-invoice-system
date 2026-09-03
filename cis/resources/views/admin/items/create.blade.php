<x-layouts.admin title="Add Catalog Item">

    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.items.index') }}" class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                    <i class="bx bx-arrow-back text-lg"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Add Master Catalog Item</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Configure SKU, pricing, category, and inventory levels for POS invoicing</p>
                </div>
            </div>
        </div>

        @if($errors->any())
            <x-alert type="danger" dismissible="true">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-card title="Item Details Form">
            <form action="{{ route('admin.items.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- SKU Code -->
                    <x-input
                        label="Item Code / SKU"
                        name="code"
                        placeholder="e.g. MED-305 or PRC-09"
                        icon="bx bx-barcode"
                        required
                    />

                    <!-- Category -->
                    <div class="space-y-1.5">
                        <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Category <span class="text-rose-500">*</span>
                        </label>
                        <select
                            name="category"
                            id="category"
                            required
                            class="w-full py-3 px-3.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition"
                        >
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Name -->
                <x-input
                    label="Item / Treatment Description"
                    name="name"
                    placeholder="e.g. Augmentin 625mg (14 tabs) or ECG Electrocardiogram"
                    icon="bx bx-tag"
                    required
                />

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Unit Price -->
                    <x-input
                        label="Unit Selling Price (RM)"
                        name="unit_price"
                        type="number"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        icon="bx bx-money"
                        required
                    />

                    <!-- Tax Rate -->
                    <x-input
                        label="Tax Rate (%)"
                        name="tax_rate"
                        type="number"
                        step="0.01"
                        min="0"
                        value="0.00"
                        icon="bx bx-receipt"
                    />

                    <!-- Stock Quantity -->
                    <x-input
                        label="Stock Quantity"
                        name="stock_quantity"
                        type="number"
                        min="0"
                        value="100"
                        icon="bx bx-box"
                        required
                    />
                </div>

                <!-- Submit Actions -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                    <x-button href="{{ route('admin.items.index') }}" variant="ghost" size="md">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="success" size="md" icon="bx bx-check">
                        Save to Catalog
                    </x-button>
                </div>
            </form>
        </x-card>

    </div>

</x-layouts.admin>
