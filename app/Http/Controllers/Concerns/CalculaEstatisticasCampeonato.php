<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Campeonato;
use App\Models\Jogador;
use App\Models\Partida;

trait CalculaEstatisticasCampeonato
{
    protected function gerarClassificacao(Campeonato $campeonato)
    {
        $tabela = [];

        foreach ($campeonato->times as $time) {
            $partidas = Partida::where('campeonato_id', $campeonato->id)
                ->where('finalizada', true)
                ->where(function ($q) use ($time) {
                    $q->where('time_casa_id', $time->id)
                        ->orWhere('time_fora_id', $time->id);
                })
                ->get();

            $dados = [
                'time' => $time,
                'pontos' => 0,
                'jogos' => 0,
                'vitorias' => 0,
                'empates' => 0,
                'derrotas' => 0,
                'gp' => 0,
                'gc' => 0,
                'sg' => 0,
            ];

            foreach ($partidas as $partida) {
                $dados['jogos']++;

                $golsPro = $partida->time_casa_id === $time->id ? $partida->gols_casa : $partida->gols_fora;
                $golsContra = $partida->time_casa_id === $time->id ? $partida->gols_fora : $partida->gols_casa;

                $dados['gp'] += $golsPro;
                $dados['gc'] += $golsContra;

                if ($golsPro > $golsContra) {
                    $dados['vitorias']++;
                    $dados['pontos'] += 3;
                } elseif ($golsPro === $golsContra) {
                    $dados['empates']++;
                    $dados['pontos'] += 1;
                } else {
                    $dados['derrotas']++;
                }
            }

            $dados['sg'] = $dados['gp'] - $dados['gc'];

            $tabela[] = $dados;
        }

        usort($tabela, function ($a, $b) {
            return [
                $b['pontos'],
                $b['sg'],
                $b['gp'],
            ] <=> [
                $a['pontos'],
                $a['sg'],
                $a['gp'],
            ];
        });

        return $tabela;
    }

    protected function buscarArtilheiros(Campeonato $campeonato)
    {
        return Jogador::with('time')
            ->whereHas('time.campeonatos', function ($q) use ($campeonato) {
                $q->where('campeonato_id', $campeonato->id);
            })
            ->withCount([
                'eventos as gols' => function ($q) use ($campeonato) {
                    $q->where('tipo', 'gol')
                        ->whereHas('partida', function ($partida) use ($campeonato) {
                            $partida->where('campeonato_id', $campeonato->id);
                        });
                },
            ])
            ->having('gols', '>', 0)
            ->orderByDesc('gols')
            ->take(10)
            ->get();
    }

    protected function buscarAssistencias(Campeonato $campeonato)
    {
        return Jogador::with('time')
            ->whereHas('time.campeonatos', function ($q) use ($campeonato) {
                $q->where('campeonato_id', $campeonato->id);
            })
            ->withCount([
                'eventosComoAssistencia as assistencias' => function ($q) use ($campeonato) {
                    $q->where('tipo', 'gol')
                        ->whereHas('partida', function ($partida) use ($campeonato) {
                            $partida->where('campeonato_id', $campeonato->id);
                        });
                },
            ])
            ->having('assistencias', '>', 0)
            ->orderByDesc('assistencias')
            ->take(10)
            ->get();
    }

    protected function gerarClassificacaoGrupos(Campeonato $campeonato)
    {
        $classificacao = [];

        foreach ($campeonato->grupos as $grupo) {
            $tabela = [];

            foreach ($grupo->times as $time) {
                $partidas = Partida::where('campeonato_id', $campeonato->id)
                    ->where('finalizada', true)
                    ->where(function ($q) use ($time) {
                        $q->where('time_casa_id', $time->id)
                            ->orWhere('time_fora_id', $time->id);
                    })
                    ->get();

                $dados = [
                    'time' => $time,
                    'pontos' => 0,
                    'jogos' => 0,
                    'vitorias' => 0,
                    'empates' => 0,
                    'derrotas' => 0,
                    'gp' => 0,
                    'gc' => 0,
                    'sg' => 0,
                ];

                foreach ($partidas as $partida) {
                    $dados['jogos']++;

                    $golsPro = $partida->time_casa_id === $time->id ? $partida->gols_casa : $partida->gols_fora;
                    $golsContra = $partida->time_casa_id === $time->id ? $partida->gols_fora : $partida->gols_casa;

                    $dados['gp'] += $golsPro;
                    $dados['gc'] += $golsContra;

                    if ($golsPro > $golsContra) {
                        $dados['vitorias']++;
                        $dados['pontos'] += 3;
                    } elseif ($golsPro === $golsContra) {
                        $dados['empates']++;
                        $dados['pontos'] += 1;
                    } else {
                        $dados['derrotas']++;
                    }
                }

                $dados['sg'] = $dados['gp'] - $dados['gc'];

                $tabela[] = $dados;
            }

            usort($tabela, function ($a, $b) {
                return [
                    $b['pontos'],
                    $b['sg'],
                    $b['gp'],
                ] <=> [
                    $a['pontos'],
                    $a['sg'],
                    $a['gp'],
                ];
            });
            $classificacao[$grupo->id] = $tabela;
        }

        return $classificacao;
    }
}
