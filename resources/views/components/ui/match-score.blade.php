@props([
    'casa',
    'fora',
    'golsCasa' => 0,
    'golsFora' => 0,
])

<div {{ $attributes->merge(['class' => 'match-scoreboard']) }}>
    <div class="match-team">
        @if ($casa->logo ?? null)
            <img src="{{ asset('storage/' . $casa->logo) }}" alt="" class="match-team__logo">
        @else
            <span class="match-team__logo flex items-center justify-center text-xl" aria-hidden="true">⚽</span>
        @endif
        <span class="match-team__name">{{ $casa->nome }}</span>
    </div>

    <div class="match-placar">
        <span class="match-placar__score">{{ $golsCasa }} × {{ $golsFora }}</span>
        @if (trim($slot ?? '') !== '')
            <div class="mt-2 flex justify-center">{{ $slot }}</div>
        @endif
    </div>

    <div class="match-team match-team--away">
        @if ($fora->logo ?? null)
            <img src="{{ asset('storage/' . $fora->logo) }}" alt="" class="match-team__logo">
        @else
            <span class="match-team__logo flex items-center justify-center text-xl" aria-hidden="true">⚽</span>
        @endif
        <span class="match-team__name">{{ $fora->nome }}</span>
    </div>
</div>
