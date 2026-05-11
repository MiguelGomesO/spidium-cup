@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-6">
    <h1 class="text-xl font-bold">👥 Times</h1>

    <a href="{{ route('times.create') }}" class="bg-gradient-to-r from-blue-500 to-cyan-500 px-4 py-2 rounded-lg">
        + Novo Time
    </a>
</div>

<div class="bg-[#020617] p-6 rounded-xl border border-white/10">
    @foreach($times as $t)
    <div class="flex justify-between items-center border-b border-white/10 py-3">

        <div>
            <img src="{{ asset('storage/' . $t->logo) }}" class="w-10 h-10">
        </div>

        <div>
            <p class="font-semibold">{{ $t->nome }}</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('times.edit', $t) }}" class="text-blue-400">
                Editar
            </a>

            <form method="POST" action="{{ route('times.destroy', $t) }}">
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
