@props([
    'headers' => [],
    'empty' => 'No records found.',
    'striped' => false,
    'compact' => false,
])

<div {{ $attributes->merge(['class' => 'w-full overflow-hidden transition-colors']) }}>
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left {{ $compact ? 'text-[11px]' : 'text-xs' }} text-slate-700 dark:text-slate-300">
            @if(!empty($headers))
                <thead class="bg-slate-50/80 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200 dark:border-slate-800 select-none">
                    <tr>
                        @foreach($headers as $header)
                            @php
                                $align = is_array($header) && isset($header['align']) ? $header['align'] : 'left';
                                $label = is_array($header) ? $header['label'] : $header;
                                $alignClass = match($align) {
                                    'right' => 'text-right',
                                    'center' => 'text-center',
                                    default => 'text-left',
                                };
                            @endphp
                            <th class="{{ $compact ? 'py-2.5 px-3.5' : 'py-3.5 px-5' }} {{ $alignClass }}">
                                {{ $label }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif

            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 {{ $striped ? '[&>tr:nth-child(even)]:bg-slate-50/50 dark:[&>tr:nth-child(even)]:bg-slate-800/20' : '' }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
