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
    <a href="{{ route('resultados.index') }}" class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice mb-4 sm:mb-6 transition min-h-[44px]">
        ← Voltar aos campeonatos
    </a>

    <div
        x-data="{ tab: @js(request('tab', 'resumo')) }"
        class="page"
    >
        <div class="page-hero">
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

                <h1 class="page-title">
                    {{ $campeonato->nome }}
                </h1>

                <div class="grid-stats mt-6 max-w-2xl">
                    <div class="stat-box">
                        <p class="stat-box__label">Times</p>
                        <p class="stat-box__value">{{ $campeonato->times->count() }}</p>
                    </div>
                    <div class="stat-box">
                        <p class="stat-box__label">Partidas</p>
                        <p class="stat-box__value">{{ $campeonato->partidas->count() }}</p>
                    </div>
                    <div class="stat-box">
                        <p class="stat-box__label">Finalizadas</p>
                        <p class="stat-box__value text-brand-orange-sand">{{ $campeonato->partidas->where('status', \App\Models\Partida::STATUS_FINALIZADA)->count() }}</p>
                    </div>
                    @if ($campeonato->formato === 'grupos')
                        <div class="stat-box">
                            <p class="stat-box__label">Grupos</p>
                            <p class="stat-box__value">{{ $campeonato->grupos->count() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="tabs-scroll" role="tablist">
            <button type="button" @click="tab = 'resumo'" :class="tab === 'resumo' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Resumo</button>
            @if ($campeonato->formato === 'liga' || $campeonato->formato === 'grupos')
                <button type="button" @click="tab = 'classificacao'" :class="tab === 'classificacao' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Classificação</button>
            @endif
            @if ($campeonato->formato === 'mata_mata')
                <button type="button" @click="tab = 'chave'" :class="tab === 'chave' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Chave</button>
            @endif
            <button type="button" @click="tab = 'partidas'" :class="tab === 'partidas' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Partidas</button>
            <button type="button" @click="tab = 'artilheiros'" :class="tab === 'artilheiros' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Artilheiros</button>
        </div>

        <div x-show="tab === 'resumo'" x-transition>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 section-card">
                    <h2 class="text-lg sm:text-xl font-bold mb-1">Últimas partidas</h2>
                    <p class="text-sm text-brand-ice/50 mb-4 sm:mb-6">Resultados recentes</p>

                    @php $ultimasPartidas = $campeonato->partidas->sortByDesc('id')->take(8); @endphp

                    @if ($ultimasPartidas->isEmpty())
                        <p class="text-center text-brand-ice/50 py-8">Nenhuma partida ainda.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($ultimasPartidas as $partida)
                                <div class="rounded-2xl bg-brand-ice/5 p-3 sm:p-4">
                                    <x-ui.match-score
                                        :casa="$partida->timeCasa"
                                        :fora="$partida->timeFora"
                                        :gols-casa="$partida->gols_casa"
                                        :gols-fora="$partida->gols_fora"
                                    >
                                        <x-partida-status-badge :partida="$partida" :pulse="true" />
                                    </x-ui.match-score>
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
                <div class="section-card p-0 sm:p-0 overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-brand-ice/10">
                        <h2 class="text-lg sm:text-2xl font-bold">Classificação</h2>
                        <p class="text-sm text-brand-ice/50 mt-1">Tabela do campeonato</p>
                    </div>
                    <x-ui.standings-table :rows="$classificacao ?? []" />
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

                            <div class="table-scroll md:!mx-0 md:!px-0">
                                <table class="w-full min-w-[480px]">
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
                <div class="bracket-scroll">
                    <div class="bracket-track">
                        @foreach ($partidasPorFase as $fase => $jogos)
                            <div class="bracket-column">
                                <h3 class="text-brand-ice/70 mb-4 uppercase tracking-widest text-xs sm:text-sm text-center">
                                    {{ $faseLabels[$fase] ?? ucfirst(str_replace('_', ' ', $fase)) }}
                                </h3>
                                <div class="flex flex-col gap-4 sm:gap-6">
                                    @foreach ($jogos as $jogo)
                                        <div class="bracket-match-card">
                                            <div class="bracket-team-row {{ $jogo->gols_casa > $jogo->gols_fora && $jogo->isFinalizada() ? 'bracket-team-row--winner' : '' }}">
                                                <span class="truncate flex-1">{{ $jogo->timeCasa->nome }}</span>
                                                <span class="tabular-nums font-bold">{{ $jogo->gols_casa ?? '—' }}</span>
                                            </div>
                                            <div class="bracket-team-row {{ $jogo->gols_fora > $jogo->gols_casa && $jogo->isFinalizada() ? 'bracket-team-row--winner' : '' }}">
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
        @endif

        <div x-show="tab === 'partidas'" x-transition>
            @include('resultados.partials.partidas')
        </div>

        <div x-show="tab === 'artilheiros'" x-transition>
            @include('resultados.partials.artilheiros')
        </div>
    </div>
@endsection
