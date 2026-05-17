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
        $partidas = Partida::with(['campeonato', 'timeCasa', 'timeFora'])->get();
        return view('partidas.index', compact('partidas'));
    }

    public function create()
    {
        $campeonatos = Campeonato::all();
        $times = Time::all();

        return view('partidas.create', compact('campeonatos', 'times'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campeonato_id' => 'nullable|exists:campeonatos,id',
            'time_casa_id' => 'required|different:time_fora_id|exists:times,id',
            'time_fora_id' => 'required|different:time_id_casa|exists:times,id',
            'data' => 'required|date',
        ]);

        $partida = Partida::create($request->all());

        return redirect()->route('partidas.show', $partida);
    }

    public function edit(Partida $partida)
    {
        $campeonatos = Campeonato::all();
        $times = Time::all();

        return view('partidas.edit', compact('partida', 'campeonatos', 'times'));
    }

    public function update(Request $request, Partida $partida)
    {
        $partida->update($request->all());

        return redirect()->route('partidas.index')->with('success', 'Partida atualizada');
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
