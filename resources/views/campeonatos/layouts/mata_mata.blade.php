@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-br from-brand-black via-brand-surface to-brand-black p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-3xl p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-4xl font-bold bg-brand-gradient bg-clip-text text-transparent">
                                {{ $campeonato->nome }}
                            </h1>

                            <span class="px-3 py-1 rounded-full bg-brand-orange/20 text-brand-orange-sand text-xs font-semibold">
                                Mata-Mata
                            </span>
                        </div>

                        <div class="flex gap-6 mt-4 text-brand-ice/70 text-sm">
                            <span>
                                ⚽ {{ $campeonato->times->count() }}/{{ $campeonato->qtd_times }} times
                            </span>

                            <span>
                                🏆 Eliminatório
                            </span>
                        </div>
                    </div>

                    @if ($campeonato->times->count() === $campeonato->qtd_times)
                        <form method="POST" action="{{ route('campeonatos.gerar-chaveamento', $campeonato) }}">
                            @csrf
                            <button
                                class="px-5 py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition"
                            >

                                Gerar Chaveamento

                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-1 space-y-6">
                    <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-3xl p-6 overflow-visible relative z-50">
                        <h2 class="text-lg font-semibold mb-5">
                            Adicionar Time
                        </h2>

                        <form
                            method="POST"
                            action="{{ route('campeonatos.times.store', $campeonato) }}"
                            class="space-y-4"
                        >
                            @csrf

                            <div
                                x-data="{
                                    open: false,
                                    selected: null
                                }"

                                class="relative z-50"
                            >
                                <input
                                    type="hidden"
                                    name="time_id"
                                    :value="selected?.id"
                                >

                                <button
                                    type="button"
                                    @click="open = !open"
                                    class="w-full p-3 rounded-2xl bg-brand-black/50 border border-brand-ice/10 flex items-center justify-between"
                                >

                                    <template x-if="selected">
                                        <div class="flex items-center gap-3">
                                            <img
                                                :src="selected.logo"
                                                class="w-8 h-8 object-contain"
                                            >
                                            <span
                                                x-text="selected.nome"
                                                class="text-brand-ice"
                                            ></span>
                                        </div>
                                    </template>

                                    <template x-if="!selected">
                                        <span class="text-brand-ice/50">
                                            Selecione um time
                                        </span>

                                    </template>

                                    <span class="text-brand-ice/60">
                                        ▼
                                    </span>
                                </button>

                                <div
                                    x-show="open"
                                    x-transition
                                    @click.outside="open = false"
                                    class="absolute z-[999] mt-2 w-full rounded-2xl bg-brand-black border border-brand-ice/10 overflow-hidden shadow-2xl max-h-72 overflow-y-auto"
                                >
                                    @foreach ($times as $time)

                                        <button
                                            type="button"

                                            @click="
                                                selected = @js([
                                                    'id' => $time->id,
                                                    'nome' => $time->nome,
                                                    'logo' => asset('storage/' . $time->logo),
                                                ]);

                                                open = false;
                                            "

                                            class="w-full px-4 py-3 hover:bg-brand-ice/5 flex items-center gap-3 text-left transition"
                                        >

                                            <img
                                                src="{{ asset('storage/' . $time->logo) }}"
                                                class="w-8 h-8 object-contain"
                                            >

                                            <span class="text-brand-ice">
                                                {{ $time->nome }}
                                            </span>

                                        </button>

                                    @endforeach
                                </div>
                            </div>

                            <button
                                class="w-full py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition"
                            >
                                Adicionar Time
                            </button>
                        </form>
                    </div>

                    <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-3xl p-6">
                        <h2 class="text-lg font-semibold mb-4">
                            Sistema Eliminatório
                        </h2>

                        <div class="space-y-3 text-sm text-brand-ice/60">
                            <p>
                                • Número de times deve ser par
                            </p>

                            <p>
                                • Perdeu está eliminado
                            </p>

                            <p>
                                • Chaveamento automático
                            </p>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-2">
                    <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-3xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold">
                                Chaveamento
                            </h2>

                            <span class="text-sm text-brand-ice/50">
                                Mata-Mata
                            </span>
                        </div>

                        @if ($campeonato->partidas->isNotEmpty())
                            <div class="space-y-5">
                                <div>
                                    <h3 class="text-lg font-semibold mb-4 text-brand-orange-sand">
                                        {{ ucfirst($campeonato->partidas->first()->fase) }}
                                    </h3>

                                    <div class="space-y-4">
                                        @foreach ($campeonato->partidas as $partida)
                                            <div class="bg-black/20 border border-brand-ice/5 rounded-2xl p-4">
                                                <div class="flex items-center justify-between py-2">
                                                    <div class="flex items-center gap-3">
                                                        <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-10 h-10 object-contain">

                                                        <span class="font-medium">
                                                            {{ $partida->timeCasa->nome }}
                                                        </span>
                                                    </div>

                                                    <span class="font-bold text-xl">
                                                        {{ $partida->gols_casa ?? 0 }}
                                                    </span>
                                                </div>

                                                <div class="border-t border-brand-ice/5 my-2"></div>

                                                <div class="flex items-center justify-between py-2">
                                                    <div class="flex items-center gap-3">
                                                        <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-10 h-10 object-contain">

                                                        <span class="font-medium">
                                                            {{ $partida->timeFora->nome }}
                                                        </span>
                                                    </div>

                                                    <span class="font-bold text-xl">
                                                        {{ $partida->gols_fora ?? 0 }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-20 text-center">
                                <div class="text-7xl mb-5">
                                    🏆
                                </div>

                                <h3 class="text-2xl font-bold mb-3">
                                    Chaveamento não gerado
                                </h3>

                                <p class="text-brand-ice/50 max-w-md">
                                    Gere o chaveamento automático para iniciar o mata-mata.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
