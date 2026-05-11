@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#020617] via-[#0f172a] to-[#020617] p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-400 via-purple-500 to-blue-500 bg-clip-text text-transparent">
                                {{ $campeonato->nome }}
                            </h1>

                            <span class="px-3 py-1 rounded-full bg-purple-500 text-xs font-semibold ">
                                Grupos
                            </span>
                        </div>

                        <div class="flex gap-6 mt-4 text-white/60 text-sm">
                            <span>
                                ⚽ {{ $campeonato->times->count() }}/{{ $campeonato->qtd_times }} times
                            </span>

                            <span>
                                🏆 {{ $campeonato->grupos->count() }} grupos
                            </span>
                        </div>
                    </div>

                    @if ($campeonato->times->count() >= 8)
                        <form method="POST" action="{{ route('campeonatos.gerar-grupos', $campeonato) }}">
                            @csrf

                            <button class="px-5 py-3 rounded-2xl bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 font-semibold hover:opacity-90 transition">
                                Gerar Partidas
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-1 space-y-6">
                    <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 overflow-visible relative z-50">
                        <h2 class="text-lg font-semibold mb-5">
                            Adicionar time
                        </h2>

                        <form method="POST" action="{{ route('campeonatos.times.store', $campeonato) }}" class="space-y-4">
                            @csrf

                            <div
                                x-data="{
                                    open: false,
                                    selected: null
                                }"

                                class="relative z-50"
                            >
                                <input type="hidden" name="time_id" :value="selected?.id">

                                <button
                                    type="button"
                                    @click="open = !open"
                                    class="w-full p-3 rounded-2xl bg-black/40 border border-white/10 flex items-center justify-between"
                                >
                                    <template x-if="selected">
                                        <div class="flex items-center gap-3">
                                            <img :src="selected.logo" class="w-8 h-8 object-contain">

                                            <span x-text="selected.nome" class="text-white"></span>
                                        </div>
                                    </template>

                                    <template x-if="!selected">
                                        <span class="text-white/40">
                                            Selecione um time
                                        </span>
                                    </template>

                                    <span class="text-white">
                                        ▼
                                    </span>
                                </button>

                                <div
                                    x-show="open"
                                    x-transition
                                    @click.outside="open = false"
                                    class="absolute z-[999] mt-2 w-full rounded-2xl bg-[#0f172a] border border-white/10 overflow-hidden shadow-2xl max-h-72 overflow-y-auto"
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

                                                open: false;
                                            "
                                            class="w-full px-4 py-3 hover:bg-white/5 flex items-center gap-3 text-left transition"
                                        >
                                            <img src="{{ asset('storage/' . $time->logo) }}" class="w-8 h-8 object-contain">

                                            <span class="text-white">
                                                {{ $time->nome }}
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <button
                                :disabled="!selected"
                                class="w-full py-3 rounded-2xl bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 font-semibold hover:opacity-90 transition"
                            >
                                Adicionar Time
                            </button>
                        </form>
                    </div>

                    <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                        <h2 class="text-lg font-semibold mb-4">
                            Sistema de Grupos
                        </h2>

                        <div class="space-y-3 text-sm text-white/50">
                            <p>
                                • Mínimo de 8 times
                            </p>

                            <p>
                                • Times distribuídos automaticamente
                            </p>

                            <p>
                                • Aproximadamente 4 times por grupo
                            </p>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-2">
                    <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold">
                                Times Participantes
                            </h2>

                            <span class="text-sm text-white/40">
                                {{ $campeonato->times->count() }} times
                            </span>
                        </div>

                        @if ($campeonato->times->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($campeonato->times as $time)
                                    <div class="bg-black/20 border border-white/5 rounded-2xl p-4 hover:bg-white/[0.03] transition">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ asset('storage/' . $time->logo) }}" class="w-14 h-14 object-contain">

                                            <div>
                                                <h3 class="font-semibold text-lg">
                                                    {{ $time->nome }}
                                                </h3>

                                                <p class="text-sm text-white/40">
                                                    Participante
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="text-6xl mb-4">
                                    🏆
                                </div>

                                <h3 class="text-xl font-semibold text-white/80 mb-2">
                                    Nenhum time participando
                                </h3>

                                <p class="text-white-40 max-w-sm">
                                    Adicione times para começar a fase de grupos
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
