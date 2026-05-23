<?php

namespace App\Http\Controllers;

use App\Models\Campeonato;
use App\Models\EventosPartida;
use App\Models\Jogador;
use App\Models\Partida;
use App\Models\Time;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'campeonatos' => Campeonato::count(),
            'times' => Time::count(),
            'partidas' => Partida::count(),
            'partidasFinalizadas' => Partida::where('finalizada', true)->count(),
            'partidasAoVivo' => Partida::with(['timeCasa', 'timeFora', 'campeonato'])
                ->where('finalizada', false)
                ->orderByDesc('data')
                ->take(5)
                ->get(),
            'ultimasResultados' => Partida::with(['timeCasa', 'timeFora', 'campeonato'])
                ->where('finalizada', true)
                ->orderByDesc('data')
                ->take(6)
                ->get(),
            'proximasPartidas' => Partida::with(['timeCasa', 'timeFora', 'campeonato'])
                ->where('finalizada', false)
                ->where('data', '>=', now())
                ->orderBy('data')
                ->take(5)
                ->get(),
            'artilheiros' => Jogador::with('time')
                ->withCount([
                    'eventos as gols' => fn ($q) => $q->where('tipo', 'gol'),
                ])
                ->having('gols', '>', 0)
                ->orderByDesc('gols')
                ->take(5)
                ->get(),
            'eventosRecentes' => EventosPartida::with(['jogador', 'partida.timeCasa', 'partida.timeFora'])
                ->where('tipo', 'gol')
                ->latest()
                ->take(6)
                ->get(),
        ]);
    }
}
