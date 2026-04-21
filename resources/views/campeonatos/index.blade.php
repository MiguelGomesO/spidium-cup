@extends('layouts.app')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold">🏆 Campeonatos</h1>

    <a href="{{ route('campeonatos.create') }}" class="bg-gradient-to-r from-red-500 to-orange-500 px-4 py-2 rounded-lg">
        + Novo
    </a>
</div>

<div class="bg-[#020617] border border-white/10 rounded-xl p-6">

    @foreach($campeonatos as $c)
    <div class="flex justify-between items-center border-b border-white/10 py-3">

        <div>
            <p class="font-semibold">{{ $c->nome }}</p>
            <p class="text-sm text-gray-400">{{ $c->tipo }}</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('campeonatos.classificacao', $c->id) }}" class="px-3 py-1 rounded-lg bg-blue-500/20 hover:bg-blue-500/40 text-blue-300 transition">
                📊 Classificação
            </a>
            <a href="{{ route('campeonatos.chave', $c->id) }}" class="px-3 py-1 rounded-lg bg-purple-500/20 hover:bg-purple-500/40 text-purple-300 transition">
                🏆 Mata-mata
            </a>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('campeonatos.edit', $c) }}" class="text-blue-400">
                Editar
            </a>

            <form action="{{ route('campeonatos.destroy', $c) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="text-red-400">
                    Deletar
                </button>
            </form>

        </div>

    </div>
    @endforeach

</div>

@endsection
