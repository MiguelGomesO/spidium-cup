<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CalculaEstatisticasCampeonato;
use App\Models\Campeonato;
use App\Models\Jogador;
use App\Models\Partida;
use App\Models\Time;

class ResultadosController extends Controller
{
    use CalculaEstatisticasCampeonato;

    public function index()
    {
        $campeonatos = Campeonato::query()
            ->withCount([
                'times',
                'partidas',
                'partidas as partidas_finalizadas_count' => fn ($q) => $q->finalizadas(),
            ])
            ->whereHas('partidas')
            ->latest()
            ->limit(3)
            ->get();

        $todasPartidas = Partida::query()
            ->with(['timeCasa', 'timeFora', 'campeonato'])
            ->withCount('momentos')
            ->finalizadas()
            ->orderByDesc('id')
            ->get();

        return view('resultados.index', [
            'campeonatos' => $campeonatos,
            'ultimasPartidas' => $todasPartidas->take(3),
            'todasPartidas' => $todasPartidas,
            'stats' => [
                'times' => Time::count(),
                'partidas' => Partida::count(),
                'jogadores' => Jogador::count(),
                'campeonatos' => Campeonato::count(),
            ],
        ]);
    }

    public function show(Campeonato $campeonato)
    {
        $campeonato->load([
            'times',
            'grupos.times',
            'partidas' => fn ($query) => $query
                ->with(['timeCasa', 'timeFora'])
                ->orderByDesc('id'),
        ]);

        $partidasPorFase = null;
        if ($campeonato->formato === 'mata_mata') {
            $partidasPorFase = Partida::where('campeonato_id', $campeonato->id)
                ->with(['timeCasa', 'timeFora'])
                ->orderBy('fase')
                ->orderBy('ordem')
                ->get()
                ->groupBy('fase');
        }

        return view('resultados.show', [
            'campeonato' => $campeonato,
            'classificacao' => $campeonato->formato === 'grupos'
                ? $this->gerarClassificacaoGrupos($campeonato)
                : ($campeonato->formato === 'liga' ? $this->gerarClassificacao($campeonato) : null),
            'artilheiros' => $this->buscarArtilheiros($campeonato),
            'partidasPorFase' => $partidasPorFase,
        ]);
    }
}
