@extends('layouts.app')

@section('page-title', 'Campeonatos')
@section('title', 'Campeonatos')

@section('content')

@php
    $formatoLabel = fn (string $formato) => match ($formato) {
        'liga' => 'Pontos corridos',
        'grupos' => 'Fase de grupos',
        'mata_mata' => 'Mata-mata',
        default => ucfirst(str_replace('_', ' ', $formato)),
    };

    $formatoBadge = fn (string $formato) => match ($formato) {
        'liga' => 'admin-champ-badge admin-champ-badge--liga',
        'grupos' => 'admin-champ-badge admin-champ-badge--grupos',
        default => 'admin-champ-badge admin-champ-badge--mata',
    };

    $statusInfo = function ($c) {
        if ($c->partidas_count === 0) {
            return ['label' => 'Sem jogos', 'class' => 'admin-champ-status--idle'];
        }
        if ($c->partidas_finalizadas_count < $c->partidas_count) {
            return ['label' => 'Em andamento', 'class' => 'admin-champ-status--live'];
        }

        return ['label' => 'Encerrado', 'class' => 'admin-champ-status--done'];
    };

    $emAndamento = $campeonatos->filter(fn ($c) => $c->partidas_count > 0 && $c->partidas_finalizadas_count < $c->partidas_count)->count();
    $totalPartidas = $campeonatos->sum('partidas_count');
@endphp

<div class="page">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-3">
                Torneios
            </span>
            <h1 class="page-title">Campeonatos</h1>
            <p class="page-subtitle max-w-lg">
                Gerencie formatos, times, partidas e classificação de cada edição.
            </p>
        </div>

        <a href="{{ route('campeonatos.create') }}" class="btn-brand w-full sm:w-auto shrink-0">
            + Novo campeonato
        </a>
    </div>

    @if (session('success'))
        <div class="bg-brand-asphalt/50 border border-brand-blue-light/25 text-brand-blue-light text-sm rounded-2xl px-5 py-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($campeonatos->isEmpty())
        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-12 lg:p-16 text-center">
            <div class="text-6xl mb-6" aria-hidden="true">🏆</div>
            <h2 class="text-2xl font-bold text-brand-ice mb-2">Nenhum campeonato cadastrado</h2>
            <p class="text-brand-ice/50 max-w-md mx-auto mb-8">
                Crie o primeiro torneio para inscrever times, gerar partidas e acompanhar a classificação.
            </p>
            <a href="{{ route('campeonatos.create') }}" class="btn-brand">
                Criar campeonato
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-box">
                <p class="stat-box__label">Campeonatos</p>
                <p class="stat-box__value">{{ $campeonatos->count() }}</p>
            </div>
            <div class="stat-box">
                <p class="stat-box__label">Em andamento</p>
                <p class="stat-box__value text-brand-blue-light">{{ $emAndamento }}</p>
            </div>
            <div class="stat-box">
                <p class="stat-box__label">Partidas</p>
                <p class="stat-box__value text-brand-orange-sand">{{ $totalPartidas }}</p>
            </div>
            <div class="stat-box">
                <p class="stat-box__label">Times inscritos</p>
                <p class="stat-box__value text-brand-lilac">{{ $campeonatos->sum('times_count') }}</p>
            </div>
        </div>

        {{-- Mobile: cards --}}
        <div class="md:hidden space-y-3">
            @foreach ($campeonatos as $index => $c)
                @php $status = $statusInfo($c); @endphp
                <article class="admin-champ-card">
                    <div class="admin-champ-card__header">
                        <div class="admin-champ-card__icon admin-champ-card__icon--{{ $index % 3 }}">🏆</div>
                        <div class="min-w-0 flex-1">
                            <h2 class="font-bold text-lg truncate">{{ $c->nome }}</h2>
                            <span class="{{ $formatoBadge($c->formato) }} mt-2">
                                {{ $formatoLabel($c->formato) }}
                            </span>
                        </div>
                        <span class="admin-champ-status {{ $status['class'] }}">{{ $status['label'] }}</span>
                    </div>

                    <div class="admin-champ-card__stats">
                        <div>
                            <p class="admin-champ-card__stat-value">{{ $c->times_count }}/{{ $c->qtd_times }}</p>
                            <p class="admin-champ-card__stat-label">Times</p>
                        </div>
                        <div>
                            <p class="admin-champ-card__stat-value">{{ $c->partidas_count }}</p>
                            <p class="admin-champ-card__stat-label">Jogos</p>
                        </div>
                        <div>
                            <p class="admin-champ-card__stat-value">{{ $c->partidas_finalizadas_count }}</p>
                            <p class="admin-champ-card__stat-label">Finalizados</p>
                        </div>
                    </div>

                    <div class="admin-champ-card__progress">
                        <div class="admin-champ-card__progress-bar" style="width: {{ $c->qtd_times > 0 ? min(100, ($c->times_count / $c->qtd_times) * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-[11px] text-brand-ice/40 mt-1">Vagas preenchidas</p>

                    <div class="admin-champ-card__actions">
                        <a href="{{ route('campeonatos.show', $c) }}" class="btn-brand flex-1 text-sm text-center">Gerenciar</a>
                        <form action="{{ route('campeonatos.destroy', $c) }}" method="POST" onsubmit="return confirm('Excluir campeonato?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-champ-btn-delete" title="Excluir">✕</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Desktop: tabela --}}
        <div class="hidden md:block admin-champ-table-wrap">
            <div class="admin-champ-table-scroll">
                <table class="admin-champ-table">
                    <thead>
                        <tr>
                            <th class="admin-champ-table__th admin-champ-table__th--name">Campeonato</th>
                            <th class="admin-champ-table__th">Formato</th>
                            <th class="admin-champ-table__th">Status</th>
                            <th class="admin-champ-table__th">Times</th>
                            <th class="admin-champ-table__th">Partidas</th>
                            <th class="admin-champ-table__th">Criado</th>
                            <th class="admin-champ-table__th admin-champ-table__th--actions">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($campeonatos as $index => $c)
                            @php
                                $status = $statusInfo($c);
                                $fillPct = $c->qtd_times > 0 ? min(100, ($c->times_count / $c->qtd_times) * 100) : 0;
                            @endphp
                            <tr class="admin-champ-row">
                                <td class="admin-champ-table__td admin-champ-table__td--name">
                                    <div class="admin-champ-name">
                                        <div class="admin-champ-name__icon admin-champ-name__icon--{{ $index % 3 }}">🏆</div>
                                        <div class="min-w-0">
                                            <p class="admin-champ-name__title">{{ $c->nome }}</p>
                                            <div class="admin-champ-name__progress">
                                                <div class="admin-champ-name__progress-bar" style="width: {{ $fillPct }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="admin-champ-table__td">
                                    <span class="{{ $formatoBadge($c->formato) }}">
                                        {{ $formatoLabel($c->formato) }}
                                    </span>
                                </td>
                                <td class="admin-champ-table__td">
                                    <span class="admin-champ-status {{ $status['class'] }}">{{ $status['label'] }}</span>
                                </td>
                                <td class="admin-champ-table__td admin-champ-table__td--stat">
                                    <span class="font-bold tabular-nums">{{ $c->times_count }}</span>
                                    <span class="text-brand-ice/40">/</span>
                                    <span class="text-brand-ice/50 tabular-nums">{{ $c->qtd_times }}</span>
                                </td>
                                <td class="admin-champ-table__td admin-champ-table__td--stat">
                                    <span class="font-bold tabular-nums text-brand-orange-sand">{{ $c->partidas_finalizadas_count }}</span>
                                    <span class="text-brand-ice/40">/</span>
                                    <span class="text-brand-ice/50 tabular-nums">{{ $c->partidas_count }}</span>
                                </td>
                                <td class="admin-champ-table__td admin-champ-table__td--date">
                                    {{ $c->created_at->format('d/m/Y') }}
                                </td>
                                <td class="admin-champ-table__td admin-champ-table__td--actions">
                                    <div class="admin-champ-actions">
                                        <a href="{{ route('campeonatos.show', $c) }}" class="btn-brand text-sm py-2 px-4 min-h-[40px]">
                                            Gerenciar
                                        </a>
                                        <form action="{{ route('campeonatos.destroy', $c) }}" method="POST" onsubmit="return confirm('Excluir este campeonato?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-champ-btn-delete" title="Excluir">✕</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@endsection
