@extends('layouts.app')

@section('page-title', 'Partidas')
@section('title', 'Partidas')

@section('content')

@php
    use App\Models\Partida;
    $total = $partidas->count();
    $finalizadas = $partidas->where('status', Partida::STATUS_FINALIZADA)->count();
    $aoVivo = $partidas->where('status', Partida::STATUS_AO_VIVO)->count();
    $emAndamento = $partidas->where('status', Partida::STATUS_EM_ANDAMENTO)->count();
@endphp

<div
    x-data="{ filtro: 'todas' }"
    class="page"
>
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-2">
                Jogos
            </span>
            <h1 class="page-title">Partidas</h1>
            <p class="page-subtitle max-w-lg">
                Placares, súmula ao vivo e novos confrontos.
            </p>
        </div>
        <a href="{{ route('partidas.create') }}" class="btn-brand w-full sm:w-auto shrink-0">
            + Nova partida
        </a>
    </div>

    <div class="grid-stats">
        <div class="stat-box">
            <p class="stat-box__label">Total</p>
            <p class="stat-box__value">{{ $total }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-box__label">Ao vivo</p>
            <p class="stat-box__value text-brand-blue-light">{{ $aoVivo }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-box__label">Em andamento</p>
            <p class="stat-box__value text-brand-lilac">{{ $emAndamento }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-box__label">Finalizadas</p>
            <p class="stat-box__value text-brand-orange-sand">{{ $finalizadas }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-brand-asphalt/50 border border-brand-blue-light/25 text-brand-blue-light text-sm rounded-2xl px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($partidas->isEmpty())
        <div class="section-card text-center py-10 sm:py-12">
            <div class="text-5xl sm:text-6xl mb-4" aria-hidden="true">⚽</div>
            <h2 class="text-xl font-bold mb-2">Nenhuma partida cadastrada</h2>
            <p class="text-brand-ice/50 max-w-md mx-auto mb-6 text-sm sm:text-base">
                Crie o primeiro jogo para registrar gols e estatísticas.
            </p>
            <a href="{{ route('partidas.create') }}" class="btn-brand">Adicionar partida</a>
        </div>
    @else
        <div class="tabs-scroll" role="tablist">
            <button type="button" @click="filtro = 'todas'" :class="filtro === 'todas' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Todas ({{ $total }})</button>
            <button type="button" @click="filtro = 'ao_vivo'" :class="filtro === 'ao_vivo' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Ao vivo ({{ $aoVivo }})</button>
            <button type="button" @click="filtro = 'em_andamento'" :class="filtro === 'em_andamento' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Em andamento ({{ $emAndamento }})</button>
            <button type="button" @click="filtro = 'finalizadas'" :class="filtro === 'finalizadas' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'">Finalizadas ({{ $finalizadas }})</button>
        </div>

        <div class="space-y-3 sm:space-y-4">
            @foreach ($partidas as $partida)
                <article
                    x-show="filtro === 'todas'
                        || (filtro === 'ao_vivo' && '{{ $partida->status }}' === 'ao_vivo')
                        || (filtro === 'em_andamento' && '{{ $partida->status }}' === 'em_andamento')
                        || (filtro === 'finalizadas' && '{{ $partida->status }}' === 'finalizada')"
                    x-transition
                    class="section-card overflow-hidden"
                >
                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        <x-partida-status-badge :partida="$partida" :pulse="true" />
                        <span class="px-2.5 py-0.5 rounded-full bg-brand-purple/20 text-xs text-brand-lilac truncate max-w-full">
                            {{ $partida->campeonato->nome ?? 'Amistoso' }}
                        </span>
                    </div>

                    <x-ui.match-score
                        :casa="$partida->timeCasa"
                        :fora="$partida->timeFora"
                        :gols-casa="$partida->gols_casa ?? 0"
                        :gols-fora="$partida->gols_fora ?? 0"
                    />

                    <div class="flex flex-col sm:flex-row gap-2 mt-4 pt-4 border-t border-brand-ice/10">
                        <a href="{{ route('partidas.show', $partida) }}" class="btn-brand flex-1 text-sm">
                            {{ $partida->isFinalizada() ? 'Ver súmula' : 'Abrir súmula' }}
                        </a>
                        <a href="{{ route('partidas.edit', $partida) }}" class="btn-ghost flex-1 text-sm">Editar</a>
                        <form method="POST" action="{{ route('partidas.destroy', $partida) }}" class="sm:flex-1" onsubmit="return confirm('Excluir esta partida?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full min-h-[44px] py-2.5 px-4 rounded-xl bg-brand-orange/10 border border-brand-orange/20 text-sm text-brand-orange-sand hover:bg-brand-orange/20 transition">
                                Excluir
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

@endsection
