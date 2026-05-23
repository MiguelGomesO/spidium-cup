@extends('layouts.public')

@php
    $formatoLabel = match ($campeonato->formato) {
        'liga' => 'Liga',
        'grupos' => 'Grupos',
        'mata_mata' => 'Mata-mata',
        default => ucfirst($campeonato->formato),
    };

    $faseLabels = [
        'oitavas' => 'Oitavas',
        'quartas' => 'Quartas',
        'semi' => 'Semifinal',
        'final' => 'Final',
        'grupos' => 'Fase de grupos',
        'mata_mata' => 'Mata-mata',
        'mata' => 'Mata-mata',
    ];
@endphp

@section('title', $campeonato->nome)

@section('content')
    <a href="{{ route('resultados.index') }}" class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice mb-6 transition">
        ← Voltar aos campeonatos
    </a>

    <div
        x-data="{ tab: @js(request('tab', 'resumo')) }"
        class="space-y-8"
    >
        <div class="relative overflow-hidden rounded-3xl border border-brand-ice/10 bg-brand-surface p-8">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-orange/10 via-brand-purple/10"></div>

            <div class="relative z-10">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="px-4 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/70">
                        Campeonato
                    </span>
                    <span class="px-4 py-1 rounded-full bg-brand-orange/20 border border-brand-orange/20 text-xs text-brand-orange-sand">
                        {{ $formatoLabel }}
                    </span>
                </div>

                <h1 class="text-4xl lg:text-5xl font-black text-brand-gradient bg-clip-text text-transparent">
                    {{ $campeonato->nome }}
                </h1>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 max-w-2xl">
                    <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl p-4">
                        <p class="text-sm text-brand-ice/50">Times</p>
                        <p class="text-3xl font-black mt-1">{{ $campeonato->times->count() }}</p>
                    </div>
                    <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl p-4">
                        <p class="text-sm text-brand-ice/50">Partidas</p>
                        <p class="text-3xl font-black mt-1">{{ $campeonato->partidas->count() }}</p>
                    </div>
                    <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl p-4">
                        <p class="text-sm text-brand-ice/50">Finalizadas</p>
                        <p class="text-3xl font-black mt-1">{{ $campeonato->partidas->where('finalizada', true)->count() }}</p>
                    </div>
                    @if ($campeonato->formato === 'grupos')
                        <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl p-4">
                            <p class="text-sm text-brand-ice/50">Grupos</p>
                            <p class="text-3xl font-black mt-1">{{ $campeonato->grupos->count() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button
                @click="tab = 'resumo'"
                :class="tab === 'resumo' ? 'bg-brand-gradient text-brand-ice' : 'bg-brand-ice/5 text-brand-ice/70 hover:bg-brand-ice/10'"
                class="px-5 py-3 rounded-2xl transition font-medium"
            >
                Resumo
            </button>

            @if ($campeonato->formato === 'liga' || $campeonato->formato === 'grupos')
                <button
                    @click="tab = 'classificacao'"
                    :class="tab === 'classificacao' ? 'bg-brand-gradient text-brand-ice' : 'bg-brand-ice/5 text-brand-ice/70 hover:bg-brand-ice/10'"
                    class="px-5 py-3 rounded-2xl transition font-medium"
                >
                    Classificação
                </button>
            @endif

            @if ($campeonato->formato === 'mata_mata')
                <button
                    @click="tab = 'chave'"
                    :class="tab === 'chave' ? 'bg-brand-gradient text-brand-ice' : 'bg-brand-ice/5 text-brand-ice/70 hover:bg-brand-ice/10'"
                    class="px-5 py-3 rounded-2xl transition font-medium"
                >
                    Chave
                </button>
            @endif

            <button
                @click="tab = 'partidas'"
                :class="tab === 'partidas' ? 'bg-brand-gradient text-brand-ice' : 'bg-brand-ice/5 text-brand-ice/70 hover:bg-brand-ice/10'"
                class="px-5 py-3 rounded-2xl transition font-medium"
            >
                Partidas
            </button>

            <button
                @click="tab = 'artilheiros'"
                :class="tab === 'artilheiros' ? 'bg-brand-gradient text-brand-ice' : 'bg-brand-ice/5 text-brand-ice/70 hover:bg-brand-ice/10'"
                class="px-5 py-3 rounded-2xl transition font-medium"
            >
                Artilheiros
            </button>
        </div>

        <div x-show="tab === 'resumo'" x-transition>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                    <h2 class="text-xl font-bold mb-1">Últimas partidas</h2>
                    <p class="text-sm text-brand-ice/50 mb-6">Resultados recentes</p>

                    @php $ultimasPartidas = $campeonato->partidas->sortByDesc('data')->take(8); @endphp

                    @if ($ultimasPartidas->isEmpty())
                        <p class="text-center text-brand-ice/50 py-8">Nenhuma partida ainda.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($ultimasPartidas as $partida)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-brand-ice/5 rounded-2xl p-4">
                                    <div class="flex flex-wrap items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-8 h-8 object-contain" alt="">
                                            <span class="font-medium">{{ $partida->timeCasa->nome }}</span>
                                        </div>
                                        <span class="text-xl font-black">{{ $partida->gols_casa }} x {{ $partida->gols_fora }}</span>
                                        <div class="flex items-center gap-2">
                                            <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-8 h-8 object-contain" alt="">
                                            <span class="font-medium">{{ $partida->timeFora->nome }}</span>
                                        </div>
                                    </div>
                                    @if ($partida->finalizada)
                                        <span class="text-xs text-brand-orange-sand">Finalizada</span>
                                    @else
                                        <span class="text-xs text-brand-blue-light">Em andamento</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    @include('resultados.partials.artilheiros')
                </div>
            </div>
        </div>

        @if ($campeonato->formato === 'liga')
            <div x-show="tab === 'classificacao'" x-transition>
                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl overflow-hidden">
                    <div class="p-6 border-b border-brand-ice/10">
                        <h2 class="text-2xl font-bold">Classificação</h2>
                        <p class="text-sm text-brand-ice/50 mt-1">Tabela do campeonato</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[800px]">
                            <thead class="bg-brand-ice/5 text-brand-ice/50 text-xs uppercase">
                                <tr>
                                    <th class="text-left p-4">#</th>
                                    <th class="text-left p-4">Time</th>
                                    <th class="p-4 text-center">PTS</th>
                                    <th class="p-4 text-center">J</th>
                                    <th class="p-4 text-center">V</th>
                                    <th class="p-4 text-center">E</th>
                                    <th class="p-4 text-center">D</th>
                                    <th class="p-4 text-center">GP</th>
                                    <th class="p-4 text-center">GC</th>
                                    <th class="p-4 text-center">SG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classificacao ?? [] as $index => $item)
                                    <tr class="border-t border-brand-ice/5 hover:bg-brand-ice/5">
                                        <td class="p-4 font-bold text-brand-ice/70">{{ $index + 1 }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ asset('storage/' . $item['time']->logo) }}" class="w-10 h-10 object-contain" alt="">
                                                <span class="font-semibold">{{ $item['time']->nome }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center font-black text-brand-orange-sand">{{ $item['pontos'] }}</td>
                                        <td class="p-4 text-center">{{ $item['jogos'] }}</td>
                                        <td class="p-4 text-center text-brand-blue-light">{{ $item['vitorias'] }}</td>
                                        <td class="p-4 text-center text-brand-orange-sand">{{ $item['empates'] }}</td>
                                        <td class="p-4 text-center text-brand-orange">{{ $item['derrotas'] }}</td>
                                        <td class="p-4 text-center">{{ $item['gp'] }}</td>
                                        <td class="p-4 text-center">{{ $item['gc'] }}</td>
                                        <td class="p-4 text-center font-semibold @if($item['sg'] > 0) text-brand-blue-light @elseif($item['sg'] < 0) text-brand-orange-sand @else text-brand-ice/70 @endif">
                                            {{ $item['sg'] > 0 ? '+' : '' }}{{ $item['sg'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($campeonato->formato === 'grupos')
            <div x-show="tab === 'classificacao'" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($campeonato->grupos as $grupo)
                        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl overflow-hidden">
                            <div class="p-6 border-b border-brand-ice/10">
                                <h2 class="text-xl font-bold">{{ $grupo->nome }}</h2>
                                <p class="text-sm text-brand-ice/50">{{ $grupo->times->count() }} times</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-brand-ice/5 text-brand-ice/50 text-xs uppercase">
                                        <tr>
                                            <th class="text-left p-4">#</th>
                                            <th class="text-left p-4">Time</th>
                                            <th class="p-4 text-center">PTS</th>
                                            <th class="p-4 text-center">J</th>
                                            <th class="p-4 text-center">SG</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse (data_get($classificacao, $grupo->id, []) as $index => $item)
                                            <tr class="border-t border-brand-ice/5">
                                                <td class="p-4 font-bold">{{ (int) $index + 1 }}</td>
                                                <td class="p-4">
                                                    <div class="flex items-center gap-3">
                                                        <img src="{{ asset('storage/' . $item['time']->logo) }}" class="w-8 h-8 object-contain" alt="">
                                                        <span class="font-semibold">{{ $item['time']->nome }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-4 text-center font-black text-brand-orange-sand">{{ $item['pontos'] }}</td>
                                                <td class="p-4 text-center">{{ $item['jogos'] }}</td>
                                                <td class="p-4 text-center @if($item['sg'] > 0) text-brand-blue-light @elseif($item['sg'] < 0) text-brand-orange-sand @endif">
                                                    {{ $item['sg'] > 0 ? '+' : '' }}{{ $item['sg'] }}
                                                </td>
                                            </tr>
                                        @empty
                                            @foreach ($grupo->times as $time)
                                                <tr class="border-t border-brand-ice/5">
                                                    <td class="p-4 text-brand-ice/50">—</td>
                                                    <td class="p-4" colspan="4">
                                                        <div class="flex items-center gap-3">
                                                            <img src="{{ asset('storage/' . $time->logo) }}" class="w-8 h-8 object-contain" alt="">
                                                            <span>{{ $time->nome }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($campeonato->formato === 'mata_mata' && $partidasPorFase)
            <div x-show="tab === 'chave'" x-transition>
                <div class="overflow-x-auto pb-4">
                    <div class="flex gap-8 min-w-max">
                        @foreach ($partidasPorFase as $fase => $jogos)
                            <div class="flex flex-col items-center">
                                <h3 class="text-brand-ice/70 mb-4 uppercase tracking-widest text-sm">
                                    {{ $faseLabels[$fase] ?? ucfirst(str_replace('_', ' ', $fase)) }}
                                </h3>

                                <div class="flex flex-col gap-6">
                                    @foreach ($jogos as $jogo)
                                        <div class="bg-brand-ice/10 border border-brand-ice/10 rounded-xl p-4 w-64">
                                            <div class="flex justify-between items-center gap-2 py-1 @if($jogo->gols_casa > $jogo->gols_fora && $jogo->finalizada) text-brand-blue-light font-bold @endif">
                                                <span class="truncate">{{ $jogo->timeCasa->nome }}</span>
                                                <span>{{ $jogo->gols_casa ?? '-' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center gap-2 py-1 @if($jogo->gols_fora > $jogo->gols_casa && $jogo->finalizada) text-brand-blue-light font-bold @endif">
                                                <span class="truncate">{{ $jogo->timeFora->nome }}</span>
                                                <span>{{ $jogo->gols_fora ?? '-' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div x-show="tab === 'partidas'" x-transition>
            @include('resultados.partials.partidas')
        </div>

        <div x-show="tab === 'artilheiros'" x-transition>
            @include('resultados.partials.artilheiros')
        </div>
    </div>
@endsection
