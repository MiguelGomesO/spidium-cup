@extends('layouts.app')

@section('content')

<div class="p-8 text-white space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">
            Escalação - {{ $time->nome }}
        </h1>
    </div>

    <div class="col-span-2 relative bg-green-900/40 border border-white/10 rounded-2xl p-6 backdrop-blur-xl overflow-hidden">
        <div class="absolute inset-0 opacity-30">
            <div class="linha-horizontal w-full top-1/2"></div>
            <div class="linha-vertical h-full left-1/2"></div>
        </div>

        <div class="relative h-[500px]">
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                @include('components.player-slot', ['label' => 'GK'])
            </div>

            <div class="absolute bottom-32 w-full flex justify-around">
                @foreach(range(1, 4) as $i)
                @include('components.player-slot', ['label' => 'DEF'])
                @endforeach
            </div>

            <div class="absolute top-40 w-full flex justify-around">
                @foreach(range(1, 3) as $i)
                @include('components.player-slot', ['label' => 'MID'])
                @endforeach
            </div>

            <div class="absolute top-10 w-full flex justify-around">
                @foreach(range(1, 3) as $i)
                @include('components.player-slot', ['label' => 'ATT'])
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
