<div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
    <h2 class="text-2xl font-bold mb-1">Artilheiros</h2>
    <p class="text-sm text-brand-ice/50 mb-6">Jogadores com mais gols no campeonato</p>

    @if ($artilheiros->isEmpty())
        <p class="text-center text-brand-ice/50 py-8">Nenhum gol registrado ainda.</p>
    @else
        <div class="space-y-3">
            @foreach ($artilheiros as $index => $jogador)
                <div class="flex items-center justify-between bg-brand-ice/5 rounded-2xl p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm
                            @if ($index === 0) bg-brand-orange-sand/20 text-brand-orange-sand
                            @elseif ($index === 1) bg-brand-urban/30 text-brand-ice/70
                            @elseif ($index === 2) bg-brand-purple/30 text-brand-orange-sand
                            @else bg-brand-ice/5 text-brand-ice/70
                            @endif
                        ">
                            {{ $index + 1 }}
                        </div>

                        <img src="{{ asset('storage/' . $jogador->time->logo) }}" class="w-10 h-10 object-contain" alt="">

                        <div>
                            <p class="font-semibold">{{ $jogador->nome }}</p>
                            <p class="text-xs text-brand-ice/50">{{ $jogador->time->nome }}</p>
                        </div>
                    </div>

                    <p class="text-2xl font-black text-brand-orange-sand">{{ $jogador->gols }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
