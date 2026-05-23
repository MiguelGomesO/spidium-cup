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

    <div class="section-card p-0 sm:p-0 overflow-hidden">
        <x-ui.standings-table :rows="$tabela" />
    </div>
</div>

@endsection
