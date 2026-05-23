@extends('layouts.app')

@section('content')

<div class="p-6">
    <h1 class="text-2xl font-bold text-brand-ice mb-6">
        🏆 Classificação - {{ $campeonato->nome }}
    </h1>

    <div class="bg-brand-ice/10 backdrop-blur-lg rounded-2xl p-6 shadow-lg">
        <table class="w-full text-brand-ice">
            <thead>
                <tr class="border-b border-brand-ice/20 text-sm uppercase">
                    <th>#</th>
                    <th>Time</th>
                    <th>Pts</th>
                    <th>J</th>
                    <th>V</th>
                    <th>E</th>
                    <th>D</th>
                    <th>GP</th>
                    <th>GC</th>
                    <th>SG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tabela as $index => $t)
                <tr class="border-b border-brand-ice/10 {{ $index < 4 ? 'bg-brand-asphalt/40' : '' }} {{ $index >= count($tabela)-2 ? 'bg-brand-orange/10' : '' }}">
                    <td class="font-bold">{{ $index + 1 }}</td>
                    <td class="flex items-center gap-2">
                        <span>{{ $t['time']->nome }}</span>
                    </td>
                    <td class="text-brand-orange-sand font-bold">{{ $t['pontos'] }}</td>
                    <td>{{ $t['jogos'] }}</td>
                    <td>{{ $t['vitorias'] }}</td>
                    <td>{{ $t['empates'] }}</td>
                    <td>{{ $t['derrotas'] }}</td>
                    <td>{{ $t['gols_pro'] }}</td>
                    <td>{{ $t['gols_contra'] }}</td>
                    <td>{{ $t['saldo'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
