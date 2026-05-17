<?php

namespace App\Http\Controllers;

use App\Models\Campeonato;
use App\Models\EventosPartida;
use App\Models\Grupo;
use App\Models\Partida;
use App\Models\Time;
use Illuminate\Http\Request;

class CampeonatoController extends Controller
{
    public function index()
    {
        $campeonatos = Campeonato::latest()->get();
        return view('campeonatos.index', compact('campeonatos'));
    }

    public function create()
    {
        return view('campeonatos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'formato' => 'required|in:liga,grupos,mata_mata',
            'qtd_times' => 'required|integer|min:2',
        ]);

        if ($data['formato'] === 'grupos' && $data['qtd_times'] < 8) {
            return back()->withErrors([
                'qtd_times' => 'Campeonatos de grupos precisam de no mínimo 8 times.'
            ])->withInput();
        }

        if ($data['formato'] === 'mata_mata' && $data['qtd_times'] % 2 !== 0) {
            return back()->withErrors([
                'qtd_times' => 'Mata-Mata precisa de número par de times.'
            ])->withInput();
        }

        $campeonato = Campeonato::create($data);

        return redirect()->route('campeonatos.show', $campeonato)->with('success', 'Campeonato criado com sucesso!');
    }

    public function show(Campeonato $campeonato)
    {
        $campeonato->load('times', 'partidas.timeCasa', 'partidas.timeFora');

        $times = Time::whereNotIn('id', $campeonato->times->pluck('id'))->orderBy('nome')->get();

        return view("campeonatos.layouts.{$campeonato->formato}", compact('campeonato', 'times'));
    }

    public function update(Request $request, Campeonato $campeonato)
    {
        $data = $request->validate([
            'nome' => 'required',
            'formato' => 'required|in:liga,grupos,mata_mata',
            'qtd_times' => 'required|integer|min:2',
        ]);

        if ($data['formato'] === 'grupos' && $data['qtd_times'] < 8) {
            return back()->withErrors([
                'qtd_times' => 'Campeonatos de grupos precisam de no mínimo 8 times.'
            ])->withInput();
        }

        if ($data['formato'] === 'mata_mata' && $data['qtd_times'] % 2 !== 0) {
            return back()->withErrors([
                'qtd_times' => 'Mata-Mata precisa de número par de times.'
            ])->withInput();
        }

        $campeonato->update($data);

        return redirect()->route('campeonatos.show')->with('success', 'Campeonato atualizado!');
    }

    public function destroy(Campeonato $campeonato)
    {
        $campeonato->delete();

        return redirect()->route('campeonatos.index')->with('success', 'Campeonato Deletado!');
    }


    public function classificacao(Campeonato $campeonato)
    {
        $campeonato->load('times');

        $tabela = [];

        foreach ($campeonato->times as $time) {
            $partidas = Partida::where('campeonato_id', $campeonato->id)
                ->where(function ($q) use ($time) {
                    $q->where('time_casa_id', $time->id)
                        ->orWhere('time_fora_id', $time->id);
                })
                ->where('finalizado', true)
                ->get();

            $dados = [
                'time' => $time,
                'pontos' => 0,
                'jogos' => 0,
                'vitorias' => 0,
                'empates' => 0,
                'derrotas' => 0,
                'gols_pro' => 0,
                'gols_contra' => 0,
                'saldo' => 0,
            ];

            foreach ($partidas as $partida) {
                $dados['jogos']++;

                if ($partida->time_casa_id == $time->id) {
                    $golsPro = $partida->gols_casa;
                    $golsContra = $partida->gols_fora;
                } else {
                    $golsPro = $partida->gols_fora;
                    $golsContra = $partida->gols_casa;
                }

                $dados['gols_pro'] += $golsPro;
                $dados['gols_contra'] += $golsContra;

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

            $dados['saldo'] = $dados['gols_pro'] - $dados['gols_contra'];

            $tabela[] = $dados;
        }

        usort($tabela, function ($a, $b) {
            return [
                $b['pontos'],
                $b['vitorias'],
                $b['saldo'],
                $b['gols_pro']
            ] <=> [
                $a['pontos'],
                $a['vitorias'],
                $a['saldo'],
                $a['gols_pro']
            ];
        });

        return view('campeonatos.classificacao', compact('campeonato', 'tabela'));
    }

    public function chave(Campeonato $campeonato)
    {
        $partidas = Partida::where('campeonato_id', $campeonato->id)
            ->with('timeCasa', 'timeFora')
            ->orderBy('fase')
            ->orderBy('ordem')
            ->get()
            ->groupBy('fase');

        return view('campeonatos.chave', compact('campeonato', 'partidas'));
    }

    public function adicionarTime(Request $request, Campeonato $campeonato)
    {
        $data = $request->validate([
            'time_id' => 'required|exists:times,id',
        ]);

        if ($campeonato->times()->count() >= $campeonato->qtd_times) {
            return back()->withErrors([
                'time_id' => 'Limite de times atingido.',
            ]);
        }

        $campeonato->times()->syncWithoutDetaching([
            $data['time_id'],
        ]);

        return back()->with('success', 'Time adicionado!');
    }

    public function gerarGrupos(Campeonato $campeonato)
    {
        $campeonato->grupos()->delete();
        $times = $campeonato->times->shuffle();

        $quantidadeGrupos = max(2, floor($times->count() / 4));

        $letras = range('A', 'Z');

        $grupos = [];

        for ($i = 0; $i < $quantidadeGrupos; $i++) {
            $grupo = Grupo::create([
                'campeonato_id' => $campeonato->id,
                'nome' => 'Grupo' . $letras[$i],
            ]);

            $grupos[] = $grupo;
        }

        foreach ($times as $index => $time) {
            $grupoIndex = $index % $quantidadeGrupos;

            $grupos[$grupoIndex]->times()->attach($time->id);
        }

        return back()->with('success', 'Grupos gerados com sucesso');
    }

    public function edit(Campeonato $campeonato)
    {
        $campeonato->load([
            'times',
            'grupos.times',
            'partidas.timeCasa',
            'partidas.timeFora',
        ]);

        $artilheiros = EventosPartida::query()
            ->selectRaw('jogador_id, COUNT(*) as gols')
            ->where('tipo', 'gol')
            ->with('jogador.time')
            ->groupBy('jogador_id')
            ->orderByDesc('gols')
            ->take(5)
            ->get();

        $assistencias = EventosPartida::query()
            ->selectRaw('assistencia_id, COUNT(*) as assistencias')
            ->whereNotNull('assistencia_id')
            ->with('assistencia.time')
            ->groupBy('assistencia_id')
            ->orderByDesc('assistencias')
            ->take(5)
            ->get();

        $classificacao = [];

        foreach ($campeonato->grupos as $grupo) {
            $tabela = [];

            foreach ($grupo->times as $time) {
                $partidas = $campeonato->partidas
                    ->where('finalizada', true)
                    ->filter(function ($partida) use ($time) {
                        return $partida->time_casa_id === $time->id || $partida->time_fora_id === $time->id;
                    });

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
                    $golsPro = $partida->time_casa_id === $time->id ? $partida->gols_fora : $partida->gols_casa;

                    $golsContra = $partida->time_fora_id === $time->id ? $partida->gols_fora : $partida->gols_casa;

                    $dados['jogos']++;

                    $dados['gp'] += $golsPro;
                    $dados['gc'] += $golsContra;

                    if ($golsPro > $golsContra) {
                        $dados['vitorias']++;
                        $dados['pontos'] += 3;
                    } elseif ($golsPro < $golsContra) {
                        $dados['derrotas']++;
                    } else {
                        $dados['empates']++;
                        $dados['pontos']++;
                    }
                }

                $dados['sg'] = $dados['gp'] - $dados['gc'];

                $tabela[] = $dados;
            }

            usort($tabela, function ($a, $b) {
                return $b['pontos'] <=> $a['pontos'] ?: $b['sg'] <=> $a['sg'] ?: $b['gp'] <=> $a['gp'];
            });

            $classificacao[$grupo->nome] = $tabela;
        }

        $cleanSheets = collect();

        return view('campeonatos.edit', compact('campeonato', 'artilheiros', 'assistencias', 'cleanSheets', 'classificacao'));
    }

    public function gerarChaveamento(Campeonato $campeonato)
    {
        $times = $campeonato->times->shuffle()->values();

        $quantidade = $times->count();

        if ($quantidade < 2 || $quantidade % 2 !== 0) {
            return back()->withErrors([
                'times' => 'O mata-mata precisa de número par de times.'
            ]);
        }

        $campeonato->partidas()->delete();

        $fase = match ($quantidade) {
            2 => 'final',
            4 => 'semi',
            8 => 'quartas',
            default => 'oitavas',
        };

        for ($i = 0; $i < $quantidade; $i += 2) {
            Partida::create([
                'campeonato_id' => $campeonato->id,
                'time_casa_id' => $times[$i]->id,
                'time_fora_id' => $times[$i + 1]->id,
                'fase' => $fase,
                'data' => now()
            ]);
        }

        return back()->with('success', 'Chaveamento gerado com sucesso');
    }

    public function storeGrupo(Request $request, Campeonato $campeonato)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        Grupo::create([
            'campeonato_id' => $campeonato->id,
            'nome' => $request->nome,
        ]);

        return back();
    }

    public function adicionarTimeGrupo(Request $request, Grupo $grupo)
    {
        $request->validate([
            'time_id' => 'required|exists:times,id',
        ]);


        $jaExiste = Grupo::where('campeonato_id', $grupo->campeonato_id)
            ->whereHas('times', function ($query) use ($request) {
                $query->where('times.id', $request->time_id);
            })
            ->exists();

        if ($jaExiste) {
            return back()->withErrors([
                'time_id' => 'Esse time já está em outro grupo.'
            ]);
        }


        $grupo->times()->attach($request->time_id);

        return back();
    }

    public function removerTimeGrupo(Grupo $grupo, Time $time)
    {
        $possuiPartidas = Partida::where('campeonato_id', $grupo->campeonato_id)
            ->where(function ($query) use ($time) {
                $query->where('time_casa_id', $time->id)
                    ->orWhere('time_fora_id', $time->id);
            })
            ->exists();

        if ($possuiPartidas) {
            return back()->withErrors([
                'time' => 'Não é possível remover um time com partidas geradas.'
            ]);
        }

        $grupo->times()->detach($time->id);

        return back();
    }

    public function destroyGrupo(Grupo $grupo)
    {
        if ($grupo->times()->exists()) {
            return back()->withErrors([
                'grupo' => 'Não é possível excluir um grupo com times.'
            ]);
        }
        $grupo->delete();

        return back();
    }

    public function gerarPartidasGrupos(Campeonato $campeonato)
    {
        $campeonato->load('grupos.times');

        $campeonato->partidas()->delete();

        foreach ($campeonato->grupos as $grupo) {

            $times = $grupo->times->values();

            for ($i = 0; $i < $times->count(); $i++) {
                for ($j = $i + 1; $j < $times->count(); $j++) {
                    Partida::create([
                        'campeonato_id' => $campeonato->id,
                        'time_casa_id' => $times[$i]->id,
                        'time_fora_id' => $times[$j]->id,
                        'fase' => 'grupos',
                        'data' => now()->addDays(rand(1, 30)),
                    ]);
                }
            }
        }

        return back()->with(
            'success',
            'Partidas geradas com sucesso',
        );
    }
}
