<a
    href="{{ route('partidas.show', $partida->momentos_count > 0 ? ['partida' => $partida, 'tab' => 'momentos'] : $partida) }}"
    class="block rounded-2xl bg-brand-ice/5 border border-brand-ice/10 p-4 hover:border-brand-lilac/30 hover:bg-brand-ice/[0.07] transition group"
>
    <x-ui.match-score
        :casa="$partida->timeCasa"
        :fora="$partida->timeFora"
        :gols-casa="$partida->gols_casa"
        :gols-fora="$partida->gols_fora"
    >
        <x-partida-status-badge :partida="$partida" :pulse="true" />
    </x-ui.match-score>

    <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-center sm:text-left">
        <p class="text-xs text-brand-ice/50">
            {{ $partida->campeonato->nome ?? 'Amistoso' }}
            @if ($partida->fase)
                · <span class="capitalize">{{ str_replace('_', ' ', $partida->fase) }}</span>
            @endif
        </p>
        <span class="text-xs font-medium text-brand-orange-sand group-hover:underline shrink-0">
            @if ($partida->momentos_count > 0)
                Ver momentos ({{ $partida->momentos_count }}) →
            @else
                Ver súmula →
            @endif
        </span>
    </div>
</a>
