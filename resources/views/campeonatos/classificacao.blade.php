@extends('layouts.app')

@section('page-title', 'Classificação')
@section('title', 'Classificação — ' . $campeonato->nome)

@section('content')

<div class="page">
    <a href="{{ route('campeonatos.edit', $campeonato) }}" class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice transition min-h-[44px]">
        ← Voltar ao campeonato
    </a>

    <div class="section-card">
        <h1 class="page-title">🏆 Classificação</h1>
        <p class="page-subtitle">{{ $campeonato->nome }}</p>
    </div>

    <div class="section-card p-0 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-brand-ice/10 bg-gradient-to-r from-brand-purple/10 to-transparent">
            <h2 class="text-lg sm:text-xl font-bold">Tabela oficial</h2>
            <p class="text-sm text-brand-ice/50 mt-1">{{ $campeonato->nome }}</p>
        </div>
        <x-ui.standings-table :rows="$tabela" :qualifiers="4" />
    </div>
</div>

@endsection
