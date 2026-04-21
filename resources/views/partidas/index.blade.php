@extends('layouts.app')

@section('content')

<div class="space-y-4">
    <a href="{{ route('partidas.create') }}" class="bg-purple-600 px-4 py-2 rounded">
        + Nova Partida
    </a>
    @foreach($partidas as $p)
    <a href="{{ route('partidas.show', $p->id) }}" class="block">
        <div class="bg-black/40 backdrop-blur-xl border border-white/10 p-4 rounded-xl flex justify-between items-center hover:bg-white/10 transition cursor-pointer">
            <div>
                <p class="text-xs text-white/50">{{ $p->data }}</p>

                <div class="flex items-center font-semibold gap-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('storage/' . $p->timeCasa->logo) }}" class="w-6 h-6 object-contain">
                        <span>{{ $p->timeCasa->nome }}</span>
                    </div>

                    <span class="text-lg font-bold">{{ $p->gols_casa ?? '-' }} x {{ $p->gols_fora ?? '-'}}</span>

                    <div class="flex items-center gap-2">
                        <span>{{ $p->timeFora->nome }}</span>
                        <img src="{{ asset('storage/' . $p->timeFora->logo) }}" class="w-6 h-6 object-contain">
                    </div>
                </div>
            </div>

            <div class="text-xs text-white/50">{{ $p->campeonato->nome ?? 'Amistoso' }}</div>
        </div>
    </a>
    @endforeach
</div>

@endsection
