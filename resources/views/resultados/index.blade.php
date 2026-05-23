@extends('layouts.public')

@section('title', 'Campeonatos')

@section('content')
    <div class="mb-6 sm:mb-10">
        <h1 class="page-title">
            Campeonatos
        </h1>
        <p class="page-subtitle max-w-xl">
            Acompanhe classificação, partidas e artilheiros. Clique em um campeonato para ver os detalhes.
        </p>
    </div>

    @if ($campeonatos->isEmpty())
        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-12 text-center">
            <div class="text-5xl mb-4">🏆</div>
            <h2 class="text-xl font-bold mb-2">Nenhum campeonato ainda</h2>
            <p class="text-brand-ice/50">Os resultados aparecerão aqui quando houver campeonatos cadastrados.</p>
        </div>
    @else
        <div class="grid-cards">
            @foreach ($campeonatos as $campeonato)
                @php
                    $formatoLabel = match ($campeonato->formato) {
                        'liga' => 'Liga',
                        'grupos' => 'Grupos',
                        'mata_mata' => 'Mata-mata',
                        default => ucfirst($campeonato->formato),
                    };
                @endphp

                <a
                    href="{{ route('resultados.show', $campeonato) }}"
                    class="group block bg-brand-surface border border-brand-ice/10 hover:border-brand-lilac/40 rounded-3xl p-6 transition hover:-translate-y-1"
                >
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <h2 class="text-xl font-bold group-hover:text-brand-orange-sand transition">
                            {{ $campeonato->nome }}
                        </h2>
                        <span class="shrink-0 px-3 py-1 rounded-full bg-brand-orange/20 border border-brand-orange/20 text-xs text-brand-orange-sand">
                            {{ $formatoLabel }}
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="bg-brand-ice/5 rounded-2xl p-3">
                            <p class="text-2xl font-black">{{ $campeonato->times_count }}</p>
                            <p class="text-xs text-brand-ice/50 mt-1">Times</p>
                        </div>
                        <div class="bg-brand-ice/5 rounded-2xl p-3">
                            <p class="text-2xl font-black">{{ $campeonato->partidas_count }}</p>
                            <p class="text-xs text-brand-ice/50 mt-1">Jogos</p>
                        </div>
                        <div class="bg-brand-ice/5 rounded-2xl p-3">
                            <p class="text-2xl font-black">{{ $campeonato->partidas_finalizadas_count }}</p>
                            <p class="text-xs text-brand-ice/50 mt-1">Finalizados</p>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-brand-lilac font-medium group-hover:underline">
                        Ver resultados →
                    </p>
                </a>
            @endforeach
        </div>
    @endif
@endsection
