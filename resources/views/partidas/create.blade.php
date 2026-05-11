@extends('layouts.app')

@section('content')

<div class="p-6 max-w-3xl mx-auto">
    <div class="bg-white/10 backdrop-blur-lg p-6 rounded-xl">
        <h1 class="text-white text-xl mb-4">Criar Partida</h1>

        <form method="POST" action="{{ route('partidas.store') }}" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div x-data="{ amistoso: true }">
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" x-model="amistoso" id="amistoso">
                    <label for="amistoso" class="text-white">Amistoso</label>
                </div>

                <div class="mb-4">
                    <label class="text-white">Campeonato</label>
                    <select :disabled="amistoso" :class="amistoso ? 'opacity-50 cursor-not-allowed' : ''" name="campeonato_id" class="w-full rounded bg-white/10 text-white">
                        <option value="">Selecione...</option>
                        @foreach($campeonatos as $c)
                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div
                x-data="{
                    openCasa: false,
                    openFora: false,

                    casa: null,
                    fora: null,
                    data: null,

                    selectCasa(time) {
                        this.casa = time
                        if (this.fora && this.fora.id === time.id) {
                            this.fora = null
                        }
                        this.openCasa = false
                    },

                    selectFora(time) {
                        this.fora = time
                        if (this.casa && this.casa.id === time.id) {
                            this.casa = null
                        }
                        this.openFora = false
                    },
                }"
                class="mb-6">
                <div class="flex items-center gap-4">
                    <div class="flex-1 relative">

                        <label class="text-white text-sm">Time Casa</label>

                        <div
                            @click="openCasa = !openCasa"
                            class="bg-white/10 rounded p-3 cursor-pointer flex items-center justify-between">
                            <template x-if="casa">
                                <div class="flex items-center gap-2">
                                    <img :src="casa.logo" class="h-6 w-6 object-contain">
                                    <span x-text="casa.nome"></span>
                                </div>
                            </template>

                            <template x-if="!casa">
                                <span class="text-white/50">Selecione um time</span>
                            </template>

                            <span>▼</span>
                        </div>

                        <div
                            x-show="openCasa"
                            @click.outside="openCasa = false"
                            class="absolute w-full bg-[#020617] mt-2 rounded shadow max-h-60 overflow-auto z-50">
                            @foreach($times as $t)
                            <div
                                x-show="!fora || fora.id !== {{ $t->id }}"
                                @click="selectCasa({
                                        id: {{ $t->id }},
                                        nome: '{{ $t->nome }}',
                                        logo: '{{ $t->logo ? asset('storage/'.$t->logo) : '' }}'
                                    })"
                                class="p-2 hover:bg-white/10 cursor-pointer flex items-center gap-2">
                                <img src="{{ $t->logo ? asset('storage/'.$t->logo) : '' }}" class="h-6 w-6 object-contain">
                                <span>{{ $t->nome }}</span>
                            </div>
                            @endforeach
                            <template x-if="casa">
                                <div @click="casa = null; openCasa = false" class="p-2 hover:bg-red-500/20 cursor-pointer text-red-400">
                                    ✕ Limpar seleção
                                </div>
                            </template>
                        </div>

                        <input type="hidden" name="time_casa_id" :value="casa?.id">

                    </div>

                    <div class="text-xl font-bold text-white/60">
                        VS
                    </div>

                    <div class="flex-1 relative">

                        <label class="text-white">Time Fora</label>

                        <div
                            @click="openFora = !openFora"
                            class="bg-white/10 rounded p-3 cursor-pointer flex items-center justify-between">
                            <template x-if="fora">
                                <div class="flex items-center gap-2">
                                    <img :src="fora.logo" class="h-6 w-6 object-contain">
                                    <span x-text="fora.nome"></span>
                                </div>
                            </template>

                            <template x-if="!fora">
                                <span class="text-white/50">Selecione um time</span>
                            </template>

                            <span>▼</span>
                        </div>

                        <div
                            x-show="openFora"
                            @click.outside="openFora = false"
                            class="absolute w-full bg-[#020617] mt-2 rounded shadow max-h-60 overflow-auto z-50">
                            @foreach($times as $t)
                            <div
                                x-show="!casa || casa.id !== {{ $t->id }}"
                                @click="selectFora({
                                        id: {{ $t->id }},
                                        nome: '{{ $t->nome }}',
                                        logo: '{{ $t->logo ? asset('storage/'.$t->logo) : '' }}'
                                    })"
                                class="p-2 hover:bg-white/10 cursor-pointer flex items-center gap-2">
                                <img src="{{ $t->logo ? asset('storage/'.$t->logo) : '' }}" class="h-6 w-6 object-contain">
                                <span>{{ $t->nome }}</span>
                            </div>
                            @endforeach
                            <template x-if="fora">
                                <div @click="fora = null; openFora = false" class="p-2 hover:bg-red-500/20 cursor-pointer text-red-400">
                                    ✕ Limpar seleção
                                </div>
                            </template>
                        </div>

                        <input type="hidden" name="time_fora_id" :value="fora?.id">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="text-white">Data da Partida </label>

                    <input
                        type="datetime-local"
                        name="data"
                        x-model="data"
                        value="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full rounded bg-white/10 text-white p-2 border border-white/10">
                </div>

                <template x-if="casa || fora">
                    <div class="mt-6" x.transition.scale>
                        <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-2xl p-6 flex items-center justify-between">
                            <div class="flex flex-col items-center gap-2 w-1/3">
                                <template x-if="casa">
                                    <>
                                        <img :src="casa.logo" class="w-12 h-12 object-contain">
                                        <span class="font-semibold text-center" x-text="casa.nome"></span>
                                    </>
                                </template>

                                <template x-if="!casa">
                                    <span class="text-white/30 text-sm">Time Casa</span>
                                </template>
                            </div>

                            <div class="text-2xl font-extrabold text-white/70">
                                VS
                            </div>

                            <div class="flex flex-col items-center gap-2 w-1/3">
                                <template x-if="fora">
                                    <>
                                        <img :src="fora.logo" class="w-12 h-12 object-contain">
                                        <span class="font-semibold text-center" x-text="fora.nome"></span>
                                    </>
                                </template>

                                <template x-if="!fora">
                                    <span class="text-white/30 text-sm">Time Fora</span>
                                </template>
                            </div>

                            <template x-if="data">
                                <div class="w-full text-center mb-3">
                                    <span class="text-xs text-white/50">
                                        📅 <span x-text="new Date(data).toLocaleDateString()"></span>
                                        •
                                        🕒 <span x-text="new Date(data).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <button
                    :disabled="!casa || !fora || loading"
                    class="w-full py-3 rounded-xl text-white transition flex items-center justify-center gap-2 mt-6"
                    :class="(!casa || !fora)
                        ? 'bg-gray-600 cursor-not-allowed'
                        : 'bg-gradient-to-r from-red-500 to-orange-500 hover:opacity-90'
                    ">
                    <template x-if="loading">
                        <span class="animate-pulse">Salvando...</span>
                    </template>
                    <template x-if="!loading">
                        <span>Salvar Partida ⚽</span>
                    </template>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
