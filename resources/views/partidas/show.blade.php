@extends('layouts.app')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-2xl p-6 mb-4">

        <div class="text-center text-white/50 text-sm mb-4">
            {{ \Carbon\Carbon::parse($partida->data)->format('d/m/Y H:i') }}
        </div>

        <div class="flex items-center justify-between">

            <div class="flex flex-col items-center gap-2 w-1/3">
                @if($partida->timeCasa->logo)
                <img src="{{ asset('storage/'.$partida->timeCasa->logo) }}" class="w-16 h-16 object-contain">
                @endif

                <span class="font-semibold text-center">
                    {{ $partida->timeCasa->nome }}
                </span>
            </div>

            <div class="text-4xl font-bold text-center">
                {{ $partida->gols_casa ?? 0 }}
                <span class="text-white/50">x</span>
                {{ $partida->gols_fora ?? 0 }}
            </div>

            <div class="flex flex-col items-center gap-2 w-1/3">
                @if($partida->timeFora->logo)
                <img src="{{ asset('storage/'.$partida->timeFora->logo) }}" class="w-16 h-16 object-contain">
                @endif

                <span class="font-semibold text-center">
                    {{ $partida->timeFora->nome }}
                </span>
            </div>

        </div>

        <div class="text-center text-white/40 text-sm mt-4">
            {{ $partida->campeonta->nome ?? 'Amistoso' }}
        </div>

        <div class="flex gap-3">

            <button class="bg-green-600 px-4 py-2 rounded">
                ⚽ Adicionar Gol
            </button>

            <button class="bg-blue-600 px-4 py-2 rounded">
                📊 Eventos
            </button>

        </div>

    </div>



    <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-2xl p-6">

        <h2 class="text-lg font-semibold mb-4">Eventos da Partida</h2>

        <p class="text-white/40 text-sm">
            Nenhum evento registrado ainda.
        </p>
        
    </div>

</div>

@endsection
