<?php

namespace App\Http\Controllers;

use App\Models\Campeonato;
use App\Models\EventosPartida;
use App\Models\Jogador;
use App\Models\Partida;
use App\Models\Time;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        return view('dashboard', [
            'campeonatos' => Campeonato::count(),
            'times' => Time::count(),
            'partidas' => Partida::count(),
            'partidasFinalizadas' => Partida::where('finalizada', true)->count(),

            'partidasAoVivo' => Partida::with(['timeCasa', 'timeFora'])
            ->where('finalizada', false)
            ->whereDate('data', now()->toDateString())
            ->orderByDesc('data')
            ->take(3)
            ->get(),

            'ultimasResultados' => Partida::with(['timeCasa', 'timeFora'])
            ->where('finalizada', true)
            ->latest()
            ->take(5)
            ->get(),

            'proximasPartidas' => Partida::with(['timeCasa', 'timeFora'])
            ->where('finalizada', false)
            ->orderBy('data')
            ->take(5)
            ->get(),

            'artilheiros' => Jogador::withCount([
                'eventos as gols' => function ($q) {
                    $q->where('tipo', 'gol');
                }
            ])
            ->having('gols', '>', 0)
            ->orderByDesc('gols')
            ->take(5)
            ->get(),

            'eventosRecentes' => EventosPartida::with('jogador', 'partida.timeCasa', 'partida.timeFora')
            ->latest()
            ->take(5)
            ->get(),
        ]);
    }
}
