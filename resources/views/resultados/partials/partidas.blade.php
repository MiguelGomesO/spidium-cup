<div class="section-card">
    <h2 class="text-lg sm:text-xl font-bold mb-1">Partidas</h2>
    <p class="text-sm text-brand-ice/50 mb-4 sm:mb-6">Todos os jogos do campeonato</p>

    @if ($campeonato->partidas->isEmpty())
        <p class="text-center text-brand-ice/50 py-8">Nenhuma partida cadastrada.</p>
    @else
        <div class="space-y-3">
            @foreach ($campeonato->partidas as $partida)
                <div class="rounded-2xl bg-brand-ice/5 border border-brand-ice/5 p-4">
                    <x-ui.match-score
                        :casa="$partida->timeCasa"
                        :fora="$partida->timeFora"
                        :gols-casa="$partida->gols_casa"
                        :gols-fora="$partida->gols_fora"
                    >
                        <x-partida-status-badge :partida="$partida" :pulse="true" />
                    </x-ui.match-score>

                    @if ($partida->fase)
                        <p class="text-center text-xs text-brand-urban mt-3 capitalize">{{ str_replace('_', ' ', $partida->fase) }}</p>
                    @endif

                    @if ($partida->isFinalizada())
                        <div class="mt-4 text-center">
                            <a href="{{ route('partidas.show', $partida) }}" class="inline-flex items-center justify-center min-h-[44px] px-4 rounded-xl text-sm font-medium bg-brand-orange/15 border border-brand-orange/30 text-brand-orange-sand hover:bg-brand-orange/25 transition">
                                🏆 Votar no MVP
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
