@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('title', 'Dashboard')

@section('content')

<div class="page">
    <div class="page-hero">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-orange/10 via-brand-purple/10 to-brand-blue/10"></div>
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-brand-purple/20 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-brand-orange/15 blur-3xl rounded-full"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-4">
                    Painel administrativo
                </span>

                <h1 class="page-title">
                    Olá, {{ Auth::user()->name }} 👋
                </h1>

                <p class="page-subtitle max-w-xl">
                    Visão geral do Spidium Cup — campeonatos, partidas ao vivo e artilheiros em um só lugar.
                </p>

                <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 mt-5 sm:mt-6">
                    <a href="{{ route('campeonatos.create') }}" class="btn-brand w-full sm:w-auto text-sm">
                        + Novo campeonato
                    </a>
                    <a href="{{ route('partidas.create') }}" class="btn-ghost w-full sm:w-auto text-sm">
                        Nova partida
                    </a>
                    <a href="{{ route('resultados.index') }}" target="_blank" class="btn-ghost w-full sm:w-auto text-sm">
                        Página pública ↗
                    </a>
                </div>
            </div>

            <div class="grid-stats w-full lg:w-[380px] shrink-0">
                <div class="stat-box">
                    <p class="stat-box__label">Campeonatos</p>
                    <p class="stat-box__value text-brand-ice">{{ $campeonatos }}</p>
                </div>
                <div class="stat-box">
                    <p class="stat-box__label">Times</p>
                    <p class="stat-box__value text-brand-ice">{{ $times }}</p>
                </div>
                <div class="stat-box">
                    <p class="stat-box__label">Partidas</p>
                    <p class="stat-box__value text-brand-blue-light">{{ $partidas }}</p>
                </div>
                <div class="stat-box">
                    <p class="stat-box__label">Finalizadas</p>
                    <p class="stat-box__value text-brand-orange-sand">{{ $partidasFinalizadas }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Atalhos rápidos --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <a href="{{ route('campeonatos.index') }}" class="group p-5 rounded-2xl bg-brand-surface border border-brand-ice/10 hover:border-brand-purple/40 transition">
            <span class="text-2xl" aria-hidden="true">🏆</span>
            <p class="font-semibold mt-3 group-hover:text-brand-orange-sand transition">Campeonatos</p>
            <p class="text-xs text-brand-urban mt-1">Gerenciar torneios</p>
        </a>
        <a href="{{ route('times.index') }}" class="group p-5 rounded-2xl bg-brand-surface border border-brand-ice/10 hover:border-brand-purple/40 transition">
            <span class="text-2xl" aria-hidden="true">👥</span>
            <p class="font-semibold mt-3 group-hover:text-brand-orange-sand transition">Times</p>
            <p class="text-xs text-brand-urban mt-1">Elencos e escudos</p>
        </a>
        <a href="{{ route('partidas.index') }}" class="group p-5 rounded-2xl bg-brand-surface border border-brand-ice/10 hover:border-brand-purple/40 transition">
            <span class="text-2xl" aria-hidden="true">⚽</span>
            <p class="font-semibold mt-3 group-hover:text-brand-orange-sand transition">Partidas</p>
            <p class="text-xs text-brand-urban mt-1">Súmulas e placares</p>
        </a>
        <a href="{{ route('times.create') }}" class="group p-5 rounded-2xl bg-brand-surface border border-brand-ice/10 hover:border-brand-orange/40 transition">
            <span class="text-2xl" aria-hidden="true">➕</span>
            <p class="font-semibold mt-3 group-hover:text-brand-orange-sand transition">Novo time</p>
            <p class="text-xs text-brand-urban mt-1">Cadastro rápido</p>
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Coluna principal --}}
        <div class="xl:col-span-2 space-y-6">
            {{-- Ao vivo --}}
            <div class="section-card">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5 sm:mb-6">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-orange opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-brand-orange"></span>
                            </span>
                            Ao vivo
                        </h2>
                        <p class="text-sm text-brand-ice/50 mt-1">Partidas com súmula ativa agora</p>
                    </div>
                    <a href="{{ route('partidas.index') }}" class="text-sm text-brand-blue-light hover:text-brand-ice transition">
                        Ver todas →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($partidasAoVivo as $partida)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-brand-black/40 border border-brand-ice/5 rounded-2xl p-4 hover:border-brand-lilac/30 transition">
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 lg:gap-6">
                                <div class="flex items-center gap-2 min-w-[120px]">
                                    @if ($partida->timeCasa->logo)
                                        <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-9 h-9 object-contain" alt="">
                                    @else
                                        <span class="text-lg">⚽</span>
                                    @endif
                                    <span class="font-medium text-sm">{{ $partida->timeCasa->nome }}</span>
                                </div>

                                <div class="text-center px-2">
                                    <p class="text-2xl font-black tabular-nums text-brand-orange-sand">
                                        {{ $partida->gols_casa }} × {{ $partida->gols_fora }}
                                    </p>
                                    <span class="text-xs text-brand-blue-light font-semibold">AO VIVO</span>
                                </div>

                                <div class="flex items-center gap-2 min-w-[120px] sm:justify-end">
                                    <span class="font-medium text-sm">{{ $partida->timeFora->nome }}</span>
                                    @if ($partida->timeFora->logo)
                                        <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-9 h-9 object-contain" alt="">
                                    @else
                                        <span class="text-lg">⚽</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs text-brand-urban hidden sm:inline">
                                    {{ $partida->campeonato->nome ?? 'Amistoso' }}
                                </span>
                                <a href="{{ route('partidas.show', $partida) }}" class="px-4 py-2 rounded-xl bg-brand-gradient text-sm font-medium hover:opacity-90 transition whitespace-nowrap">
                                    Abrir súmula
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 rounded-2xl border border-dashed border-brand-ice/10">
                            <p class="text-brand-ice/40 mb-4">Nenhuma partida ao vivo no momento.</p>
                            <a href="{{ route('partidas.create') }}" class="inline-flex px-5 py-2.5 rounded-xl bg-brand-gradient text-sm font-semibold hover:opacity-90 transition">
                                Nova partida
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Últimos resultados --}}
            <div class="section-card">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5 sm:mb-6">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold">Últimos resultados</h2>
                        <p class="text-sm text-brand-ice/50 mt-1">Partidas finalizadas recentemente</p>
                    </div>
                    <a href="{{ route('resultados.index') }}" class="text-sm text-brand-blue-light hover:text-brand-ice transition">
                        Página pública ↗
                    </a>
                </div>

                <div class="space-y-2">
                    @forelse ($ultimasResultados as $partida)
                        <a href="{{ route('partidas.show', $partida) }}" class="block">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-brand-black/40 rounded-2xl p-4 hover:bg-brand-ice/5 border border-transparent hover:border-brand-ice/10 transition">
                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        @if ($partida->timeCasa->logo)
                                            <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-8 h-8 object-contain" alt="">
                                        @endif
                                        <span class="font-medium">{{ $partida->timeCasa->nome }}</span>
                                    </div>
                                    <span class="text-brand-urban text-sm">vs</span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium">{{ $partida->timeFora->nome }}</span>
                                        @if ($partida->timeFora->logo)
                                            <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-8 h-8 object-contain" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xl font-black tabular-nums text-brand-orange-sand">
                                        {{ $partida->gols_casa }} × {{ $partida->gols_fora }}
                                    </span>
                                    <x-partida-status-badge :partida="$partida" class="text-xs" />
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-center py-8 text-brand-ice/40">Nenhum resultado registrado ainda.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Em andamento --}}
            <div class="section-card">
                <h2 class="text-lg font-bold mb-1">Em andamento</h2>
                <p class="text-sm text-brand-ice/50 mb-5">Cadastradas no campeonato, ainda não ao vivo</p>

                <div class="space-y-3">
                    @forelse ($partidasEmAndamento as $partida)
                        <a href="{{ route('partidas.show', $partida) }}" class="block p-4 rounded-2xl bg-brand-black/40 border border-brand-ice/5 hover:border-brand-ice/15 transition">
                            <x-partida-status-badge :partida="$partida" class="mb-2" />
                            <p class="text-sm font-semibold leading-snug">
                                {{ $partida->timeCasa->nome }}
                                <span class="text-brand-urban font-normal"> vs </span>
                                {{ $partida->timeFora->nome }}
                            </p>
                            <p class="text-xs text-brand-urban mt-1 truncate">
                                {{ $partida->campeonato->nome ?? 'Amistoso' }}
                            </p>
                        </a>
                    @empty
                        <p class="text-sm text-brand-ice/40 text-center py-4">Nenhuma partida em andamento.</p>
                    @endforelse
                </div>
            </div>

            {{-- Artilheiros --}}
            <div class="section-card">
                <h2 class="text-lg font-bold mb-1">Artilheiros</h2>
                <p class="text-sm text-brand-ice/50 mb-5">Maiores goleadores gerais</p>

                <div class="space-y-3">
                    @forelse ($artilheiros as $index => $art)
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-brand-black/40">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shrink-0
                                @if ($index === 0) bg-brand-orange-sand/20 text-brand-orange-sand
                                @elseif ($index === 1) bg-brand-ice/10 text-brand-ice/70
                                @else bg-brand-purple/20 text-brand-lilac
                                @endif
                            ">
                                {{ $index + 1 }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold truncate">{{ $art->nome }}</p>
                                <p class="text-xs text-brand-urban truncate">{{ $art->time->nome ?? '—' }}</p>
                            </div>
                            <span class="text-lg font-black text-brand-orange-sand shrink-0">{{ $art->gols }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-brand-ice/40 text-center py-4">Sem gols registrados ainda.</p>
                    @endforelse
                </div>
            </div>

            {{-- Atividade recente --}}
            <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6">
                <h2 class="text-lg font-bold mb-1">Gols recentes</h2>
                <p class="text-sm text-brand-ice/50 mb-5">Últimos eventos nas súmulas</p>

                <div class="space-y-4">
                    @forelse ($eventosRecentes as $evento)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-brand-orange shrink-0 mt-2"></div>
                            <div class="min-w-0">
                                <p class="text-sm leading-snug">
                                    <span class="font-semibold text-brand-orange-sand">{{ $evento->jogador->nome ?? 'Jogador' }}</span>
                                    marcou em
                                    <span class="text-brand-ice/80">
                                        {{ $evento->partida->timeCasa->nome ?? '?' }}
                                        ×
                                        {{ $evento->partida->timeFora->nome ?? '?' }}
                                    </span>
                                </p>
                                <span class="text-xs text-brand-urban">
                                    {{ $evento->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-brand-ice/40 text-center py-4">Nenhum gol registrado ainda.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
