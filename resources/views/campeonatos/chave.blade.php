@extends('layouts.app')

@section('content')

<div class="p-8 overflow-x-auto w-full">
    <h1 class="text-3xl font-bold text-white mb-10">
        🏆 {{ $campeonato->nome }}
    </h1>

    <div class="flex justify-start">

        <div class="flex gap-24 relative">

            @foreach($partidas as $fase => $jogos)

            <div class="flex flex-col items-center relative bracket-column">

                <h2 class="text-white/70 mb-6 uppercase tracking-widest text-sm">
                    {{ $fase }}
                </h2>

                <div class="flex flex-col justify-center gap-20 relative">

                    @foreach($jogos as $index => $jogo)
                    <div class="relative bracket-match">

                        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-xl p-4 w-64 shadow-xl transition duration-300 hover:scale-105 hover:shadow-2xl card-glow">
                            <div class="team {{ $jogo->gols_casa > $jogo->gols_fora ? 'winner' : ''}}">
                                <span>{{ $jogo->timeCasa->nome }}</span>
                                <span>{{ $jogo->gols_casa ?? '-' }}</span>
                            </div>

                            <div class="team {{ $jogo->gols_fora > $jogo->gols_casa ? 'winner' : ''}}">
                                <span>{{ $jogo->timeFora->nome }}</span>
                                <span>{{ $jogo->gols_fora ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="linha-horizontal right-[-48px] top-1/2 w-12"></div>

                        @if($loop->odd)
                        <div class="linha-vertical right-[-48px] top-full h-20"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

<!-- <style>
    .match-card {
        @apply bg-white/10 backdrop-blur-xl border border-white/10 rounded-xl p-4 w-64 shadow-xl transition
        duration-300
    }

    .match-card:hover {
        @apply scale-105 shadow-2xl
    }

    .team {
        @apply flex justify-between text-white text-sm py-1
    }

    .team.winner {
        @apply text-green-400 font-bold text-shadow: 0 0 8px #22c55e;
    }

    .connector-right {
        position: absolute;
        right: -48px;
        top: 50%;
        width: 48px;
        height: 2px;
        background: rgba(255, 255, 255, 0.2);
    }

    .bracket-column:nth-child(2) .bracket-match {
        margin-top: 60px;
    }

    .bracket-column:nth-child(3) .bracket-match {
        margin-top: 120px;
    }

    .bracket-column:nth-child(4) .bracket-match {
        margin-top: 240px;
    }
</style> -->
