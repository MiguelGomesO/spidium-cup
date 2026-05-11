@extends('layouts.app')

@section('content')

<h1 class="text-xl font-bold mb-6">Novo time</h1>

<form method="POST" action="{{ route('times.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <input name="nome" placeholder="Nome do time" class="w-full p-3 rounded bg-black/40 border border-white/10">

    <input name="logo" type="file" class="w-full p-3 rounded bg-black/40 border border-white/10">

    <input name="uniforme" type="file" class="w-full p-3 rounded bg-black/40 border border-white/10">

    <input name="estadio" type="file" class="w-full p-3 rounded bg-black/40 border border-white/10">

    <button class="bg-green-500 px-4 py-2 rounded">
        Criar time
    </button>
</form>

@endsection
