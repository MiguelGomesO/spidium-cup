@extends('layouts.app')

@section('content')

<div
    x-data="{ filtro: 'todas' }"
    class="max-w-7xl mx-auto space-y-8"
>
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-3">
                Jogos
            </span>
            <h1 class="text-3xl lg:text-4xl font-black text-brand-gradient">
                Partidas
            </h1>
            <p class="text-brand-ice/60 mt-2 max-w-lg">
                Acompanhe placares, entre na súmula ao vivo ou agende novos confrontos.
            </p>
        </div>

        <a
            href="{{ route('partidas.create') }}"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition shadow-lg shadow-brand-purple/20 shrink-0"
        >
            <span class="text-lg leading-none" aria-hidden="true">+</span>
            Nova partida
        </a>
    </div>

    @php
        $total = $partidas->count();
        $finalizadas = $partidas->where('finalizada', true)->count();
        $aoVivo = $partidas->where('finalizada', false)->count();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-brand-surface border border-brand-ice/10 rounded-2xl p-5">
            <p class="text-sm text-brand-urban">Total</p>
            <p class="text-3xl font-black mt-1">{{ $total }}</p>
        </div>
        <div class="bg-brand-surface border border-brand-ice/10 rounded-2xl p-5">
            <p class="text-sm text-brand-urban">Em andamento</p>
            <p class="text-3xl font-black mt-1 text-brand-blue-light">{{ $aoVivo }}</p>
        </div>
        <div class="bg-brand-surface border border-brand-ice/10 rounded-2xl p-5">
            <p class="text-sm text-brand-urban">Finalizadas</p>
            <p class="text-3xl font-black mt-1 text-brand-orange-sand">{{ $finalizadas }}</p>
        </div>
        <div class="bg-brand-surface border border-brand-ice/10 rounded-2xl p-5 hidden sm:block">
            <p class="text-sm text-brand-urban">Atalho</p>
            <p class="text-sm text-brand-ice/60 mt-2">Clique na partida para abrir a súmula ao vivo.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-brand-asphalt/50 border border-brand-blue-light/25 text-brand-blue-light text-sm rounded-2xl px-5 py-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($partidas->isEmpty())
        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-12 text-center">
            <div class="text-6xl mb-6" aria-hidden="true">⚽</div>
            <h2 class="text-2xl font-bold mb-2">Nenhuma partida cadastrada</h2>
            <p class="text-brand-ice/50 max-w-md mx-auto mb-8">
                Crie o primeiro jogo para registrar gols, assistências e finalizar o confronto.
            </p>
            <a href="{{ route('partidas.create') }}" class="inline-flex px-6 py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition">
                Agendar partida
            </a>
        </div>
    @else
        <div class="flex flex-wrap gap-2">
            <button
                type="button"
                @click="filtro = 'todas'"
                :class="filtro === 'todas' ? 'bg-brand-gradient text-brand-ice' : 'bg-brand-ice/5 text-brand-ice/60 hover:bg-brand-ice/10'"
                class="px-4 py-2 rounded-xl text-sm font-medium transition"
            >
                Todas ({{ $total }})
            </button>
            <button
                type="button"
                @click="filtro = 'ao_vivo'"
                :class="filtro === 'ao_vivo' ? 'bg-brand-gradient text-brand-ice' : 'bg-brand-ice/5 text-brand-ice/60 hover:bg-brand-ice/10'"
                class="px-4 py-2 rounded-xl text-sm font-medium transition"
            >
                Em andamento ({{ $aoVivo }})
            </button>
            <button
                type="button"
                @click="filtro = 'finalizadas'"
                :class="filtro === 'finalizadas' ? 'bg-brand-gradient text-brand-ice' : 'bg-brand-ice/5 text-brand-ice/60 hover:bg-brand-ice/10'"
                class="px-4 py-2 rounded-xl text-sm font-medium transition"
            >
                Finalizadas ({{ $finalizadas }})
            </button>
        </div>

        <div class="space-y-4">
            @foreach ($partidas as $partida)
                <article
                    x-show="filtro === 'todas'
                        || (filtro === 'ao_vivo' && {{ $partida->finalizada ? 'false' : 'true' }})
                        || (filtro === 'finalizadas' && {{ $partida->finalizada ? 'true' : 'false' }})"
                    x-transition
                    class="bg-brand-surface border border-brand-ice/10 hover:border-brand-lilac/30 rounded-3xl overflow-hidden transition"
                >
                    <div class="p-5 lg:p-6 flex flex-col lg:flex-row lg:items-center gap-5">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="text-xs text-brand-urban">
                                    📅 {{ $partida->data->format('d/m/Y H:i') }}
                                </span>
                                @if ($partida->finalizada)
                                    <span class="px-2.5 py-0.5 rounded-full bg-brand-orange/15 border border-brand-orange/25 text-xs text-brand-orange-sand font-semibold">
                                        Finalizada
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-brand-asphalt/60 border border-brand-blue-light/25 text-xs text-brand-blue-light font-semibold animate-pulse">
                                        Ao vivo
                                    </span>
                                @endif
                                <span class="px-2.5 py-0.5 rounded-full bg-brand-purple/20 text-xs text-brand-lilac">
                                    {{ $partida->campeonato->nome ?? 'Amistoso' }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 lg:gap-8">
                                <div class="flex items-center gap-3 min-w-[120px]">
                                    @if ($partida->timeCasa->logo)
                                        <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-10 h-10 object-contain" alt="">
                                    @endif
                                    <span class="font-semibold">{{ $partida->timeCasa->nome }}</span>
                                </div>

                                <div class="text-3xl font-black tabular-nums text-brand-orange-sand px-2">
                                    {{ $partida->gols_casa ?? 0 }}
                                    <span class="text-brand-urban mx-1">×</span>
                                    {{ $partida->gols_fora ?? 0 }}
                                </div>

                                <div class="flex items-center gap-3 min-w-[120px]">
                                    <span class="font-semibold">{{ $partida->timeFora->nome }}</span>
                                    @if ($partida->timeFora->logo)
                                        <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-10 h-10 object-contain" alt="">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 lg:flex-col lg:min-w-[140px] shrink-0">
                            <a
                                href="{{ route('partidas.show', $partida) }}"
                                class="flex-1 lg:flex-none text-center py-2.5 px-4 rounded-xl bg-brand-gradient text-sm font-semibold hover:opacity-90 transition"
                            >
                                {{ $partida->finalizada ? 'Ver súmula' : 'Súmula ao vivo' }}
                            </a>
                            <a
                                href="{{ route('partidas.edit', $partida) }}"
                                class="flex-1 lg:flex-none text-center py-2.5 px-4 rounded-xl bg-brand-ice/10 border border-brand-ice/10 text-sm hover:bg-brand-ice/15 transition"
                            >
                                Editar
                            </a>
                            <form
                                method="POST"
                                action="{{ route('partidas.destroy', $partida) }}"
                                class="flex-1 lg:flex-none"
                                onsubmit="return confirm('Excluir esta partida?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-brand-orange/10 border border-brand-orange/20 text-sm text-brand-orange-sand hover:bg-brand-orange/20 transition">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

@endsection
