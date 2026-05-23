@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold bg-brand-gradient bg-clip-text text-transparent">
                Campeonatos
            </h1>

            <p class="text-brand-ice/60 mt-1">
                Gerencie seus campeonatos
            </p>
        </div>

        <a href="{{ route('campeonatos.create') }}" class="px-5 py-3 rounded-xl bg-brand-gradient font-semibold hover:opacity-90 transition shadow-lg">
            + Novo Campeonato
        </a>
    </div>

    <div class="bg-brand-ice/5 border border-brand-ice/10 rounded-2xl backdrop-blur-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-brand-ice/5 border-b border-brand-ice/10">
                <tr class="text-left text-sm text-brand-ice/70">
                    <th class="px-6 py-4">
                        Campeonato
                    </th>

                    <th class="px-6 py-4">
                        Formato
                    </th>

                    <th class="px-6 py-4">
                        Times
                    </th>

                    <th class="px-6 py-4">
                        Criado em
                    </th>

                    <th class="px-6 py-4 text-center">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($campeonatos as $c)
                    <tr class="border-b border-brand-ice/5 hover:bg-brand-ice/[0.12] transition">
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="font-semibold text-brand-ice">
                                    {{ $c->nome }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if ($c->formato === "liga")
                                    bg-brand-blue/30 text-brand-blue-light
                                @elseif ($c->formato === 'grupos')
                                    bg-brand-purple/20 text-brand-lilac
                                @else
                                    bg-brand-orange/20 text-brand-orange-sand
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $c->formato)) }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-brand-ice/80">
                            {{ $c->times->count() }}/{{ $c->qtd_times }}
                        </td>

                        <td class="px-6 py-5 text-brand-ice/60 text-sm">
                            {{ $c->created_at->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('campeonatos.show', $c) }}" class="px-4 py-2 rounded-xl bg-brand-gradient text-sm font-semibold hover:opacity-90 transition">
                                    Entrar
                                </a>
                                <form action="{{ route('campeonatos.destroy', $c) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-2 rounded-xl bg-brand-orange/10 hover:bg-brand-orange/15 text-brand-orange-sand transition">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
