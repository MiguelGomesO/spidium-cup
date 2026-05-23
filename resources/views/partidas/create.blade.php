@extends('layouts.app')

@section('page-title', 'Nova partida')
@section('title', 'Nova partida')

@section('content')

<div class="page max-w-5xl">
    <a href="{{ route('partidas.index') }}" class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice transition">
        <span aria-hidden="true">←</span>
        Voltar às partidas
    </a>

    <div class="page-hero">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-orange/10 via-brand-purple/10 to-brand-blue/10"></div>
        <div class="relative z-10">
            <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-4">
                Nova partida
            </span>
            <h1 class="page-title">Adicionar confronto</h1>
            <p class="page-subtitle max-w-xl">
                Escolha os times e o status. Depois de criar, abra a súmula para registrar os gols.
            </p>
        </div>
    </div>

    <form
        method="POST"
        action="{{ route('partidas.store') }}"
        x-data="{ amistoso: @js((bool) old('amistoso', !old('campeonato_id'))), loading: false }"
        @submit="loading = true"
        class="space-y-6"
    >
        @csrf

        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-5">
            <div>
                <h2 class="text-lg font-bold">1. Competição</h2>
                <p class="text-sm text-brand-ice/50 mt-1">Marque como amistoso ou vincule a um campeonato.</p>
            </div>

            <label class="flex items-center gap-3 p-4 rounded-2xl bg-brand-black/30 border border-brand-ice/10 cursor-pointer hover:bg-brand-ice/5 transition">
                <input
                    type="checkbox"
                    name="amistoso"
                    value="1"
                    x-model="amistoso"
                    class="rounded border-brand-ice/30 text-brand-orange focus:ring-brand-orange"
                >
                <div>
                    <span class="font-medium text-brand-ice">Partida amistosa</span>
                    <span class="block text-xs text-brand-urban mt-0.5">Sem vínculo com campeonato</span>
                </div>
            </label>

            <div x-show="!amistoso" x-transition>
                <label for="campeonato_id" class="text-sm font-medium text-brand-ice/80 mb-2 block">Campeonato</label>
                <select
                    id="campeonato_id"
                    name="campeonato_id"
                    :disabled="amistoso"
                    class="w-full p-4 rounded-2xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice focus:border-brand-purple focus:ring-2 focus:ring-brand-purple/25 outline-none transition disabled:opacity-50"
                >
                    <option value="">Selecione o campeonato...</option>
                    @foreach ($campeonatos as $campeonato)
                        <option value="{{ $campeonato->id }}" @selected(old('campeonato_id') == $campeonato->id)>
                            {{ $campeonato->nome }}
                        </option>
                    @endforeach
                </select>
                @error('campeonato_id')
                    <p class="mt-2 text-sm text-brand-orange">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold">2. Confronto e status</h2>
                <p class="text-sm text-brand-ice/50 mt-1">Os times não podem ser iguais.</p>
            </div>

            <x-partidas.status-select :selected="old('status', \App\Models\Partida::STATUS_EM_ANDAMENTO)" />

            <x-partidas.form-times
                :times-for-select="$timesForSelect"
                :casa="null"
                :fora="null"
                :readonly-teams="false"
            >
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button
                        type="submit"
                        :disabled="loading"
                        class="flex-1 py-4 rounded-2xl font-bold bg-brand-gradient shadow-lg hover:opacity-90 transition disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                        <span x-show="!loading">Criar e abrir súmula</span>
                        <span x-show="loading" x-cloak>Salvando...</span>
                    </button>
                    <a href="{{ route('partidas.index') }}" class="sm:w-auto px-6 py-4 rounded-2xl text-center bg-brand-ice/5 border border-brand-ice/10 hover:bg-brand-ice/10 transition">
                        Cancelar
                    </a>
                </div>
            </x-partidas.form-times>
        </div>
    </form>
</div>

<style>[x-cloak] { display: none !important; }</style>

@endsection
