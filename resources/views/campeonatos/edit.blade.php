@extends('layouts.app')

@section('content')

<h1 class="text-xl font-bold mb-6">Editar Campeonato</h1>

<form method="POST" action="{{ route('campeonatos.update', $campeonato) }}">
    @csrf
    @method('PUT')

    <input name="nome" value="{{ $campeonato->nome }}" class="w-full p-3 rounded bg-black/40 border border-white/10 mb-3">
    <input name="tipo" value="{{ $campeonato->tipo }}" class="w-full p-3 rounded bg-black/40 border border-white/10 mb-3">

    <button class="bg-yellow-500 px-4 py-2 rounded">
        Atualizar
    </button>
</form>

@endsection
