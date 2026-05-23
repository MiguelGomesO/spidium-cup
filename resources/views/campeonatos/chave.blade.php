@extends('layouts.app')

@section('page-title', 'Chave')
@section('title', 'Chave — ' . $campeonato->nome)

@section('content')

<div class="page">
    <a href="{{ route('campeonatos.edit', $campeonato) }}" class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice transition min-h-[44px]">
        ← Voltar
    </a>

    <div class="section-card">
        <h1 class="page-title">🏆 {{ $campeonato->nome }}</h1>
        <p class="page-subtitle">Chave eliminatória — arraste para o lado no celular</p>
    </div>

    <div class="bracket-scroll">
        <div class="bracket-track">
            @foreach ($partidas as $fase => $jogos)
                <div class="bracket-column">
                    <h2 class="text-brand-ice/80 mb-4 uppercase tracking-widest text-xs sm:text-sm text-center">
                        {{ $fase }}
                    </h2>

                    <div class="flex flex-col gap-6 sm:gap-8">
                        @foreach ($jogos as $jogo)
                            <div class="bracket-match-card card-hover">
                                <div class="bracket-team-row {{ $jogo->gols_casa > $jogo->gols_fora ? 'bracket-team-row--winner' : '' }}">
                                    <span class="truncate flex-1">{{ $jogo->timeCasa->nome }}</span>
                                    <span class="tabular-nums font-bold">{{ $jogo->gols_casa ?? '—' }}</span>
                                </div>
                                <div class="bracket-team-row {{ $jogo->gols_fora > $jogo->gols_casa ? 'bracket-team-row--winner' : '' }}">
                                    <span class="truncate flex-1">{{ $jogo->timeFora->nome }}</span>
                                    <span class="tabular-nums font-bold">{{ $jogo->gols_fora ?? '—' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
