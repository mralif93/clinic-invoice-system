<x-layouts.admin title="Invoice & Receipt Design Templates">

    <div class="space-y-6">

        <!-- Page Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-indigo-900 p-6 sm:p-7 border border-indigo-800/40 shadow-xl shadow-indigo-950/40 text-white">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300 font-bold border border-white/10">
                            <i class="bx bx-slider-alt text-lg"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">Invoice &amp; Thermal Receipt Layout Templates</h1>
                    </div>
                    <p class="text-xs text-slate-300">FR-3.5 &bull; Live rendering of the official A4 Formal Tax Invoice and 80mm ESC/POS thermal counter slip</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- 80mm Thermal Receipt Preview Card -->
            <div class="lg:col-span-5 space-y-4">
                <x-card title="80mm Thermal Slip Template" subtitle="High-speed POS thermal printer layout">
                    <div class="p-4 rounded-2xl bg-white text-black font-mono text-[11px] space-y-3 border border-slate-300 shadow-sm leading-relaxed mx-auto max-w-sm">
                        <!-- Receipt Header -->
                        <div class="text-center space-y-0.5 border-b border-dashed border-slate-400 pb-3">
                            <div class="font-bold text-sm uppercase">{{ $profile->clinic_name }}</div>
                            <div>{{ $profile->address }}</div>
                            <div>Tel: {{ $profile->phone }}</div>
                            <div class="text-[10px]">SSM / Reg: {{ $profile->registration_number }}</div>
                        </div>

                        <!-- Metadata -->
                        <div class="text-[10px] space-y-0.5 border-b border-dashed border-slate-400 pb-2">
                            <div class="flex justify-between"><span>Inv No:</span> <span>INV-20260901-DEMO</span></div>
                            <div class="flex justify-between"><span>Date:</span> <span>{{ now()->format('d/m/Y H:i') }}</span></div>
                            <div class="flex justify-between"><span>Doctor:</span> <span>Dr. Aiman Hakim</span></div>
                            <div class="flex justify-between"><span>Patient:</span> <span>Sample Patient (IC: 900101-14-1234)</span></div>
                        </div>

                        <!-- Item lines -->
                        <div class="space-y-1.5 border-b border-dashed border-slate-400 pb-2">
                            <div class="flex justify-between">
                                <div class="truncate max-w-[170px]">1x Standard Consultation</div>
                                <div class="text-right">{{ $profile->currency_symbol }} 45.00</div>
                            </div>
                            <div class="flex justify-between">
                                <div class="truncate max-w-[170px]">1x Paracetamol 500mg (10 tabs)</div>
                                <div class="text-right">{{ $profile->currency_symbol }} 8.00</div>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="space-y-1 border-b border-dashed border-slate-400 pb-2">
                            <div class="flex justify-between"><span>Subtotal:</span> <span>{{ $profile->currency_symbol }} 53.00</span></div>
                            <div class="flex justify-between font-bold text-xs pt-1"><span>TOTAL DUE:</span> <span>{{ $profile->currency_symbol }} 53.00</span></div>
                            <div class="flex justify-between"><span>PAID AMOUNT:</span> <span>{{ $profile->currency_symbol }} 53.00</span></div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center text-[9px] pt-1 text-slate-600">
                            {{ $profile->receipt_footer }}
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- A4 Formal Tax Invoice Template Card -->
            <div class="lg:col-span-7 space-y-4">
                <x-card title="A4 Formal Tax Invoice Template" subtitle="Formal medical insurance and tax claim document">
                    <div class="p-6 rounded-2xl bg-white text-slate-900 border border-slate-200 shadow-sm space-y-5 text-xs font-sans">
                        <!-- Formal Header -->
                        <div class="flex justify-between items-start border-b border-slate-200 pb-4">
                            <div class="space-y-1">
                                <div class="text-lg font-extrabold text-indigo-700 uppercase">{{ $profile->clinic_name }}</div>
                                <p class="text-slate-500 text-[11px]">{{ $profile->address }}</p>
                                <p class="text-slate-500 text-[11px]">Tel: {{ $profile->phone }} &bull; Email: {{ $profile->email }}</p>
                                <p class="text-slate-500 text-[11px]">Reg: {{ $profile->registration_number }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-black text-slate-900 font-mono">TAX INVOICE</div>
                                <div class="font-mono font-bold text-indigo-600 text-xs mt-1">INV-20260901-DEMO</div>
                                <div class="text-slate-400 text-[10px] font-mono mt-0.5">Date: {{ now()->format('d M Y') }}</div>
                            </div>
                        </div>

                        <!-- Patient & Attendant -->
                        <div class="grid grid-cols-2 gap-4 py-1 text-xs">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-0.5">
                                <div class="font-bold text-slate-400 uppercase text-[9px]">Patient Details</div>
                                <div class="font-bold text-slate-900">Sample Patient Record</div>
                                <div class="text-slate-600 font-mono text-[11px]">IC: 900101-14-1234</div>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-0.5 text-right">
                                <div class="font-bold text-slate-400 uppercase text-[9px]">Clinical Reference</div>
                                <div class="font-bold text-slate-900">Dr. Aiman Hakim</div>
                                <div class="text-slate-600 text-[11px]">Status: <strong class="text-emerald-600">PAID</strong></div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                            <thead class="bg-slate-100 font-bold uppercase text-[9px] text-slate-600">
                                <tr>
                                    <th class="py-2 px-3">Item Description</th>
                                    <th class="py-2 px-3 text-center">Qty</th>
                                    <th class="py-2 px-3 text-right">Rate</th>
                                    <th class="py-2 px-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <tr>
                                    <td class="py-2 px-3">Standard General Consultation</td>
                                    <td class="py-2 px-3 text-center font-mono">1</td>
                                    <td class="py-2 px-3 text-right font-mono">{{ $profile->currency_symbol }} 45.00</td>
                                    <td class="py-2 px-3 text-right font-mono font-bold">{{ $profile->currency_symbol }} 45.00</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-3">Paracetamol 500mg (10 tabs)</td>
                                    <td class="py-2 px-3 text-center font-mono">1</td>
                                    <td class="py-2 px-3 text-right font-mono">{{ $profile->currency_symbol }} 8.00</td>
                                    <td class="py-2 px-3 text-right font-mono font-bold">{{ $profile->currency_symbol }} 8.00</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Grand Total -->
                        <div class="flex justify-between items-center pt-2">
                            <div class="text-[10px] text-slate-500 max-w-xs">
                                {{ $profile->invoice_terms }}
                            </div>
                            <div class="text-right font-mono">
                                <div class="text-slate-500 text-xs">Total Payable:</div>
                                <div class="text-base font-black text-indigo-700">{{ $profile->currency_symbol }} 53.00</div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

        </div>

    </div>

</x-layouts.admin>
