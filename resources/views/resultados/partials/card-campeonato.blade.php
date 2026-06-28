@php
    $formatoLabel = match ($campeonato->formato) {
        'liga' => 'Pontos corridos',
        'grupos' => 'Fase de grupos',
        'mata_mata' => 'Mata-mata',
        default => ucfirst($campeonato->formato),
    };

    $emAndamento = $campeonato->partidas_count > 0
        && $campeonato->partidas_finalizadas_count < $campeonato->partidas_count;

    $themes = [
        [
            'badge' => 'bg-brand-orange/15 text-brand-orange-sand border-brand-orange/20',
            'shield' => 'bg-gradient-to-br from-brand-orange/80 to-brand-orange/40',
            'glow' => 'from-brand-orange/10',
        ],
        [
            'badge' => 'bg-brand-purple/20 text-brand-lilac border-brand-purple/30',
            'shield' => 'bg-gradient-to-br from-brand-purple/80 to-brand-purple/40',
            'glow' => 'from-brand-purple/10',
        ],
        [
            'badge' => 'bg-brand-blue/20 text-brand-blue-light border-brand-blue/30',
            'shield' => 'bg-gradient-to-br from-brand-blue/80 to-brand-blue/40',
            'glow' => 'from-brand-blue/10',
        ],
    ];
    $theme = $themes[$index % 3];
@endphp

<a
    href="{{ route('resultados.show', $campeonato) }}"
    class="group championship-card block"
>
    <div class="absolute inset-0 bg-gradient-to-br {{ $theme['glow'] }} to-transparent opacity-0 group-hover:opacity-100 transition pointer-events-none"></div>

    <span class="relative inline-flex self-start items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium border {{ $theme['badge'] }} mb-4">
        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-80"></span>
        {{ $emAndamento ? 'Em andamento' : 'Finalizado' }}
    </span>

    <div class="relative flex items-start gap-4 mb-5">
        <div class="championship-card__shield {{ $theme['shield'] }}">
            <svg class="w-7 h-7 text-white/90" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.5 2 6 4.5 6 8c0 3.5 2 6 4 8l2 6 2-6c2-2 4-4.5 4-8 0-3.5-2.5-6-6-6zm0 3a3 3 0 013 3c0 1.5-.8 2.8-1.8 3.8L12 17l-1.2-5.2C10 10.8 9 9.5 9 8a3 3 0 013-3z"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <h3 class="font-bold text-brand-ice group-hover:text-brand-orange-sand transition leading-snug">
                {{ $campeonato->nome }}
            </h3>
            <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[11px] bg-brand-ice/5 border border-brand-ice/10 text-brand-ice/60">
                {{ $formatoLabel }}
            </span>
        </div>
    </div>

    <div class="relative grid grid-cols-3 gap-2 sm:gap-3 text-center border-t border-brand-ice/10 pt-4">
        <div>
            <p class="text-xl sm:text-2xl font-black tabular-nums">{{ $campeonato->times_count }}</p>
            <p class="text-[10px] sm:text-xs text-brand-ice/50 mt-0.5">Times</p>
        </div>
        <div>
            <p class="text-xl sm:text-2xl font-black tabular-nums">{{ $campeonato->partidas_count }}</p>
            <p class="text-[10px] sm:text-xs text-brand-ice/50 mt-0.5">Jogos</p>
        </div>
        <div>
            <p class="text-xl sm:text-2xl font-black tabular-nums">{{ $campeonato->partidas_finalizadas_count }}</p>
            <p class="text-[10px] sm:text-xs text-brand-ice/50 mt-0.5">Finalizados</p>
        </div>
    </div>

    <p class="relative mt-4 text-sm text-brand-blue-light font-medium group-hover:underline">
        Ver detalhes →
    </p>
</a>
