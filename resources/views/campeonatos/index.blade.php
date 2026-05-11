@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 bg-clip-text text-transparent">
                Campeonatos
            </h1>

            <p class="text-white/50 mt-1">
                Gerencie seus campeonatos
            </p>
        </div>

        <a href="{{ route('campeonatos.create') }}" class="px-5 py-3 rounded-xl bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 font-semibold hover:opacity-90 transition shadow-lg">
            + Novo Campeonato
        </a>
    </div>

    <div class="bg-white/5 border border-white/10 rounded-2xl backdrop-blur-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-white/5 border-b border-white/10">
                <tr class="text-left text-sm text-white/60">
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
                    <tr class="border-b border-white/5 hover:bg-white/[0.30] transition">
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="font-semibold text-white">
                                    {{ $c->nome }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if ($c->formato === "liga")
                                    bg-blue-500/20 text-blue-300
                                @elseif ($c->formato === 'grupos')
                                    bg-purple-500/20 text-purple-300
                                @else
                                    bg-orange-500/20 text-orange-300
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $c->formato)) }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-white/70">
                            {{ $c->times->count() }}/{{ $c->qtd_times }}
                        </td>

                        <td class="px-6 py-5 text-white/50 text-sm">
                            {{ $c->created_at->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('campeonatos.show', $c) }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 text-sm font-semibold hover:opacity-90 transition">
                                    Entrar
                                </a>
                                <form action="{{ route('campeonatos.destroy', $c) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-300 transition">
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
