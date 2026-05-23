@extends('layouts.app')

@section('content')

<div class="bg-gradient-to-br from-brand-black via-brand-surface to-brand-black p-6">
    <div class="max-w-5xl mx-auto">
        <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-2xl p-6 mb-6">
            <h1 class="text-3xl font-bold bg-brand-gradient bg-clip-text text-transparent">
                {{ $campeonato->nome }}
            </h1>

            <div class="flex gap-4 mt-3 text-sm text-brand-ice/70">

                <span>
                    Formato: {{  ucfirst($campeonato->formato) }}
                </span>

                <span>
                    {{ $campeonato->times->count() }}/{{ $campeonato->qtd_times }} times
                </span>
            </div>
        </div>

        <div class="bg-brand-ice/5 border border-brand-ice/10 backdrop-blur-xl rounded-2xl p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">
                Adicionar time
            </h2>

            <form method="POST" action="{{ route('campeonatos.times.store', $campeonato) }}" class="flex gap-3">
                @csrf

                <div x-data="{
                        open: false,
                        selected: null
                    }"
                    class="relative flex-1"
                >
                    <input type="hidden" name="time_id" :value="selected?.id">

                    <button type="button" @click="open = !open" class="w-full p-3 rounded-xl bg-brand-black/50 border border-brand-ice/10 flex items-center justify-between">
                        <template x-if="selected">
                            <div class="flex items-center gap-3">
                                <img :src="selected.logo" class="w-8 h-8 object-contain">
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

                    <div x-show="open" x-transition @click.outside="open = false" class="absolute z-50 mt-2 w-full rounded-xl bg-brand-black border border-brand-ice/10 overflow-hidden shadow-2xl max-h-72 overflow-y-auto">
                        @foreach ($times as $time)
                            <button type="button"
                                @click="
                                    selected = {
                                        id: {{ $time->id }},
                                        nome: '{{ $time->nome }}',
                                        logo: '{{ asset('storage/' . $time->logo) }}'
                                    };

                                    open = false;
                                "
                                class="w-full px-4 py-3 hover:bg-brand-ice/5 flex items-center gap-3 text-left transition"
                            >
                                <img src="{{ asset('storage/' . $time->logo) }}" class="w-8 h-8 object-contain">

                                <span class="text-brand-ice">
                                    {{ $time->nome }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <button class="p-5 rounded-xl bg-brand-gradient font-semibold">
                    Adicionar
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($campeonato->times as $time)
                <div class="bg-brand-ice/5 border  border-brand-ice/10 backdrop-blur-xl rounded-2xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-lg">
                                {{ $time->nome }}
                            </h3>

                            <p class="text-sm text-brand-ice/60">
                                Participante
                            </p>
                        </div>

                        <span class="text-2xl">
                            ⚽
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
