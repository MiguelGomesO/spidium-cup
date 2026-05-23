<?php

namespace App\Http\Controllers;

use App\Models\Campeonato;
use App\Models\EventosPartida;
use App\Models\Jogador;
use App\Models\Partida;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'campeonatos' => Campeonato::count(),
            'times' => \App\Models\Time::count(),
            'partidas' => Partida::count(),
            'partidasFinalizadas' => Partida::finalizadas()->count(),
            'partidasAoVivo' => Partida::with(['timeCasa', 'timeFora', 'campeonato'])
                ->aoVivo()
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
            'partidasEmAndamento' => Partida::with(['timeCasa', 'timeFora', 'campeonato'])
                ->emAndamento()
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
            'ultimasResultados' => Partida::with(['timeCasa', 'timeFora', 'campeonato'])
                ->finalizadas()
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
            'artilheiros' => Jogador::with('time')
                ->comEstatisticas()
                ->withCount(['eventos as gols' => fn ($q) => $q->where('tipo', 'gol')])
                ->orderByDesc('gols')
                ->limit(5)
                ->get(),
            'eventosRecentes' => EventosPartida::with(['jogador', 'partida.timeCasa', 'partida.timeFora'])
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
