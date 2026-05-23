@extends('layouts.app')

@section('page-title', 'Editar partida')
@section('title', 'Editar partida')

@section('content')

@php
    $casa = [
        'id' => $partida->timeCasa->id,
        'nome' => $partida->timeCasa->nome,
        'logo' => $partida->timeCasa->logo ? asset('storage/' . $partida->timeCasa->logo) : null,
    ];
    $fora = [
        'id' => $partida->timeFora->id,
        'nome' => $partida->timeFora->nome,
        'logo' => $partida->timeFora->logo ? asset('storage/' . $partida->timeFora->logo) : null,
    ];
@endphp

<div class="page max-w-5xl">
    <a href="{{ route('partidas.index') }}" class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice transition">
        <span aria-hidden="true">←</span>
        Voltar às partidas
    </a>

    <div class="relative overflow-hidden rounded-3xl border border-brand-ice/10 bg-brand-surface p-8 lg:p-10">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-blue/10 via-brand-purple/10 to-brand-orange/5"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-4">
                    Editar partida
                </span>
                <h1 class="text-3xl font-black text-brand-gradient">
                    {{ $partida->timeCasa->nome }} × {{ $partida->timeFora->nome }}
                </h1>
                <p class="mt-2 text-brand-ice/60">
                    Placar atual: <strong class="text-brand-orange-sand">{{ $partida->gols_casa }} × {{ $partida->gols_fora }}</strong>
                </p>
            </div>
            <x-partida-status-badge :partida="$partida" :pulse="true" class="shrink-0" />
        </div>
    </div>

    @if ($partida->isFinalizada())
        <div class="bg-brand-orange/10 border border-brand-orange/25 text-brand-orange-sand text-sm rounded-2xl px-5 py-4">
            Esta partida está finalizada. Você pode alterar campeonato e status, mas os times não podem mais ser trocados.
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('partidas.update', $partida) }}"
        x-data="{ amistoso: {{ $partida->campeonato_id ? 'false' : 'true' }}, loading: false }"
        @submit="loading = true"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-5">
            <div>
                <h2 class="text-lg font-bold">Competição</h2>
            </div>

            <label class="flex items-center gap-3 p-4 rounded-2xl bg-brand-black/30 border border-brand-ice/10 cursor-pointer">
                <input type="checkbox" name="amistoso" value="1" x-model="amistoso" class="rounded border-brand-ice/30 text-brand-orange focus:ring-brand-orange">
                <span class="font-medium">Partida amistosa</span>
            </label>

            <div x-show="!amistoso" x-transition>
                <label for="campeonato_id" class="text-sm font-medium text-brand-ice/80 mb-2 block">Campeonato</label>
                <select
                    id="campeonato_id"
                    name="campeonato_id"
                    class="w-full p-4 rounded-2xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice focus:border-brand-purple focus:ring-2 focus:ring-brand-purple/25 outline-none transition"
                >
                    <option value="">Selecione...</option>
                    @foreach ($campeonatos as $campeonato)
                        <option value="{{ $campeonato->id }}" @selected(old('campeonato_id', $partida->campeonato_id) == $campeonato->id)>
                            {{ $campeonato->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold">Confronto e status</h2>
            </div>

            <x-partidas.status-select :selected="old('status', $partida->status)" />

            <x-partidas.form-times
                :times-for-select="$timesForSelect"
                :casa="$casa"
                :fora="$fora"
                :readonly-teams="$partida->isFinalizada()"
            >
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button
                        type="submit"
                        :disabled="loading"
                        class="flex-1 py-4 rounded-2xl font-bold bg-brand-gradient hover:opacity-90 transition disabled:opacity-50"
                    >
                        <span x-show="!loading">Salvar alterações</span>
                        <span x-show="loading" x-cloak>Salvando...</span>
                    </button>
                    <a href="{{ route('partidas.show', $partida) }}" class="sm:w-auto px-6 py-4 rounded-2xl text-center bg-brand-ice/5 border border-brand-ice/10 hover:bg-brand-ice/10 transition">
                        Ver súmula
                    </a>
                </div>
            </x-partidas.form-times>
        </div>
    </form>
</div>

<style>[x-cloak] { display: none !important; }</style>

@endsection
