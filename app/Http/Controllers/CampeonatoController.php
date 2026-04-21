<?php

namespace App\Http\Controllers;

use App\Models\Campeonato;
use App\Models\Partida;
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
        $request->validate([
            'nome' => 'required',
        ]);

        $campeonato = Campeonato::create($request->all());

        if ($campeonato->formato == 'tabela') {
            $this->gerarTabela($campeonato);
        } else {
            $this->gerarMataMata($campeonato);
        }

        return redirect()->route('campeonatos.index')->with('success', 'Campeonato criado!');
    }

    public function edit(Campeonato $campeonato)
    {
        return view('campeonatos.edit', compact('campeonato'));
    }

    public function update(Request $request, Campeonato $campeonato)
    {
        $request->validate([
            'nome' => 'required',
        ]);

        $campeonato->update($request->all());

        return redirect()->route('campeonatos.index')->with('success', 'Atualizado!');
    }

    public function destroy(Campeonato $campeonato)
    {
        $campeonato->delete();

        return back()->with('success', 'Deletado!');
    }

    private function gerarTabela($campeonato)
    {

        $times = $campeonato->times;

        if ($times->count() < 2) {
            return;
        }

        $rodada = 1;

        for ($i = 0; $i < count($times); $i++) {
            for ($j = $i + 1; $j < count($times); $j++) {

                Partida::create([
                    'campeonato_id' => $campeonato->id,
                    'time_casa_id' => $times[$i]->id,
                    'time_fora_id' => $times[$j]->id,
                    'rodada' => $rodada,
                    'status' => 'agendado',
                    'data_jogo' => now()->addDays($rodada)
                ]);

                $rodada++;
            }
        }
    }

    private function gerarMataMata($campeonato)
    {
        $times = $campeonato->times->shuffle();

        if ($times->count() < 2) {
            return;
        }

        $total = $times->count();

        $fases = [
            2 => 'final',
            4 => 'semifinal',
            8 => 'quartas',
            16 => 'oitavas',
        ];

        $fase = $fases[$total] ?? 'fase_inicial';

        $jogos = $times->chunk(2);

        $ordem = 1;

        foreach ($jogos as $j) {

            Partida::create([
                'campeonato_id' => $campeonato->id,
                'time_casa_id' => $j[0]->id,
                'time_fora_id' => $j[1]->id,
                'fase' => $fase,
                'ordem' => $ordem,
                'status' => 'agendado'
            ]);

            $ordem++;
        }
    }

    public function classificacao($id)
    {
        $campeonato =  Campeonato::with('times')->findOrFail($id);

        $times = $campeonato->times;

        $tabela = [];

        foreach ($times as $t) {
            $partidas = Partida::where('campeonato_id', $id)
                ->where(function ($q) use ($t) {
                    $q->where('time_casa_id', $t->id)
                        ->orWhere('time_fora_id', $t->id);
                })
                ->where('status', 'finalizado')
                ->get();

            $dados = [
                'time' => $t,
                'pontos' => 0,
                'jogos' => 0,
                'vitorias' => 0,
                'empates' => 0,
                'derrotas' => 0,
                'gols_pro' => 0,
                'gols_contra' => 0
            ];

            foreach ($partidas as $p) {
                $dados['jogos']++;

                if ($p->time_casa_id == $t->id) {
                    $golsPro = $p->gols_casa;
                    $golsContra = $p->gols_fora;
                } else {
                    $golsPro =  $p->gols_fora;
                    $golsContra =  $p->gols_casa;
                }

                $dados['gols_pro'] += $golsPro;
                $dados['gols_contra'] += $golsContra;

                if ($golsPro > $golsContra) {
                    $dados['vitorias']++;
                    $dados['pontos'] += 3;
                } elseif ($golsPro == $golsContra) {
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

        return view('campeonatos.classificacao', compact('tabela', 'campeonato'));
    }

    public function chave($id){
        $campeonato = Campeonato::findOrFail($id);

        $partidas = \App\Models\Partida::where('campeonato_id', $id)
            ->with('timeCasa', 'timeFora')
            ->orderBy('fase')
            ->orderBy('ordem')
            ->get()
            ->groupBy('fase');

        return view('campeonatos.chave', compact('partidas', 'campeonato'));
    }
}
