@extends('layouts.app')

@section('content')

<div class="bg-gradient-to-br from-brand-black via-brand-surface to-brand-black p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-3xl p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-4xl font-bold bg-brand-gradient bg-clip-text text-transparent">
                            {{  $campeonato->nome }}
                        </h1>

                        <span class="px-3 py-1 rounded-full bg-brand-blue/30 text-brand-blue-light text-xs font-semibold">
                            Liga
                        </span>
                    </div>

                    <div class="flex gap-6 mt-4 text-brand-ice/70 text-sm">
                        <span>
                            ⚽ {{ $campeonato->times->count() }}/{{ $campeonato->qtd_times }}
                        </span>

                        <span>
                            📅 {{ $campeonato->created_at->format('d/m/Y')}}
                        </span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="#" class="px-5 py-3 rounded-xl bg-brand-gradient font-semibold hover:opacity-90 transition">
                        Gerar Partidas
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-1 space-y-6">
                <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-3xl p-6 overflow-visible relative z-50">
                    <h2 class="text-lg font-semibold mb-5">
                        Adicionar Time
                    </h2>

                    <form method="POST" action="{{ route('campeonatos.times.store', $campeonato) }}" class="space-y-4">
                        @csrf

                        <div x-data="{
                                open: false,
                                selected: null
                            }"
                            class="relative"
                        >
                            <input type="hidden" name="time_id" :value="selected?.id">

                            <button type="button" @click="open = !open" class="w-full p-3 rounded-2xl bg-brand-black/50 border border-brand-ice/10 flex items-center justify-between">
                                <template x-if="selected">
                                    <div class="flex items-center gap-3">
                                        <img :src="selected.logo" class="w-8 h-6 object-contain">

                                        <span x-text="selected.nome" class="text-brand-ice"></span>
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

                            <div x-show="open" x-transition @click.outside="open = false" class="absolute z-[999] mt-2 w-full rounded-2xl bg-brand-black border border-brand-ice/10 overflow-hidden shadow-2xl max-h-72 overflow-y-auto">
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
                                        <img src="{{ asset('storage/' . $time->logo) }}" class="w-8 h-8 object-contain">

                                        <span class="text-brand-ice">{{ $time->nome }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <button class="w-full py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition">
                            Adicionar time
                        </button>
                    </form>
                </div>

                <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold">
                            Classificação
                        </h2>

                        <a href="{{ route('campeonatos.classificacao', $campeonato) }}" class="text-sm text-brand-blue-light hover:text-brand-ice transition">
                            Ver Tabela
                        </a>
                    </div>

                    <p class="text-brand-ice/50 text-sm">
                        A classificação aparecerá após as partidas.
                    </p>
                </div>
            </div>

            <div class="xl:col-span-2">
                <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold">
                            Times Participantes
                        </h2>

                        <span class="text-sm text-brand-ice/50">
                            {{ $campeonato->times->count() }} times
                        </span>
                    </div>

                    @if ($campeonato->times->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($campeonato->times as $time)
                                <div class="bg-black/20 border-border-brand-ice/5 rounded-2xl p-4 hover:bg-white/[0.03] transition">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ asset('storage/' . $time->logo) }}" class="w-14 h-14 object-contain">

                                        <div>
                                            <h3 class="font-semibold text-lg">
                                                {{ $time->nome }}
                                            </h3>

                                            <p class="text-sm text-brand-ice/50">
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
                                    ⚽
                                </div>

                                <h3 class="text-xl font-semibold text-brand-ice/80 mb-2">
                                    Nenhum time participando
                                </h3>

                                <p class="text-brand-ice/50 max-w-sm">
                                    Adicione times ao campeonato para começar a competição.
                                </p>
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
