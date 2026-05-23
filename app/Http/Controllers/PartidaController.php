<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\Campeonato;
use App\Models\Jogador;
use App\Models\Time;
use Illuminate\Http\Request;

class PartidaController extends Controller
{
    public function historico(Time $time)
    {
        $partidas = Partida::with(['timeCasa', 'timeFora'])
            ->where(function($q) use ($time) {
                $q->where('time_casa_id', $time->id)
                    ->orWhere('time_fora_id', $time->id);
            })
            ->orderByDesc('data')
            ->get();

        return response()->json($partidas);
    }

    public function artilheiros(Time $time)
    {
        $artilheiros = Jogador::where('time_id', $time->id)
            ->withCount(['eventos as gols' => function ($q) {
                $q->where('tipo', 'gol');
            }])
            ->orderByDesc('gols')
            ->get();

        return response()->json($artilheiros);
    }

    public function index()
    {
        $partidas = Partida::with(['campeonato', 'timeCasa', 'timeFora'])
            ->orderByDesc('data')
            ->get();

        return view('partidas.index', compact('partidas'));
    }

    public function create()
    {
        $campeonatos = Campeonato::orderBy('nome')->get();
        $times = Time::orderBy('nome')->get();

        return view('partidas.create', [
            'campeonatos' => $campeonatos,
            'times' => $times,
            'timesForSelect' => $this->timesForSelect($times),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'campeonato_id' => 'nullable|exists:campeonatos,id',
            'time_casa_id' => 'required|different:time_fora_id|exists:times,id',
            'time_fora_id' => 'required|different:time_casa_id|exists:times,id',
            'data' => 'required|date',
        ]);

        if ($request->boolean('amistoso')) {
            $data['campeonato_id'] = null;
        }

        $partida = Partida::create($data);

        return redirect()
            ->route('partidas.show', $partida)
            ->with('success', 'Partida criada! Registre os gols na tela ao vivo.');
    }

    public function edit(Partida $partida)
    {
        $partida->load(['campeonato', 'timeCasa', 'timeFora']);
        $campeonatos = Campeonato::orderBy('nome')->get();
        $times = Time::orderBy('nome')->get();

        return view('partidas.edit', [
            'partida' => $partida,
            'campeonatos' => $campeonatos, 
            'times' => $times,
            'timesForSelect' => $this->timesForSelect($times),
        ]);
    }

    public function update(Request $request, Partida $partida)
    {
        $data = $request->validate([
            'campeonato_id' => 'nullable|exists:campeonatos,id',
            'time_casa_id' => 'required|different:time_fora_id|exists:times,id',
            'time_fora_id' => 'required|different:time_casa_id|exists:times,id',
            'data' => 'required|date',
        ]);

        if ($request->boolean('amistoso')) {
            $data['campeonato_id'] = null;
        }

        if ($partida->finalizada) {
            unset($data['time_casa_id'], $data['time_fora_id']);
        }

        $partida->update($data);

        return redirect()
            ->route('partidas.show', $partida)
            ->with('success', 'Partida atualizada com sucesso.');
    }

    private function timesForSelect($times)
    {
        return $times->map(fn ($time) => [
            'id' => $time->id,
            'nome' => $time->nome,
            'logo' => $time->logo ? asset('storage/' . $time->logo) : null,
        ])->values();
    }

    public function destroy(Partida $partida)
    {
        $partida->delete();

        return redirect()->back()->with('success', 'Partida removida');
    }

    public function show(Partida $partida)
    {
        $partida->load([
            'timeCasa.jogadores',
            'timeFora.jogadores',
            'eventos.jogador',
            'eventos.assistencia'
        ]);

        return view('partidas.show', compact('partida'));
    }

    public function finalizar(Partida $partida) {
        $partida->update([
            'finalizada' => true,
        ]);

        return back()->with('success', 'Partida finalizada com sucesso');
    }
}
