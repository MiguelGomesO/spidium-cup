@extends('layouts.app')

@section('page-title', 'Campeonatos')
@section('title', 'Campeonatos')

@section('content')

<div class="page">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="page-title">Campeonatos</h1>
            <p class="page-subtitle">Gerencie seus torneios</p>
        </div>
        <a href="{{ route('campeonatos.create') }}" class="btn-brand w-full sm:w-auto shrink-0">
            + Novo campeonato
        </a>
    </div>

    {{-- Mobile: cards --}}
    <div class="md:hidden space-y-3">
        @foreach ($campeonatos as $c)
            <article class="section-card">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <h2 class="font-bold text-lg">{{ $c->nome }}</h2>
                    <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-semibold
                        @if ($c->formato === 'liga') bg-brand-blue/30 text-brand-blue-light
                        @elseif ($c->formato === 'grupos') bg-brand-purple/20 text-brand-lilac
                        @else bg-brand-orange/20 text-brand-orange-sand @endif">
                        {{ ucfirst(str_replace('_', ' ', $c->formato)) }}
                    </span>
                </div>
                <p class="text-sm text-brand-urban mb-4">{{ $c->times->count() }}/{{ $c->qtd_times }} times · {{ $c->created_at->format('d/m/Y') }}</p>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('campeonatos.show', $c) }}" class="btn-brand text-sm">Entrar</a>
                    <form action="{{ route('campeonatos.destroy', $c) }}" method="POST" onsubmit="return confirm('Excluir campeonato?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full min-h-[44px] rounded-xl bg-brand-orange/10 text-brand-orange-sand text-sm font-medium">Excluir</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Desktop: tabela --}}
    <div class="hidden md:block section-card p-0 overflow-hidden">
        <div class="table-scroll">
            <table class="min-w-[720px]">
                <thead class="bg-brand-ice/5 border-b border-brand-ice/10 text-left text-sm text-brand-ice/70">
                    <tr>
                        <th class="px-4 lg:px-6 py-4">Campeonato</th>
                        <th class="px-4 lg:px-6 py-4">Formato</th>
                        <th class="px-4 lg:px-6 py-4">Times</th>
                        <th class="px-4 lg:px-6 py-4">Criado em</th>
                        <th class="px-4 lg:px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campeonatos as $c)
                        <tr class="border-b border-brand-ice/5 hover:bg-brand-ice/[0.08] transition">
                            <td class="px-4 lg:px-6 py-4 font-semibold">{{ $c->nome }}</td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($c->formato === 'liga') bg-brand-blue/30 text-brand-blue-light
                                    @elseif ($c->formato === 'grupos') bg-brand-purple/20 text-brand-lilac
                                    @else bg-brand-orange/20 text-brand-orange-sand @endif">
                                    {{ ucfirst(str_replace('_', ' ', $c->formato)) }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-brand-ice/80">{{ $c->times->count() }}/{{ $c->qtd_times }}</td>
                            <td class="px-4 lg:px-6 py-4 text-brand-ice/60 text-sm">{{ $c->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 lg:px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('campeonatos.show', $c) }}" class="btn-brand text-sm py-2 min-h-[40px]">Entrar</a>
                                    <form action="{{ route('campeonatos.destroy', $c) }}" method="POST" onsubmit="return confirm('Excluir?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="min-h-[40px] px-3 rounded-xl bg-brand-orange/10 text-brand-orange-sand">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
