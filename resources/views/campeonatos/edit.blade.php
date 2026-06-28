@extends('layouts.app')

@section('page-title', $campeonato->nome)
@section('title', $campeonato->nome)

@section('content')

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => showToast(@json(session('success')), 'success'));
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => showToast(@json($errors->first()), 'error'));
    </script>
@endif

<div x-data="{ tab: @js(request('tab', 'visao-geral')) }" class="page">
    <div class="page-hero">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-orange/10 via-brand-purple/10"></div>

        <div class="absolute -top-20 -right-20 w-72 h-72 bg-brand-purple/20 blur-3xl rounded-full"></div>

        <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-brand-orange/20 blur-3xl rounded-full"></div>

        <div class="relative z-20 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-4 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/70">
                        Campeonato
                    </span>

                    <span class="px-4 py-1 rounded-full bg-brand-orange/20 border border-brand-orange/20 text-xs text-brand-orange-sand capitalize">
                        {{ $campeonato->formato }}
                    </span>
                </div>

                <h1 class="text-4xl lg:text-6xl font-black text-brand-gradient bg-clip-text text-transparent">
                    {{ $campeonato->nome }}
                </h1>

                <p class="mt-4 text-brand-ice/60 max-w-2xl leading-relaxed">
                    Central de gerenciamento do campeonato
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full xl:w-[420px]">
                <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl p-5">
                    <p class="text-sm text-brand-ice/50">
                        Times
                    </p>

                    <h2 class="text-4xl font-black mt-2">
                        {{ $campeonato->times->count() }}
                    </h2>
                </div>

                <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl p-5">
                    <p class="text-sm text-brand-ice/50">
                        Grupos
                    </p>

                    <h2 class="text-4xl font-black mt-2">
                        {{ $campeonato->grupos->count() }}
                    </h2>
                </div>

                <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl p-5">
                    <p class="text-sm text-brand-ice/50">
                        Partidas
                    </p>

                    <h2 class="text-4xl font-black mt-2">
                        {{ $campeonato->partidas->count() }}
                    </h2>
                </div>

                <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl p-5">
                    <p class="text-sm text-brand-ice/50">
                        Finalizadas
                    </p>

                    <h2 class="text-4xl font-black mt-2">
                        {{ $campeonato->partidas->where('status', \App\Models\Partida::STATUS_FINALIZADA)->count() }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="tabs-scroll" role="tablist">
        <button type="button" @click="tab = 'visao-geral'" :class="tab === 'visao-geral' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Visão geral</button>
        @if ($campeonato->formato === 'grupos')
            <button type="button" @click="tab = 'grupos'" :class="tab === 'grupos' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Grupos</button>
        @endif
        @if ($campeonato->formato === 'mata_mata')
            <button type="button" @click="tab = 'mata-mata'" :class="tab === 'mata-mata' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Mata-mata</button>
        @endif
        <button type="button" @click="tab = 'partidas'" :class="tab === 'partidas' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Partidas</button>
        @if ($campeonato->formato === 'liga')
            <button type="button" @click="tab = 'classificacao'" :class="tab === 'classificacao' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Classificação</button>
        @endif
        <button type="button" @click="tab = 'estatisticas'" :class="tab === 'estatisticas' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Estatísticas</button>
    </div>

    <div x-show="tab === 'visao-geral'" x-transition>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold">⚽ Últimas Partidas</h2>

                            <p class="text-sm text-brand-ice/50 mt-1">Jogos recentes do campeonato</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach ($campeonato->partidas->where('status', \App\Models\Partida::STATUS_FINALIZADA)->take(5) as $partida)
                            <a href="{{ route('partidas.show', $partida) }}" class="flex items-center justify-between bg-brand-ice/5 hover:bg-brand-ice/10 transition rounded-2xl p-4 border border-brand-ice/5">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-10 h-10 object-contain">

                                        <span class="font-medium">{{ $partida->timeCasa->nome }}</span>
                                    </div>

                                    <span class="text-brand-ice/30">
                                        vs
                                    </span>

                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-10 h-10 object-contain">

                                        <span class="font-medium">{{ $partida->timeFora->nome }}</span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-xl font-black">
                                        {{ $partida->gols_casa }} <span class="text-brand-ice/30">-</span> {{ $partida->gols_fora }}
                                    </div>

                                    <span class="text-xs text-brand-orange-sand">
                                        Finalizada
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                    <h2 class="text-xl font-bold mb-6">
                        🔥 Artilheiros
                    </h2>

                    <div class="space-y-4">
                        @foreach ($artilheiros as $jogador)
                            <div class="flex items-center justify-between bg-brand-ice/5 rounded-2xl p-4 hover:bg-brand-ice/10">
                                <div>
                                    <p class="font-semibold">
                                        {{ $jogador->nome }}
                                    </p>

                                    <p class="text-xs text-brand-ice/50">
                                        {{ $jogador->time->nome }}
                                    </p>
                                </div>

                                <span class="font-bold text-brand-orange-sand">
                                    ⚽ {{ $jogador->gols }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($campeonato->formato === 'grupos')
        <div x-show="tab === 'grupos'" x-transition>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
                <div class="xl:col-span-2 bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                    <h2 class="text-xl font-bold mb-2">Gerenciar Grupos</h2>
                    <p class="text-sm text-brand-ice/50 mb-6">
                        Crie grupos manualmente ou distribua os times automaticamente.
                    </p>

                    <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                        @if ($campeonato->times->count() >= 4 && $campeonato->times->count() < $campeonato->qtd_times)
                            <form
                                method="POST"
                                action="{{ route('campeonatos.gerar-grupos', $campeonato) }}"
                                onsubmit="return confirm('Isso apagará os grupos atuais e redistribuirá todos os times. Continuar?')"
                            >
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition">
                                    🎲 Gerar Automaticamente
                                </button>
                            </form>
                        @elseif ($campeonato->times->count() < 4)
                            <p class="text-sm text-brand-orange-sand/80 py-3">
                                Adicione pelo menos 4 times ao campeonato para gerar grupos automaticamente.
                            </p>
                        @endif

                        @if ($campeonato->grupos->isNotEmpty() && $campeonato->grupos->every(fn ($g) => $g->times->count() >= 2))
                            <form method="POST" action="{{ route('campeonatos.gerar-partidas-grupos', $campeonato) }}" onsubmit="return confirm('Isso apagará todas as partidas atuais e gerará os jogos da fase de grupos. Continuar?')">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-brand-ice/10 border border-brand-ice/10 hover:bg-brand-ice/15 transition font-medium">
                                    ⚽ Gerar Partidas dos Grupos
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                    <h2 class="text-lg font-bold mb-4">Criar Grupo Manual</h2>

                    <form method="POST" action="{{ route('campeonatos.grupos.store', $campeonato) }}" class="space-y-3">
                        @csrf

                        <input
                            type="text"
                            name="nome"
                            required
                            maxlength="255"
                            placeholder="Ex: Grupo A"
                            value="Grupo {{ chr(65 + min($campeonato->grupos->count(), 25)) }}"
                            class="w-full p-3 rounded-2xl bg-brand-ice/5 border border-brand-ice/10 focus:border-brand-purple/50 focus:outline-none"
                        >

                        <button type="submit" class="w-full py-3 rounded-2xl bg-brand-ice/10 border border-brand-ice/10 hover:bg-brand-ice/15 transition font-medium">
                            + Adicionar Grupo
                        </button>
                    </form>
                </div>
            </div>

            @if ($timesSemGrupo->isNotEmpty())
                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">
                        Times sem grupo ({{ $timesSemGrupo->count() }})
                    </h3>

                    <div class="flex flex-wrap gap-3">
                        @foreach ($timesSemGrupo as $time)
                            <div class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-brand-ice/5 border border-brand-ice/10">
                                <img src="{{ asset('storage/' . $time->logo) }}" class="w-8 h-8 object-contain" alt="">
                                <span class="text-sm font-medium">{{ $time->nome }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($campeonato->grupos->isEmpty())
                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-12 text-center">
                    <div class="text-5xl mb-4">👥</div>
                    <h3 class="text-xl font-bold mb-2">Nenhum grupo criado</h3>
                    <p class="text-brand-ice/50 max-w-md mx-auto">
                        Crie grupos manualmente ou use a geração automática para distribuir os {{ $campeonato->times->count() }} times do campeonato.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($campeonato->grupos as $grupo)
                        <div class="bg-brand-surface border border-brand-ice/10 hover:border-brand-ice/20 rounded-3xl overflow-hidden transition hover:-translate-y-1">
                            <div class="p-6 border-b border-brand-ice/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <h2 class="text-2xl font-bold">
                                        {{ $grupo->nome }}
                                    </h2>

                                    <p class="text-sm text-brand-ice/50 mt-1">
                                        {{ $grupo->times->count() }} times
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if ($grupo->times->isEmpty())
                                        <form method="POST" action="{{ route('grupos.destroy', $grupo) }}" onsubmit="return confirm('Excluir este grupo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-brand-orange/15 border border-brand-orange/25 text-brand-orange-sand text-sm hover:bg-brand-orange/25 transition">
                                                Excluir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            @if ($timesSemGrupo->isNotEmpty())
                                <div class="px-6 py-4 border-b border-brand-ice/10 bg-brand-ice/[0.02]">
                                    <form method="POST" action="{{ route('grupos.times.store', $grupo) }}" class="flex flex-col sm:flex-row gap-3">
                                        @csrf
                                        <select name="time_id" required class="flex-1 p-3 rounded-2xl bg-brand-black text-brand-ice border border-brand-ice/10 focus:outline-none focus:border-brand-purple/50">
                                            <option value="" class="bg-brand-black text-brand-ice">Adicionar time ao grupo...</option>
                                            @foreach ($timesSemGrupo as $time)
                                                <option value="{{ $time->id }}" class="bg-brand-black text-brand-ice">{{ $time->nome }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-5 py-3 rounded-2xl bg-brand-gradient opacity-90 font-medium hover:opacity-90 transition whitespace-nowrap">
                                            Adicionar
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-brand-ice/5 text-brand-ice/50 text-xs uppercase">
                                        <tr>
                                            <th class="text-left p-4">#</th>
                                            <th class="text-left p-4">Time</th>
                                            <th class="text-center p-4">PTS</th>
                                            <th class="text-center p-4">J</th>
                                            <th class="text-center p-4">SG</th>
                                            <th class="text-center p-4 w-16"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse (data_get($classificacao, $grupo->id, []) as $index => $item)
                                            <tr class="border-t border-brand-ice/5 hover:bg-brand-ice/5 transition">
                                                <td class="p-4">
                                                    <div class="w-8 h-8 rounded-full
                                                        @if ((int) $index < 2)
                                                            bg-brand-asphalt/40 text-brand-blue-light
                                                        @else
                                                            bg-brand-ice/5 text-brand-ice/60
                                                        @endif
                                                        flex items-center justify-center text-sm font-bold
                                                    ">
                                                        {{ (int) $index + 1 }}
                                                    </div>
                                                </td>

                                                <td class="p-4">
                                                    <div class="flex items-center gap-3">
                                                        <img class="w-10 h-10 object-contain" src="{{ asset('storage/' . $item['time']->logo) }}" alt="">
                                                        <span class="font-semibold">{{ $item['time']->nome }}</span>
                                                    </div>
                                                </td>

                                                <td class="p-4 text-center font-black text-brand-orange-sand">
                                                    {{ $item['pontos'] }}
                                                </td>

                                                <td class="p-4 text-center text-brand-ice/80">
                                                    {{ $item['jogos'] }}
                                                </td>

                                                <td class="p-4 text-center font-semibold
                                                    @if ($item['sg'] > 0)
                                                        text-brand-blue-light
                                                    @elseif($item['sg'] < 0)
                                                        text-brand-orange-sand
                                                    @else
                                                        text-brand-ice/70
                                                    @endif
                                                ">
                                                    {{ $item['sg'] > 0 ? '+' : '' }}{{ $item['sg'] }}
                                                </td>

                                                <td class="p-4 text-center">
                                                    <form method="POST" action="{{ route('grupos.times.destroy', [$grupo, $item['time']]) }}" onsubmit="return confirm('Remover {{ $item['time']->nome }} deste grupo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-brand-orange-sand/70 hover:text-brand-orange-sand text-sm" title="Remover do grupo">
                                                            ✕
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            @foreach ($grupo->times as $time)
                                                <tr class="border-t border-brand-ice/5">
                                                    <td class="p-4 text-brand-ice/50">—</td>
                                                    <td class="p-4" colspan="4">
                                                        <div class="flex items-center gap-3">
                                                            <img class="w-10 h-10 object-contain" src="{{ asset('storage/' . $time->logo) }}" alt="">
                                                            <span class="font-semibold">{{ $time->nome }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-4 text-center">
                                                        <form method="POST" action="{{ route('grupos.times.destroy', [$grupo, $time]) }}" onsubmit="return confirm('Remover {{ $time->nome }} deste grupo?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-brand-orange-sand/70 hover:text-brand-orange-sand text-sm" title="Remover do grupo">✕</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($grupo->times->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="p-6 text-center text-brand-ice/50 text-sm">
                                                        Nenhum time neste grupo. Adicione times acima.
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @if ($campeonato->formato === 'liga')
        <div x-show="tab === 'classificacao'" x-transition>
            <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl overflow-hidden">
                <div class="p-6 border-b border-brand-ice/10 bg-gradient-to-r from-brand-purple/10 to-transparent">
                    <h2 class="text-2xl font-bold">Classificação</h2>
                    <p class="text-sm text-brand-ice/50 mt-1">Tabela oficial do campeonato</p>
                </div>
                <x-ui.standings-table :rows="$classificacao" :qualifiers="4" />
            </div>
        </div>
    @endif

    <div x-show="tab === 'partidas'" x-transition>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            <div class="xl:col-span-2 bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                <h2 class="text-xl font-bold mb-2">Nova Partida</h2>
                <p class="text-sm text-brand-ice/50 mb-6">
                    Crie confrontos manualmente entre os times do campeonato.
                </p>

                @if ($campeonato->times->count() < 2)
                    <p class="text-sm text-brand-orange-sand/80">
                        Adicione pelo menos 2 times ao campeonato para criar partidas.
                    </p>
                @else
                    <form method="POST" action="{{ route('campeonatos.partidas.store', $campeonato) }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-brand-ice/60 mb-2 block">Time da casa</label>
                                <select name="time_casa_id" required class="w-full p-3 rounded-2xl bg-brand-black text-brand-ice border border-brand-ice/10 focus:outline-none focus:border-brand-purple/50">
                                    <option value="" class="bg-brand-black text-brand-ice">Selecione...</option>
                                    @foreach ($campeonato->times as $time)
                                        <option value="{{ $time->id }}" class="bg-brand-black text-brand-ice" @selected(old('time_casa_id') == $time->id)>{{ $time->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm text-brand-ice/60 mb-2 block">Time visitante</label>
                                <select name="time_fora_id" required class="w-full p-3 rounded-2xl bg-brand-black text-brand-ice border border-brand-ice/10 focus:outline-none focus:border-brand-purple/50">
                                    <option value="" class="bg-brand-black text-brand-ice">Selecione...</option>
                                    @foreach ($campeonato->times as $time)
                                        <option value="{{ $time->id }}" class="bg-brand-black text-brand-ice" @selected(old('time_fora_id') == $time->id)>{{ $time->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-partidas.status-select
                                    :selected="old('status', \App\Models\Partida::STATUS_EM_ANDAMENTO)"
                                    id="campeonato-partida-status"
                                />
                            </div>

                            @if ($campeonato->formato === 'mata_mata')
                                <div>
                                    <label class="text-sm text-brand-ice/60 mb-2 block">Fase</label>
                                    <select name="fase" required class="w-full p-3 rounded-2xl bg-brand-black text-brand-ice border border-brand-ice/10 focus:outline-none focus:border-brand-purple/50">
                                        <option value="" class="bg-brand-black text-brand-ice">Selecione...</option>
                                        <option value="oitavas" class="bg-brand-black text-brand-ice" @selected(old('fase') === 'oitavas')>Oitavas</option>
                                        <option value="quartas" class="bg-brand-black text-brand-ice" @selected(old('fase') === 'quartas')>Quartas</option>
                                        <option value="semi" class="bg-brand-black text-brand-ice" @selected(old('fase') === 'semi')>Semifinal</option>
                                        <option value="final" class="bg-brand-black text-brand-ice" @selected(old('fase') === 'final')>Final</option>
                                    </select>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="px-6 py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition">
                            + Criar Partida
                        </button>
                    </form>
                @endif
            </div>

            <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                <h2 class="text-lg font-bold mb-4">Resumo</h2>
                <div class="space-y-3 text-sm">
                    <p class="text-brand-ice/60">
                        <span class="text-brand-ice font-semibold">{{ $campeonato->partidas->count() }}</span> partidas cadastradas
                    </p>
                    <p class="text-brand-ice/60">
                        <span class="text-brand-ice font-semibold">{{ $campeonato->partidas->where('status', \App\Models\Partida::STATUS_FINALIZADA)->count() }}</span> finalizadas
                    </p>
                    <p class="text-brand-ice/60">
                        <span class="text-brand-ice font-semibold">{{ $campeonato->times->count() }}</span> times no campeonato
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold">⚽ Partidas</h2>
                    <p class="text-sm text-brand-ice/50 mt-1">Jogos do campeonato</p>
                </div>
            </div>

            @if ($campeonato->partidas->isEmpty())
                <div class="py-12 text-center">
                    <div class="text-5xl mb-4">⚽</div>
                    <h3 class="text-xl font-bold mb-2">Nenhuma partida cadastrada</h3>
                    <p class="text-brand-ice/50">Use o formulário acima para criar o primeiro jogo.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($campeonato->partidas as $partida)
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-brand-ice/5 hover:bg-brand-ice/10 border border-brand-ice/5 hover:border-brand-ice/20 rounded-2xl p-4 transition">
                            <a href="{{ route('partidas.show', $partida) }}" class="flex flex-col lg:flex-row lg:items-center gap-4 flex-1">
                                <div class="flex items-center gap-6 flex-wrap">
                                    <div class="flex items-center gap-3 min-w-[160px]">
                                        <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-12 h-12 object-contain" alt="">
                                        <span class="font-semibold">{{ $partida->timeCasa->nome }}</span>
                                    </div>

                                    <div class="text-3xl font-black tracking-wide">
                                        {{ $partida->gols_casa }}
                                        <span class="text-brand-ice/30 mx-2">x</span>
                                        {{ $partida->gols_fora }}
                                    </div>

                                    <div class="flex items-center gap-3 min-w-[160px]">
                                        <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-12 h-12 object-contain" alt="">
                                        <span class="font-semibold">{{ $partida->timeFora->nome }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 text-sm text-brand-ice/50">
                                    <x-partida-status-badge :partida="$partida" :pulse="true" />
                                    @if ($partida->fase)
                                        <span class="px-3 py-1 rounded-full bg-brand-ice/5 border border-brand-ice/10 capitalize">{{ str_replace('_', ' ', $partida->fase) }}</span>
                                    @endif
                                </div>
                            </a>

                            <div class="flex items-center gap-3">
                                @if (! $partida->isFinalizada())
                                    <form method="POST" action="{{ route('partidas.destroy', $partida) }}" onsubmit="return confirm('Excluir esta partida?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 rounded-xl bg-brand-orange/15 border border-brand-orange/25 text-brand-orange-sand text-sm hover:bg-brand-orange/25 transition">
                                            Excluir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div x-show="tab === 'estatisticas'" x-transition>
        <div class="grid grid-cols-1 lg:grid-gols-2 gap-6">
            <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">
                            ⚽ Artilheiros
                        </h2>

                        <p class="text-sm text-brand-ice/50 mt-1">
                            Jogadores com mais gols
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($artilheiros as $index => $jogador)
                        <div class="flex items-center justify-between bg-brand-ice/5 hover:bg-brand-ice/10 border border-brand-ice/5 hover:border-brand-ice/10 rounded-2xl p-4 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full
                                    @if($index === 0)
                                        bg-brand-orange-sand/20 text-brand-orange-sand
                                    @elseif ($index === 1)
                                        bg-brand-urban/30 text-brand-ice/70
                                    @elseif ($index === 2)
                                        bg-brand-purple/30 text-brand-orange-sand
                                    @else
                                        bg-brand-ice/5 text-brand-ice/70
                                    @endif
                                    flex items-center justify-center font-bold
                                ">
                                    {{ $index + 1 }}
                                </div>

                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $jogador->time->logo) }}" class="w-12 h-12 object-contain">

                                    <div>
                                        <p class="font-semibold">
                                            {{ $jogador->nome }}
                                        </p>

                                        <p class="text-xs text-brand-ice/50">
                                            {{ $jogador->time->nome }}
                                        </p>

                                        <x-jogador-social-links :jogador="$jogador" class="mt-2" />
                                        <x-jogador-stats :jogador="$jogador" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-2xl font-black text-brand-orange-sand">{{ $jogador->gols }}</p>

                                <span class="text-xs text-brand-ice/50">
                                    @if ($jogador->gols > 1)
                                        gols
                                    @else
                                        gol
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">
                            🎯 Assistências
                        </h2>

                        <p class="text-sm text-brand-ice/50 mt-1">
                            Líderes em assistências
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($assistencias as $index => $jogador)
                        <div class="flex items-center justify-between bg-brand-ice/5 hover:bg-brand-ice/10 border border-brand-ice/5 hover:border-brand-ice/10 rounded-2xl p-4 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-brand-blue/30 text-brand-blue-light flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>

                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $jogador->time->logo) }}" class="w-12 h-12 object-contain">

                                    <div>
                                        <p class="font-semibold">
                                            {{ $jogador->nome }}
                                        </p>

                                        <p class="text-xs text-brand-ice/50">
                                            {{ $jogador->time->nome }}
                                        </p>

                                        <x-jogador-social-links :jogador="$jogador" class="mt-2" />
                                        <x-jogador-stats :jogador="$jogador" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-2xl font-black text-brand-blue-light">
                                    {{ $jogador->assistencias }}
                                </p>

                                <span class="text-xs text-brand-ice/50">
                                    assists
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div x-show="tab === 'mata-mata'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach (data_get($classificados, 'label', []) as $partida)
                <a href="{{ route('partidas.show', $partida) }}" class="flex items-center justify-between bg-brand-ice/5 hover:bg-brand-ice/10 transition rounded-2xl p-4 border border-brand-ice/5">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-10 h-10 object-contain">

                        <span class="font-medium">{{ $partida->timeCasa->nome }}</span>
                    </div>
                </a>

                <a href="{{ route('partidas.show', $partida) }}" class="flex items-center justify-between bg-brand-ice/5 hover:bg-brand-ice/10 transition rounded-2xl p-4 border border-brand-ice/5">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-10 h-10 object-contain">

                        <span class="font-medium">{{ $partida->timeFora->nome }}</span>
                    </div>
                </a>

                <span class="text-brand-ice/30">
                    vs
                </span>
            @endforeach
        </div>
    </div>
</div>

@endsection
