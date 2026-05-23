@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
    <a href="{{ route('partidas.index') }}" class="inline-flex items-center min-h-[44px] text-sm text-brand-ice/60 hover:text-brand-ice transition">← Partidas</a>
    <a href="{{ route('partidas.edit', $partida) }}" class="btn-ghost text-sm w-full sm:w-auto text-center">
        Editar partida
    </a>
</div>

@if (session('success'))
    <div class="max-w-4xl mx-auto mb-4 bg-brand-asphalt/50 border border-brand-blue-light/25 text-brand-blue-light text-sm rounded-2xl px-5 py-4">
        {{ session('success') }}
    </div>
@endif

<div class="p-6 max-w-4xl mx-auto"
    x-data="{
        openGol: false,
        openFinalizar: false,
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

    <div class="section-card mb-4">
        <div class="flex justify-center mb-4">
            <x-partida-status-badge :partida="$partida" :pulse="true" />
        </div>

        <div class="match-scoreboard">
            <div class="match-team">
                @if($partida->timeCasa->logo)
                    <img src="{{ asset('storage/'.$partida->timeCasa->logo) }}" class="match-team__logo" alt="">
                @endif
                <span class="match-team__name">{{ $partida->timeCasa->nome }}</span>
            </div>
            <div class="match-placar">
                <span class="match-placar__score"><span x-text="golsCasa"></span> × <span x-text="golsFora"></span></span>
            </div>
            <div class="match-team match-team--away">
                @if($partida->timeFora->logo)
                    <img src="{{ asset('storage/'.$partida->timeFora->logo) }}" class="match-team__logo" alt="">
                @endif
                <span class="match-team__name">{{ $partida->timeFora->nome }}</span>
            </div>
        </div>

        <p class="text-center text-brand-ice/50 text-sm mt-4">
            {{ $partida->campeonato->nome ?? 'Amistoso' }}
        </p>

        @if (! $partida->isFinalizada())
            <div class="flex flex-col sm:flex-row flex-wrap gap-3 mt-4">
                <button @click="openGol = true" class="bg-green-600 hover:bg-green-500 transition px-4 py-2 rounded-xl font-medium">
                    📊 Adicionar eventos
                </button>

                <button @click="openFinalizar = true" class="bg-brand-orange hover:bg-brand-purple transition px-4 py-2 rounded-xl font-medium">
                    🏁 Finalizar partida
                </button>
            </div>

            <div class="mt-4 pt-4 border-t border-brand-ice/10">
                <p class="text-xs text-brand-urban mb-2">Alterar status</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (\App\Models\Partida::statuses() as $value => $label)
                        @if ($value !== $partida->status)
                            <form method="POST" action="{{ route('partidas.status', $partida) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $value }}">
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-brand-ice/10 border border-brand-ice/10 hover:bg-brand-ice/15 transition">
                                    {{ $label }}
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <div class="relative rounded-2xl p-6 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-orange/10 via-transparent to-brand-orange-sand/10"></div>

        <div class="absolute inset-0 rounded-2xl border border-brand-orange/25"></div>

        <div class="relative z-10 space-y-4">

            <h2 class="text-lg font-semibold mb-4">Eventos da Partida</h2>


            <template x-for="e in [...eventos].sort((a,b) => a.minuto - b.minuto)" :key="e.id">
                <div class="relative flex items-center py-3">

                    <div class="top-0 bottom-0 w-px bg-brand-ice/10 -translate-x-1/2"></div>

                    <div class="w-1/2 pr-6 text-right">
                        <template x-if="e.jogador.time_id === {{ $partida->time_casa_id }}">
                            <div class="inline-flex items-center gap-2 bg-brand-orange/10 px-3 py-1 rounded-lg">
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
                        <div class="bg-brand-ice/10 text-xs px-2 py-1 rounded-full">
                            <span x-text="e.minuto + '\''"></span>
                        </div>
                    </div>

                    <div class="w-1/2 pl-6 text-left">
                        <template x-if="e.jogador.time_id === {{ $partida->time_fora_id }}">
                            <div class="inline-flex items-center gap-2 bg-brand-orange/10 px-3 py-1 rounded-lg">
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

                    <button @click="deleteGol(e.id)" class="text-brand-orange hover:text-brand-orange-sand text-xs ml-2 opacity-60 hover:opacity-100 transition">
                        ✕
                    </button>
                </div>
            </template>

            <template x-if="eventos.length === 0">
                <div class="text-center py-8 text-brand-ice/50">

                    <div class="text-3xl mb-2">⚽</div>

                    <p class="text-sm">
                        Nenhum evento registrado
                    </p>

                    <p class="text-xs mt-1 text-brand-ice/30">
                        Adicione o primeiro gol da partida
                    </p>
                </div>
            </template>
        </div>
    </div>

    @include('partidas.partials.desempenho-jogadores')

    <template x-if="openGol">
        <div class="fixed inset-0 z-[9999] flex items-center justify-center">

            <div @click="openGol = false" class="absolute inset-0 bg-black/70 backdrop-blur-md"></div>

            <div class="relative bg-brand-surface w-full max-w-md max-h-[90vh] overflow-y-auto rounded-2xl border border-brand-ice/10 shadow-2xl p-4 sm:p-6 space-y-5 mx-2">

                <div class="text-center">
                    <h2 class="text-lg font-semibold">Adicionar Gol ⚽</h2>
                    <p class="text-xs text-brand-ice/50">Registre um evento da partida</p>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div class="flex flex-col items-center gap-1 w-1/3">
                        @if($partida->timeCasa->logo)
                        <img src="{{ asset('storage/'.$partida->timeCasa->logo) }}" class="w-8 h-8 object-contain">
                        @endif
                        <span class="text-xs text-center">{{ $partida->timeCasa->nome }}</span>
                    </div>

                    <span class="text-brand-ice/50 font-bold">VS</span>

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
                        <label class="text-xs text-brand-ice/70">Time</label>

                        <select name="time_id" x-model="time" class="w-full mt-1 bg-brand-ice/10 border border-brand-ice/10 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="casa">{{ $partida->timeCasa->nome }}</option>
                            <option value="fora">{{ $partida->timeFora->nome }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs text-brand-ice/70">Selecione o Evento</label>
                        <select name="tipo" class="bg-gray-800 text-brand-ice w-full mt-1 border border-brand-ice/10 rounded px-3 py-2" x-model="tipo" @change="
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
                        <label class="text-xs text-brand-ice/70">Jogador</label>

                        <select name="jogador_id" x-show="time === 'casa'" :disabled="time !== 'casa'" class="w-full mt-1 bg-brand-ice/10 border border-brand-ice/10 rounded-lg px-3 py-2" x-model="jogador_id">
                            @foreach($partida->timeCasa->jogadores as $j)
                            <option value="{{ $j->id }}">{{ $j->nome }}</option>
                            @endforeach
                        </select>

                        <select name="jogador_id" x-show="time === 'fora'" :disabled="time !== 'fora'" class="w-full mt-1 bg-brand-ice/10 border border-brand-ice/10 rounded-lg px-3 py-2" x-model="jogador_id">
                            @foreach($partida->timeFora->jogadores as $j)
                            <option value="{{ $j->id }}">{{ $j->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="tipo === 'gol'">
                        <label class="mt-1 flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" x-model="temAssistencia" class="sr-only">
                            <div class=" w-11 h-6 rounded-full relative transition" :class="temAssistencia ? 'bg-green-500' : 'bg-brand-ice/10'">
                                <div class="h-5 w-5 bg-white rounded-full absolute top-0.5 left-0.5 transition-all" :class="temAssistencia ? 'translate-x-5' : ''">
                                </div>
                            </div>
                            <span class="text-xs text-brand-ice/80">
                                🎯 Assistência
                            </span>
                        </label>
                    </div>

                    <template x-if="tipo === 'gol' && temAssistencia">
                        <div>
                            <label class="text-xs text-brand-ice/70">Assistência</label>

                            <select x-show="time === 'casa'" :disabled="time !== 'casa'" name="assistencia_id" class="w-full mt-1 bg-brand-ice/10 rounded-lg px-3 py-2">
                                @foreach($partida->timeCasa->jogadores as $j)
                                <option value="{{ $j->id }}">{{ $j->nome }}</option>
                                @endforeach
                            </select>

                            <select x-show="time === 'fora'" :disabled="time !== 'fora'" name="assistencia_id" class="w-full mt-1 bg-brand-ice/10 rounded-lg px-3 py-2">
                                @foreach($partida->timeFora->jogadores as $j)
                                <option value="{{ $j->id }}">{{ $j->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>


                    <div>
                        <label class="text-xs text-brand-ice/70">Minuto</label>

                        <input type="number" name="minuto" min="1" max="90" placeholder="Ex: 23" class="w-full mt-1 bg-brand-ice/10 border border-brand-ice/10 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="openGol = false" class="w-1/2 bg-brand-ice/10 hover:bg-brand-ice/15 py-2 rounded-lg text-sm transition">
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
    <template x-if="openFinalizar">
        <div class="fixed inset-0 z-[99999] flex items-center justify-center">
            <div @click="openFinalizar = false" class="absolute inset-0 bg-black/70 backdrop-blur-md"></div>

            <div class="relative w-full max-w-md rounded-2xl border border-brand-ice/10 bg-brand-surface p-6 shadow-2xl">
                <div class="text-center">
                    <div class="text-5xl mb-3">
                        🏁
                    </div>

                    <h2 class="text-xl font-bold mb-2">
                        Finalizar partida?
                    </h2>

                    <p class="text-sm text-brand-ice/60 mb-6">
                        Após finalizar, os eventos e o placar não poderão mais ser alterados.
                    </p>

                    <div class="flex gap-3">
                        <button @click="openFinalizar = false" class="flex-1 bg-brand-ice/10 hover:bg-brand-ice/15 transition rounded-xl py-3">
                            Cancelar
                        </button>

                        <form method="POST" action="{{ route('partidas.finalizar', $partida) }}" class="flex-1">
                            @csrf
                            @method('PATCH')

                            <button class="w-full bg-brand-orange hover:bg-brand-purple transition rounded-xl py-3 font-semibold">
                                Finalizar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@endsection
