@extends('layouts.app')

@section('content')

<h1 class="text-xl font-bold mb-6">Novo Campeonato</h1>

<form method="POST" action="{{ route('campeonatos.store') }}" class="space-y-4">
    @csrf

    <input name="nome" placeholder="Nome" class="w-full p-3 rounded bg-black/40 border border-white/10">

    <select name="formato" class="w-full p-3 rounded bg-black/40 border border-white/10">
        <option value="tabela">Pontos Corridos</option>
        <option value="mata_mata">Mata-Mata</option>
    </select>

    <input name="qtd_times" type="number" placeholder="Quantidade de times" class="w-full p-3 rounded bg-black/40 border border-white/10">

    <button class="bg-green-500 px-4 py-2 rounded">
        Criar Campeonato
    </button>
</form>

@endsection
