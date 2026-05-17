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

                        <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 text-xs font-semibold">
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

                <a href="{{ route('campeonatos.show', $campeonato) }}" class="px-5 py-3 rounded-2xl bg-white/10 border border-white/10 hover:bg-white/20 transition">
                    ← Voltar
                </a>
            </div>
            <div class="mt-4 flex justify-between">
                @if ($campeonato->partidas->isEmpty())
                    <form method="POST" action="{{ route('campeonatos.gerar-partidas-grupos', $campeonato) }}">
                        @csrf

                        <button class="px-5 py-3 rounded-2xl bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 font-semibold hover:opacity-90 transition">
                            Gerar Partidas
                        </button>
                    </form>
                @endif
                @if ($campeonato->grupos->isEmpty())
                    <form method="POST" action="{{ route('campeonatos.gerar-grupos', $campeonato) }}">
                        @csrf

                        <button class="px-5 py-3 rounded-2xl bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 font-semibold hover:opacity-90 transition">
                            Gerar grupos automático
                        </button>
                    </form>
                @endif
            </div>


        </div>



        @if ($campeonato->grupos->isEmpty())
        <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-10 mb-6">
            <form method="POST" action="{{ route('campeonatos.grupos.store', $campeonato) }}" class="flex items-end gap-4">
                @csrf
                <div class="flex-1">
                    <label class="text-sm text-white/60">
                        Nome do Grupo
                    </label>

                    <input type="text" name="nome" placeholder="Ex: Grupo A" class="w-full mt-2 p-3 rounded-2xl bg-black/40 border border-white/10 outline-none">
                </div>

                <button type="submit" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 font-semibold hover:opacity-90 transition">
                    Criar Grupo
                </button>
            </form>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @php
                $timesUsados = $campeonato->grupos
                    ->flatMap(fn ($grupo) => $grupo->times)
                    ->pluck('id')
                    ->toArray();
            @endphp
            @foreach ($campeonato->grupos as $grupo)
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 bg-white/[0.03] flex items-center justify-between">
                    <h2 class="text-xl font-bold">
                        {{ $grupo->nome }}
                    </h2>

                    @if ($grupo->times->isEmpty())
                        <form method="POST" action="{{ route('grupos.destroy', $grupo) }}">
                            @csrf
                            @method('DELETE')

                            <button class="text-red-400 hover:text-red-300 transition">
                                ✕
                            </button>
                        </form>

                    @else
                        <div class="text-xs text-white/30" title="Grupo possui times">
                            🔒
                        </div>
                    @endif

                </div>

                <div class="p-4 border-b border-white/5">
                    <form method="POST" action="{{ route('grupos.times.store', $grupo) }}" class="flex gap-3">
                        @csrf
                        <div
                            x-data="{
                                open: false,
                                selected: null
                            }"
                            class="relative flex-1 z-50"
                        >
                            <input type="hidden" name="time_id" :value="selected?.id">

                            <button type="button" @click="open = !open" class="w-full p-3 rounded-2xl bg-black/40 border border-white/10 flex items-center justify-between">
                                <template x-if="selected">
                                    <div class="flex items-center gap-3">
                                        <img :src="selected.logo" class="w-8 h-8 object-contain">

                                        <span x-text="selected.nome" class="text-white"></span>
                                    </div>
                                </template>

                                <template x-if="!selected">
                                    <span class="text-white/40">Selecionar time</span>
                                </template>

                                <span class="text-white/50">
                                    ▼
                                </span>
                            </button>

                            <div
                                x-show="open"
                                x-transition
                                @click.outside="open = false"
                                class="absolute z-[999] mt-2 w-full rounded-2xl bg-[#0f172a] border border-white/10 overflow-hidden shadow-2xl max-h-72 overflow-y-auto"
                            >
                                @foreach ($campeonato->times->whereNotIn('id', $timesUsados) as $time)
                                    <button
                                        type="button"
                                        @click="
                                            selected= @js([
                                                'id' => $time->id,
                                                'nome' => $time->nome,
                                                'logo' => asset('storage/' . $time->logo),
                                            ]);
                                            open = false;
                                        "
                                        class="w-full px-4 py-3 hover:bg-white/5 flex items-center gap-3 text-left transition"
                                    >
                                        <img src="{{ asset('storage/' . $time->logo) }}" class="w-8 h-8 object-contain">

                                        <span class="text-white">{{ $time->nome }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="px-4 rounded-full bg-green-400 font-semibold hover:opacity-90 hover:bg-green-600 transition">
                            +
                        </button>
                    </form>
                </div>

                <div class="p-4 space-y-3">
                    @forelse ($grupo->times as $time)
                    <div class="bg-black/20 border border-white/5 rounded-2xl p-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/' . $time->logo) }}" class="w-12 h-12 object-contain">

                            <div>
                                <h3 class="font-semibold">
                                    {{ $time->nome }}
                                </h3>

                                <p class="text-sm text-white/40">
                                    Participante
                                </p>
                            </div>
                        </div>

                        @php
                            $possuiPartidas = $campeonato->partidas->where('time_casa_id', $time->id)
                                ->merge($campeonato->partidas->where('time_fora_id', $time->id))
                                ->isNotEmpty();
                        @endphp

                        @if (!$possuiPartidas)
                            <form method="POST" action="{{ route('grupos.times.destroy', [$grupo, $time]) }}">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-400 hover:text-red-300 transition">
                                    ✕
                                </button>
                            </form>
                        @else
                            <div class="text-xs text-white/30" title="Time possui partidas geradas">
                                🔒
                            </div>
                        @endif
                    </div>
                    @empty
                    <div class="py-10 text-center text-white/40">
                        Nenhum time no grupo
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

        @if ($campeonato->partidas->isNotEmpty())
            <div class="mt-8">
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold">
                            Partidas
                        </h2>

                        <span class="text-sm text-white/40">{{ $campeonato->partidas->count() }} jogos</span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($campeonato->partidas as $partida)
                            <div class="bg-black/20 border border-white/5 rounded-2xl p-4 hover:bg-white/[0.03] transition">
                                <a href="{{ route('partidas.show', $partida) }}" class="block bg-black/20 border border-white/5 rounded-2xl p-4 hover:bg-white/[0.03] transition cursor-pointer">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3 w-[40%]">
                                            <img src="{{ asset('storage/' . $partida->timeCasa->logo) }}" class="w-12 h-12 object-contain">

                                            <span class="font-semibold">{{ $partida->timeCasa->nome }}</span>
                                        </div>

                                        <div class="text-center">
                                            <div class="text-xl font-bold">
                                                {{ $partida->gols_casa ?? 0 }}
                                                x
                                                {{ $partida->gols_fora ?? 0 }}
                                            </div>

                                            <div class="text-xs text-white/40 mt-1">
                                                {{ \Carbon\Carbon::parse($partida->data)->format('d/m H:i') }}
                                            </div>
                                        </div>


                                        <div class="flex items-center justify-end gap-3 w-[40%]">
                                            <span class="font-semibold">{{ $partida->timeFora->nome }}</span>

                                            <img src="{{ asset('storage/' . $partida->timeFora->logo) }}" class="w-12 h-12 object-contain">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                <h2 class="text-xl font-bold mb-6">
                    ⚽ Artilheiros
                </h2>

                <div class="space-y-4">
                    @forelse ($artilheiros as $art)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="font-semibold">
                                    {{ $art->jogador->nome }}
                                </span>
                            </div>

                            <span class="font-bold text-orange-300">
                                {{ $art->gols }}
                            </span>
                        </div>
                    @empty
                        <p class="text-white/40 flex items-center justify-center py-10">
                            Nenhum gol registrado
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                <h2 class="text-xl font-bold mb-6">
                    👟 Assistências
                </h2>

                <div class="space-y-4">
                    @forelse ($assistencias as $ass)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="font-semibold">
                                    {{ $ass->assistencia->nome }}
                                </span>
                            </div>

                            <span class="font-bold text-orange-300">
                                {{ $ass->assistencias }}
                            </span>
                        </div>
                    @empty
                        <p class="text-white/40 flex items-center justify-center py-10">
                            Nenhuma assistencia registrado
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                <h2 class="text-xl font-bold mb-6">
                    🧤 Clean Sheets
                </h2>

                <div class="flex items-center justify-center py-10 text-white/40">
                    Em breve
                </div>
            </div>
        </div>

        @if (!empty($classificacao))
            <div class="mt-8">
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                    <h2 class="text-2xl font-bold mb-6">
                        📊 Classificação
                    </h2>

                    <div class="space-y-8">
                        @foreach ($classificacao as $grupo => $times)
                            <div class="overflow-hidden rounded-2xl border border-white/5">
                                <div class="bg-white/[0.03] px-6 py-4 border-b border-white/5">
                                    <h3 class="text-xl font-bold">
                                        {{ $grupo }}
                                    </h3>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="text-white/50 text-sm">
                                            <tr class="border-b border-white/5">
                                                <th class="text-left p-4">Time</th>
                                                <th>PTS</th>
                                                <th>J</th>
                                                <th>V</th>
                                                <th>E</th>
                                                <th>D</th>
                                                <th>GP</th>
                                                <th>GC</th>
                                                <th>SG</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($times as $linha)
                                                <tr class="border-b border-white/5 hover:bg-white/[0.02] transition">
                                                    <td class="p-4">
                                                        <div class="flex items-center gap-3">
                                                            <img src="{{ asset('storage/' . $linha['time']->logo) }}" class="w-10 h-10 object-contain">

                                                            <span class="font-semibold">
                                                                {{ $linha['time']->nome }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center font-bold text-orange-300">{{ $linha['pontos'] }}</td>
                                                    <td class="text-center">{{ $linha['jogos'] }}</td>
                                                    <td class="text-center">{{ $linha['vitorias'] }}</td>
                                                    <td class="text-center">{{ $linha['empates'] }}</td>
                                                    <td class="text-center">{{ $linha['derrotas'] }}</td>
                                                    <td class="text-center">{{ $linha['gp'] }}</td>
                                                    <td class="text-center">{{ $linha['gc'] }}</td>
                                                    <td class="text-center">{{ $linha['sg'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
