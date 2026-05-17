@extends('layouts.app')

@section('content')

<div class="space-y-8">
    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#020617] p-8">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 via-purple-500/10 to-blue-500/10"></div>

        <div class="absolute -top-20 -right-20 w-72 h-72 bg-purple-500/20 blur-3xl rounded-full"></div>

        <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-purple-500/20 blur-3xl rounded-full"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <div>
                <span class="px-4 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-white/60">
                    Sistema Oficial
                </span>

                <h1 class="mt-5 text-5xl font-black leading-tight bg-gradient-to-r from-orange-400 via-purple-400 to-blue-400 bg-clip-text text-transparent">
                    Spidium Cup
                </h1>

                <p class="mt-4 text-white/50 max-w-2xl leading-relaxed">
                    Plataforma inteligente para gerenciamento de campeonatos, partidas e estatísticas da SPIDIUM!
                </p>

                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="{{ route('campeonatos.create') }}" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 font-semibold hover:scale-105 transition">
                        + Novo Campeonato
                    </a>

                    <a href="{{ route('campeonatos.index') }}" class="px-6 py-3 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition">
                        Ver campeonatos
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full lg:w-[420px]">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                    <p class="text-sm text-white/40">
                        Campeonatos
                    </p>

                    <h2 class="text-4xl font-black mt-2">
                        {{ $campeonatos }}
                    </h2>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                    <p class="text-sm text-white/40">
                        Times
                    </p>

                    <h2 class="text-4xl font-black mt-2">
                        {{ $times }}
                    </h2>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                    <p class="text-sm text-white/40">
                        Partidas
                    </p>

                    <h2 class="text-4xl font-black mt-2">
                        {{ $partidas }}
                    </h2>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                    <p class="text-sm text-white/40">
                        Finalizadas
                    </p>

                    <h2 class="text-4xl font-black mt-2">
                        {{ $partidasFinalizadas }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-[#020617] border border-white/10 rounded-3xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold">🔴 Partidas Ao Vivo</h2>

                        <p class="text-sm text-white/40 mt-1">Jogos acontecendo agora</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($partidasAoVivo as $partida)
                        <div class="bg-white/5 border border-white/5 rounded-2xl p-5 hover:bg-white/10 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col items-center">
                                        <img class="w-12 h-12 rounded-full bg-white/10" src="{{ asset('storage/' . $partida->timeCasa->logo) }}">
                                        <span class="text-xs mt-2">{{ $partida->timeCasa->nome }}</span>
                                    </div>

                                    <div class="text-center">
                                        <p class="text-3xl font-black">{{ $partida->gols_casa }} x {{ $partida->gols_fora }}</p>
                                        <span class="text-red-400 text-sm">AO VIVO</span>
                                    </div>

                                    <div class="flex flex-col items-center">
                                        <img class="w-12 h-12 rounded-full bg-white/10" src="{{ asset('storage/' . $partida->timeFora->logo) }}">
                                        <span class="text-xs mt-2">{{ $partida->timeFora->nome }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('partidas.show', $partida) }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm">
                                    Ver Partida
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-white/30">
                            Nenhuma partida ao vivo
                        </div>
                    @endforelse

                </div>
            </div>

            <div class="bg-[#020617] border border-white/10 rounded-3xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold">⚽ Últimos Resultados</h2>

                        <p class="text-sm text-white/40 mt-1">
                            Últimas partidas finalizadas
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($ultimasResultados as $partida)
                    <a href="{{ route('partidas.show', $partida) }}">
                        <div class="flex items-center justify-between bg-white/5 rounded-2xl p-4 hover:bg-white/10 transition mb-2">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-10 h-10 rounded-full bg-white/10">

                                    <span class="font-medium">{{ $partida->timeCasa->nome }}</span>
                                </div>

                                <span class="text-white/30">
                                    vs
                                </span>

                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-10 h-10 rounded-full bg-white/10">

                                    <span class="font-medium">{{ $partida->timeFora->nome }}</span>
                                </div>
                            </div>

                            <div class="text-2xl font-black">
                                {{ $partida->gols_casa }} x {{ $partida->gols_fora }}
                            </div>
                        </div>
                    </a>
                @empty

                @endforelse
            </div>
        </div>
    </div>

    <div class="xl:col-span-1 space-y-6">
        <div class="bg-[#020617] border border-white/10 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold">
                        🏆 Ranking
                    </h2>

                    <p class="text-sm text-white/40 mt-1">
                        Melhores equipes
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                @foreach ($artilheiros as $art)
                    <div class="flex items-center justify-between bg-white/5 rounded-2xl p-4">
                        <div>
                            <p class="font-semibold">{{ $art->nome }}</p>

                            <p class="text-xs text-white/40">{{ $art->time->nome ?? 'Sem time' }}</p>
                        </div>

                        <span>
                            ⚽ {{ $art->gols }}
                        </span>
                    </div>

                @endforeach
            </div>
        </div>

        <div class="bg-[#020617] border border-white/10 rounded-3xl p-6">
            <h2 class="text-xl font-bold mb-6">
                📢 Atividade
            </h2>

            <div class="space-y-5 text-sm">
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-green-400 mt-2"></div>

                    <div>
                        <p>Novo Campeonato criado</p>

                        <span class="text-white/30 text-xs">há 5 minutos</span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-400 mt-2"></div>

                    <div>
                        <p>Novo time registrado</p>

                        <span class="text-white/30 text-xs">há 1 hora</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
