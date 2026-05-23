@props(['rows' => [], 'mobileCards' => true])

@php
    $renderRow = function ($item, $index) {
        $time = $item['time'];
        return [
            'index' => $index,
            'item' => $item,
            'time' => $time,
        ];
    };
@endphp

{{-- Mobile: cards --}}
@if ($mobileCards)
    <div class="md:hidden space-y-2">
        @foreach ($rows as $index => $item)
            @php $time = $item['time']; @endphp
            <div class="standing-card">
                <span class="standing-card__pos">{{ $index + 1 }}</span>
                @if ($time->logo ?? null)
                    <img src="{{ asset('storage/' . $time->logo) }}" class="w-9 h-9 object-contain shrink-0" alt="">
                @endif
                <div class="min-w-0 flex-1">
                    <p class="font-semibold truncate">{{ $time->nome }}</p>
                    <p class="text-xs text-brand-urban mt-0.5">
                        {{ $item['jogos'] }}J · {{ $item['vitorias'] }}V · {{ $item['empates'] }}E · {{ $item['derrotas'] }}D
                    </p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-lg font-black text-brand-orange-sand">{{ $item['pontos'] }}</p>
                    <p class="text-[10px] text-brand-urban uppercase">pts</p>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Desktop: tabela --}}
<div class="{{ $mobileCards ? 'hidden md:block' : '' }} table-wrap">
    <div class="table-scroll table-scroll--wide">
        <table>
            <thead class="bg-brand-ice/5 text-brand-ice/50 text-xs uppercase">
                <tr>
                    <th class="text-left p-3 sm:p-4 w-10">#</th>
                    <th class="text-left p-3 sm:p-4">Time</th>
                    <th class="p-3 sm:p-4 text-center">PTS</th>
                    <th class="p-3 sm:p-4 text-center">J</th>
                    <th class="p-3 sm:p-4 text-center">V</th>
                    <th class="p-3 sm:p-4 text-center">E</th>
                    <th class="p-3 sm:p-4 text-center">D</th>
                    <th class="p-3 sm:p-4 text-center">GP</th>
                    <th class="p-3 sm:p-4 text-center">GC</th>
                    <th class="p-3 sm:p-4 text-center">SG</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $index => $item)
                    @php $time = $item['time']; @endphp
                    <tr class="border-t border-brand-ice/5 hover:bg-brand-ice/5">
                        <td class="p-3 sm:p-4 font-bold text-brand-ice/70">{{ $index + 1 }}</td>
                        <td class="p-3 sm:p-4">
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                @if ($time->logo ?? null)
                                    <img src="{{ asset('storage/' . $time->logo) }}" class="w-8 h-8 sm:w-10 sm:h-10 object-contain shrink-0" alt="">
                                @endif
                                <span class="font-semibold truncate">{{ $time->nome }}</span>
                            </div>
                        </td>
                        <td class="p-3 sm:p-4 text-center font-black text-brand-orange-sand">{{ $item['pontos'] }}</td>
                        <td class="p-3 sm:p-4 text-center">{{ $item['jogos'] }}</td>
                        <td class="p-3 sm:p-4 text-center text-brand-blue-light">{{ $item['vitorias'] }}</td>
                        <td class="p-3 sm:p-4 text-center text-brand-orange-sand">{{ $item['empates'] }}</td>
                        <td class="p-3 sm:p-4 text-center text-brand-orange">{{ $item['derrotas'] }}</td>
                        <td class="p-3 sm:p-4 text-center">{{ $item['gp'] }}</td>
                        <td class="p-3 sm:p-4 text-center">{{ $item['gc'] }}</td>
                        <td class="p-3 sm:p-4 text-center font-semibold @if($item['sg'] > 0) text-brand-blue-light @elseif($item['sg'] < 0) text-brand-orange-sand @else text-brand-ice/70 @endif">
                            {{ $item['sg'] > 0 ? '+' : '' }}{{ $item['sg'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
