<div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
    <h2 class="text-2xl font-bold mb-1">Partidas</h2>
    <p class="text-sm text-brand-ice/50 mb-6">Todos os jogos do campeonato</p>

    @if ($campeonato->partidas->isEmpty())
        <p class="text-center text-brand-ice/50 py-8">Nenhuma partida cadastrada.</p>
    @else
        <div class="space-y-4">
            @foreach ($campeonato->partidas as $partida)
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-brand-ice/5 border border-brand-ice/5 rounded-2xl p-4">
                    <div class="flex flex-wrap items-center gap-4 lg:gap-6">
                        <div class="flex items-center gap-3 min-w-[140px]">
                            <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-10 h-10 object-contain" alt="">
                            <span class="font-semibold">{{ $partida->timeCasa->nome }}</span>
                        </div>

                        <div class="text-2xl font-black">
                            {{ $partida->gols_casa }}
                            <span class="text-brand-ice/30 mx-1">x</span>
                            {{ $partida->gols_fora }}
                        </div>

                        <div class="flex items-center gap-3 min-w-[140px]">
                            <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-10 h-10 object-contain" alt="">
                            <span class="font-semibold">{{ $partida->timeFora->nome }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-sm">
                        <span class="text-brand-ice/50">📅 {{ $partida->data->format('d/m/Y H:i') }}</span>
                        @if ($partida->fase)
                            <span class="px-3 py-1 rounded-full bg-brand-ice/5 border border-brand-ice/10 capitalize">{{ str_replace('_', ' ', $partida->fase) }}</span>
                        @endif
                        @if ($partida->finalizada)
                            <span class="px-3 py-1 rounded-full bg-brand-orange/15 border border-brand-orange/25 text-brand-orange-sand text-xs font-semibold">Finalizada</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-brand-asphalt/50 border border-brand-blue-light/20 text-brand-blue-light text-xs font-semibold">Em andamento</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
