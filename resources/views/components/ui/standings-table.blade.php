@props([
    'rows' => [],
    'mobileCards' => true,
    'compact' => false,
    'qualifiers' => 0,
    'emptyMessage' => 'Nenhum jogo disputado ainda.',
])

@php
    $positionClass = function (int $index): string {
        return match ($index) {
            0 => 'standings-pos--gold',
            1 => 'standings-pos--silver',
            2 => 'standings-pos--bronze',
            default => 'standings-pos--default',
        };
    };

    $sgClass = function (int $sg): string {
        if ($sg > 0) {
            return 'standings-stat--positive';
        }
        if ($sg < 0) {
            return 'standings-stat--negative';
        }

        return 'standings-stat--neutral';
    };
@endphp

@if (count($rows) === 0)
    <div class="standings-empty">
        <div class="standings-empty__icon">📊</div>
        <p class="standings-empty__text">{{ $emptyMessage }}</p>
    </div>
@else
    @if ($mobileCards)
        <div class="standings-mobile md:hidden">
            @foreach ($rows as $index => $item)
                @php
                    $time = $item['time'];
                    $qualified = $qualifiers > 0 && $index < $qualifiers;
                @endphp
                <article @class([
                    'standings-card',
                    'standings-card--qualified' => $qualified,
                    'standings-card--podium' => $index < 3,
                ])>
                    <span class="standings-pos {{ $positionClass($index) }}">{{ $index + 1 }}</span>

                    @if ($time->logo ?? null)
                        <img src="{{ asset('storage/' . $time->logo) }}" class="standings-card__logo" alt="">
                    @else
                        <div class="standings-card__logo standings-card__logo--placeholder">⚽</div>
                    @endif

                    <div class="standings-card__body">
                        <p class="standings-card__team">{{ $time->nome }}</p>
                        <div class="standings-card__stats">
                            <span>{{ $item['jogos'] }}J</span>
                            @if ($compact)
                                <span class="{{ $sgClass($item['sg']) }}">
                                    SG {{ $item['sg'] > 0 ? '+' : '' }}{{ $item['sg'] }}
                                </span>
                            @else
                                <span class="standings-stat--win">{{ $item['vitorias'] }}V</span>
                                <span class="standings-stat--draw">{{ $item['empates'] }}E</span>
                                <span class="standings-stat--loss">{{ $item['derrotas'] }}D</span>
                            @endif
                        </div>
                    </div>

                    <div class="standings-pts">
                        <span class="standings-pts__value">{{ $item['pontos'] }}</span>
                        <span class="standings-pts__label">pts</span>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    <div @class(['standings-table-wrap', 'hidden md:block' => $mobileCards])>
        <div class="standings-table-scroll">
            <table class="standings-table">
                <thead>
                    <tr>
                        <th class="standings-table__th standings-table__th--pos">#</th>
                        <th class="standings-table__th standings-table__th--team">Time</th>
                        <th class="standings-table__th standings-table__th--pts">PTS</th>
                        <th class="standings-table__th">J</th>
                        @unless ($compact)
                            <th class="standings-table__th">V</th>
                            <th class="standings-table__th">E</th>
                            <th class="standings-table__th">D</th>
                            <th class="standings-table__th">GP</th>
                            <th class="standings-table__th">GC</th>
                        @endunless
                        <th class="standings-table__th">SG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $index => $item)
                        @php
                            $time = $item['time'];
                            $qualified = $qualifiers > 0 && $index < $qualifiers;
                        @endphp
                        <tr @class([
                            'standings-row',
                            'standings-row--qualified' => $qualified,
                            'standings-row--podium' => $index < 3,
                        ])>
                            <td class="standings-table__td standings-table__td--pos">
                                <span class="standings-pos {{ $positionClass($index) }}">{{ $index + 1 }}</span>
                            </td>
                            <td class="standings-table__td standings-table__td--team">
                                <div class="standings-team">
                                    @if ($time->logo ?? null)
                                        <img src="{{ asset('storage/' . $time->logo) }}" class="standings-team__logo" alt="">
                                    @else
                                        <div class="standings-team__logo standings-team__logo--placeholder">⚽</div>
                                    @endif
                                    <span class="standings-team__name">{{ $time->nome }}</span>
                                </div>
                            </td>
                            <td class="standings-table__td standings-table__td--pts">
                                <span class="standings-pts standings-pts--inline">
                                    <span class="standings-pts__value">{{ $item['pontos'] }}</span>
                                </span>
                            </td>
                            <td class="standings-table__td standings-table__td--stat">{{ $item['jogos'] }}</td>
                            @unless ($compact)
                                <td class="standings-table__td standings-table__td--stat standings-stat--win">{{ $item['vitorias'] }}</td>
                                <td class="standings-table__td standings-table__td--stat standings-stat--draw">{{ $item['empates'] }}</td>
                                <td class="standings-table__td standings-table__td--stat standings-stat--loss">{{ $item['derrotas'] }}</td>
                                <td class="standings-table__td standings-table__td--stat">{{ $item['gp'] }}</td>
                                <td class="standings-table__td standings-table__td--stat">{{ $item['gc'] }}</td>
                            @endunless
                            <td class="standings-table__td standings-table__td--stat {{ $sgClass($item['sg']) }}">
                                {{ $item['sg'] > 0 ? '+' : '' }}{{ $item['sg'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($qualifiers > 0)
            <p class="standings-legend">
                <span class="standings-legend__dot"></span>
                Zona de classificação ({{ $qualifiers }} {{ $qualifiers === 1 ? 'time' : 'times' }})
            </p>
        @endif
    </div>
@endif
