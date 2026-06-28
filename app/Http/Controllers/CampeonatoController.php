<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CalculaEstatisticasCampeonato;
use App\Models\Campeonato;
use App\Models\EventosPartida;
use App\Models\Grupo;
use App\Models\Partida;
use App\Models\Time;
use App\Models\Jogador;
use Illuminate\Http\Request;

class CampeonatoController extends Controller
{
    use CalculaEstatisticasCampeonato;

    public function index()
    {
        $campeonatos = Campeonato::withCount(['times', 'partidas'])
            ->withCount(['partidas as partidas_finalizadas_count' => fn ($q) => $q->where('status', Partida::STATUS_FINALIZADA)])
            ->latest()
            ->get();

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
                ->finalizadas()
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
                'nome' => 'Grupo ' . $letras[$i],
            ]);

            $grupos[] = $grupo;
        }

        foreach ($times as $index => $time) {
            $grupoIndex = $index % $quantidadeGrupos;

            $grupos[$grupoIndex]->times()->attach($time->id);
        }

        return $this->redirectEditGrupos($campeonato, 'Grupos gerados com sucesso');
    }

    public function edit(Campeonato $campeonato)
    {
        $campeonato->load([
            'times',
            'grupos.times',
            'partidas' => fn ($query) => $query
                ->with(['timeCasa', 'timeFora'])
                ->orderByDesc('id'),
        ]);

        $timesEmGrupoIds = $campeonato->grupos
            ->flatMap(fn ($grupo) => $grupo->times)
            ->pluck('id');

        $timesSemGrupo = $campeonato->times
            ->whereNotIn('id', $timesEmGrupoIds)
            ->values();

        return view('campeonatos.edit', [
            'campeonato' => $campeonato,
            'timesSemGrupo' => $timesSemGrupo,
            'classificacao' => $campeonato->formato === 'grupos' ? $this->gerarClassificacaoGrupos($campeonato) : $this->gerarClassificacao($campeonato),
            'artilheiros' => $this->buscarArtilheiros($campeonato),
            'assistencias' => $this->buscarAssistencias($campeonato),
            'classificados' => $campeonato->formato === 'mata_mata' ? $this->gerarClassificados($campeonato) : null,
        ]);
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
                'status' => Partida::STATUS_EM_ANDAMENTO,
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

        return $this->redirectEditGrupos($campeonato, 'Grupo criado com sucesso.');
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
            return redirect()
                ->route('campeonatos.edit', ['campeonato' => $grupo->campeonato, 'tab' => 'grupos'])
                ->withErrors(['time_id' => 'Esse time já está em outro grupo.']);
        }


        $grupo->times()->attach($request->time_id);

        return $this->redirectEditGrupos($grupo->campeonato, 'Time adicionado ao grupo.');
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
            return redirect()
                ->route('campeonatos.edit', ['campeonato' => $grupo->campeonato, 'tab' => 'grupos'])
                ->withErrors(['time' => 'Não é possível remover um time com partidas geradas.']);
        }

        $grupo->times()->detach($time->id);

        return $this->redirectEditGrupos($grupo->campeonato, 'Time removido do grupo.');
    }

    public function destroyGrupo(Grupo $grupo)
    {
        if ($grupo->times()->exists()) {
            return redirect()
                ->route('campeonatos.edit', ['campeonato' => $grupo->campeonato, 'tab' => 'grupos'])
                ->withErrors(['grupo' => 'Não é possível excluir um grupo com times.']);
        }
        $campeonato = $grupo->campeonato;
        $grupo->delete();

        return $this->redirectEditGrupos($campeonato, 'Grupo excluído.');
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
                        'status' => Partida::STATUS_EM_ANDAMENTO,
                    ]);
                }
            }
        }

        return $this->redirectEditGrupos($campeonato, 'Partidas geradas com sucesso');
    }

    public function storePartida(Request $request, Campeonato $campeonato)
    {
        $campeonato->load('times');

        $rules = [
            'time_casa_id' => 'required|exists:times,id|different:time_fora_id',
            'time_fora_id' => 'required|exists:times,id',
            'status' => 'required|in:finalizada,ao_vivo,em_andamento',
        ];

        if ($campeonato->formato === 'mata_mata') {
            $rules['fase'] = 'required|in:oitavas,quartas,semi,final';
        }

        $data = $request->validate($rules);

        $timeIds = $campeonato->times->pluck('id');

        if (!$timeIds->contains($data['time_casa_id']) || !$timeIds->contains($data['time_fora_id'])) {
            return redirect()
                ->route('campeonatos.edit', ['campeonato' => $campeonato, 'tab' => 'partidas'])
                ->withErrors(['times' => 'Os dois times precisam estar inscritos neste campeonato.']);
        }

        $fase = $data['fase'] ?? ($campeonato->formato === 'grupos' ? 'grupos' : null);

        Partida::create([
            'campeonato_id' => $campeonato->id,
            'time_casa_id' => $data['time_casa_id'],
            'time_fora_id' => $data['time_fora_id'],
            'fase' => $fase,
            'gols_casa' => 0,
            'gols_fora' => 0,
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('campeonatos.edit', ['campeonato' => $campeonato, 'tab' => 'partidas'])
            ->with('success', 'Partida criada com sucesso.');
    }

    public function gerarMataMata(Campeonato $campeonato) {
        $jaExiste = Partida::where('campeonato_id', $campeonato)
            ->where('fase', 'mata')
            ->exists();

        if ($jaExiste) {
            return back()->with('error', 'Mata-mata já foi gerado.');
        }

        $pendentes = Partida::where('campeonato_id', $campeonato->id)
            ->where('fase', 'grupos')
            ->naoFinalizadas()
            ->exists();

        if ($pendentes) {
            return back()->with('error', 'Ainda existem partidas não finalizadas.');
        }

        $confrontos = $this->gerarClassificados($campeonato);

        if (empty($confrontos)) {
            return back()->with('error', 'Não foi possível gerar confrontos.');
        }

        foreach ($confrontos as $confronto) {
            Partida::create([
                'campeonato_id' => $campeonato->id,
                'time_casa_id' => $confronto['casa']->id,
                'time_fora_id' => $confronto['fora']->id,
                'fase' => 'mata_mata',
                'gols_casa' => 0,
                'gols_fora' => 0,
                'status' => Partida::STATUS_EM_ANDAMENTO,
            ]);
        }

        return back()->with('success', 'Mata-mata gerado com sucesso');
    }

    private function redirectEditGrupos(Campeonato $campeonato, string $message)
    {
        return redirect()
            ->route('campeonatos.edit', ['campeonato' => $campeonato, 'tab' => 'grupos'])
            ->with('success', $message);
    }

    private function gerarClassificados(Campeonato $campeonato)
    {
        $classificacao = $this->gerarClassificacaoGrupos($campeonato);

        $grupos = $campeonato->grupos->values();

        $mataMata = [];

        for ($i = 0; $i < $grupos->count(); $i += 2) {
            $grupoA = $grupos[$i] ?? null;
            $grupoB = $grupos[$i + 1] ?? null;

            if (!$grupoA || !$grupoB) {
                continue;
            }

            $classA = $classificacao[$grupoA->id] ?? [];
            $classB = $classificacao[$grupoB->id] ?? [];

            if (count($classA) < 2 || count($classB) < 2) {
                continue;
            }

            $mataMata[] = [
                'casa' => $classA[0]['time'],
                'fora' => $classB[1]['time'],

                'grupo_casa' => $grupoA->nome,
                'grupo_fora' => $grupoB->nome,

                'label' => 'Confronto'
            ];

            $mataMata[] = [
                'casa' => $classB[0]['time'],
                'fora' => $classA[1]['time'],

                'grupo_casa' => $grupoB->nome,
                'grupo_fora' => $grupoA->nome,

                'label' => 'Confronto'
            ];
        }

        return $mataMata;
    }
}
