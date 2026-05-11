@extends('layouts.app')

@section('content')

<div class="p-6 max-w-4xl mx-auto"
    x-data="{
        openGol: false,
        time: 'casa',
        tipo: 'gol',
        jogador_id: '',
        temAssistencia: false,
        assistencia_id: '',
        eventos: @js($partida->eventos),
        golsCasa: {{ $partida->gols_casa ?? 0 }},
        golsFora: {{ $partida->gols_fora ?? 0 }},

        async addEvento(form) {
            let data = new FormData(form);

            let res = await fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: data
            });

            let json = await res.json();


            if(!res.ok) {
                alert(json.message);
                return;
            }

            this.eventos.push(json.evento);

            if(json.placar){
                this.golsCasa = json.placar.casa;
                this.golsFora = json.placar.fora;
            }

            this.openGol = false;

            form.reset();
        },

        async deleteGol(id) {
            let res = await fetch('/eventos/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            let json = await res.json();

            this.eventos = this.eventos.filter(e => e.id != id);
            this.golsCasa = json.placar.casa;
            this.golsFora = json.placar.fora;

        }
    }">

    <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-2xl p-6 mb-4">

        <div class="text-center text-white/50 text-sm mb-4">
            {{ \Carbon\Carbon::parse($partida->data)->format('d/m/Y H:i') }}
        </div>

        <div class="flex items-center justify-between">

            <div class="flex flex-col items-center gap-2 w-1/3">
                @if($partida->timeCasa->logo)
                <img src="{{ asset('storage/'.$partida->timeCasa->logo) }}" class="w-16 h-16 object-contain">
                @endif

                <span class="font-semibold text-center">
                    {{ $partida->timeCasa->nome }}
                </span>
            </div>

            <div class="text-4xl font-bold text-center">
                <span x-text="golsCasa"></span>
                <span class="text-white/50">x</span>
                <span x-text="golsFora"></span>
            </div>

            <div class="flex flex-col items-center gap-2 w-1/3">
                @if($partida->timeFora->logo)
                <img src="{{ asset('storage/'.$partida->timeFora->logo) }}" class="w-16 h-16 object-contain">
                @endif

                <span class="font-semibold text-center">
                    {{ $partida->timeFora->nome }}
                </span>
            </div>

        </div>

        <div class="text-center text-white/40 text-sm mt-4">
            {{ $partida->campeonato->nome ?? 'Amistoso' }}
        </div>

        <div class="flex gap-3">
            <button @click="openGol = true" class="bg-green-600 px-4 py-2 rounded">
                📊 Adicionar Eventos
            </button>
        </div>

    </div>

    <div class="relative rounded-2xl p-6 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 via-transparent to-yellow-500/5"></div>

        <div class="absolute inset-0 rounded-2xl border border-orange-400/20"></div>

        <div class="relative z-10 space-y-4">

            <h2 class="text-lg font-semibold mb-4">Eventos da Partida</h2>


            <template x-for="e in [...eventos].sort((a,b) => a.minuto - b.minuto)" :key="e.id">
                <div class="relative flex items-center py-3">

                    <div class="top-0 bottom-0 w-px bg-white/10 -translate-x-1/2"></div>

                    <div class="w-1/2 pr-6 text-right">
                        <template x-if="e.jogador.time_id === {{ $partida->time_casa_id }}">
                            <div class="inline-flex items-center gap-2 bg-orange-500/10 px-3 py-1 rounded-lg">
                                <span class="text-sm font-medium" x-text="e.jogador.nome"></span>
                                <span x-show="e.tipo == 'gol'">⚽</span>
                                <template x-if="e.tipo === 'gol' && e.assistencia">
                                    ( <span x-text="e.assistencia?.nome"></span> )
                                </template>
                                <span x-show="e.tipo == 'cartao_amarelo'">🟨</span>
                                <span x-show="e.tipo == 'cartao_vermelho'">🟥</span>
                            </div>
                        </template>
                    </div>

                    <div class="relative z-10 w-0 flex flex-col items-center">
                        <div class="bg-white/10 text-xs px-2 py-1 rounded-full">
                            <span x-text="e.minuto + '\''"></span>
                        </div>
                    </div>

                    <div class="w-1/2 pl-6 text-left">
                        <template x-if="e.jogador.time_id === {{ $partida->time_fora_id }}">
                            <div class="inline-flex items-center gap-2 bg-orange-500/10 px-3 py-1 rounded-lg">
                                <span class="text-sm font-medium" x-text="e.jogador.nome"></span>
                                <span x-show="e.tipo === 'gol'">⚽</span>
                                <template x-if="e.tipo === 'gol' && e.assistencia">
                                    ( <span x-text="e.assistencia?.nome"></span> )
                                </template>
                                <span x-show="e.tipo === 'cartao_amarelo'">🟨</span>
                                <span x-show="e.tipo === 'cartao_vermelho'">🟥</span>
                            </div>
                        </template>
                    </div>

                    <button @click="deleteGol(e.id)" class="text-red-400 hover:text-red-600 text-xs ml-2 opacity-60 hover:opacity-100 transition">
                        ✕
                    </button>
                </div>
            </template>

            <template x-if="eventos.length === 0">
                <div class="text-center py-8 text-white/40">

                    <div class="text-3xl mb-2">⚽</div>

                    <p class="text-sm">
                        Nenhum evento registrado
                    </p>

                    <p class="text-xs mt-1 text-white/30">
                        Adicione o primeiro gol da partida
                    </p>
                </div>
            </template>
        </div>
    </div>


    <template x-if="openGol">
        <div class="fixed inset-0 z-[9999] flex items-center justify-center">

            <div @click="openGol = false" class="absolute inset-0 bg-black/70 backdrop-blur-md"></div>

            <div class="relative bg-[#020617] w-full max-w-md rounded-2xl border border-white/10 shadow-2xl p-6 space-y-5">

                <div class="text-center">
                    <h2 class="text-lg font-semibold">Adicionar Gol ⚽</h2>
                    <p class="text-xs text-white/40">Registre um evento da partida</p>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div class="flex flex-col items-center gap-1 w-1/3">
                        @if($partida->timeCasa->logo)
                        <img src="{{ asset('storage/'.$partida->timeCasa->logo) }}" class="w-8 h-8 object-contain">
                        @endif
                        <span class="text-xs text-center">{{ $partida->timeCasa->nome }}</span>
                    </div>

                    <span class="text-white/40 font-bold">VS</span>

                    <div class="flex flex-col items-center gap-1 w-1/3">
                        @if($partida->timeFora->logo)
                        <img src="{{ asset('storage/'.$partida->timeFora->logo) }}" class="w-8 h-8 object-contain">
                        @endif
                        <span class="text-xs text-center">{{ $partida->timeFora->nome }}</span>
                    </div>
                </div>

                <form action="{{ route('eventos.store') }}" method="POST" @submit.prevent="addEvento($event.target)">
                    @csrf

                    <input class="hidden" name="partida_id" value="{{ $partida->id }}">
                    <input class="hidden" name="tipo" value="gol">

                    <div>
                        <label class="text-xs text-white/60">Time</label>

                        <select name="time_id" x-model="time" class="w-full mt-1 bg-white/10 border border-white/10 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="casa">{{ $partida->timeCasa->nome }}</option>
                            <option value="fora">{{ $partida->timeFora->nome }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-white/60">Selecione o Evento</label>
                        <select name="tipo" class="bg-gray-800 text-white w-full mt-1 border border-white/10 rounded px-3 py-2" x-model="tipo" @change="
                            if (tipo !== 'gol') {
                                temAssistencia = false;
                            }
                        ">
                            <option value="gol">⚽ Gol</option>
                            <option value="cartao_amarelo">🟨 Amarelo</option>
                            <option value="cartao_vermelho">🟥 Vermelho</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-white/60">Jogador</label>

                        <select name="jogador_id" x-show="time === 'casa'" :disabled="time !== 'casa'" class="w-full mt-1 bg-white/10 border border-white/10 rounded-lg px-3 py-2" x-model="jogador_id">
                            @foreach($partida->timeCasa->jogadores as $j)
                            <option value="{{ $j->id }}">{{ $j->nome }}</option>
                            @endforeach
                        </select>

                        <select name="jogador_id" x-show="time === 'fora'" :disabled="time !== 'fora'" class="w-full mt-1 bg-white/10 border border-white/10 rounded-lg px-3 py-2" x-model="jogador_id">
                            @foreach($partida->timeFora->jogadores as $j)
                            <option value="{{ $j->id }}">{{ $j->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="tipo === 'gol'">
                        <label class="mt-1 flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" x-model="temAssistencia" class="sr-only">
                            <div class=" w-11 h-6 rounded-full relative transition" :class="temAssistencia ? 'bg-green-500' : 'bg-white/10'">
                                <div class="h-5 w-5 bg-white rounded-full absolute top-0.5 left-0.5 transition-all" :class="temAssistencia ? 'translate-x-5' : ''">
                                </div>
                            </div>
                            <span class="text-xs text-white/80">
                                🎯 Assistência
                            </span>
                        </label>
                    </div>

                    <template x-if="tipo === 'gol' && temAssistencia">
                        <div>
                            <label class="text-xs text-white/60">Assistência</label>

                            <select x-show="time === 'casa'" :disabled="time !== 'casa'" name="assistencia_id" class="w-full mt-1 bg-white/10 rounded-lg px-3 py-2">
                                @foreach($partida->timeCasa->jogadores as $j)
                                <option value="{{ $j->id }}">{{ $j->nome }}</option>
                                @endforeach
                            </select>

                            <select x-show="time === 'fora'" :disabled="time !== 'fora'" name="assistencia_id" class="w-full mt-1 bg-white/10 rounded-lg px-3 py-2">
                                @foreach($partida->timeFora->jogadores as $j)
                                <option value="{{ $j->id }}">{{ $j->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>


                    <div>
                        <label class="text-xs text-white/60">Minuto</label>

                        <input type="number" name="minuto" min="1" max="90" placeholder="Ex: 23" class="w-full mt-1 bg-white/10 border border-white/10 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="openGol = false" class="w-1/2 bg-white/10 hover:bg-white/20 py-2 rounded-lg text-sm transition">
                            Cancelar
                        </button>
                        <button class="w-1/2 bg-green-600 hover:bg-green-700 py-2 rounded-lg text-sm font-semibold transition">
                            Salvar Gol
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </template>
</div>

@endsection
