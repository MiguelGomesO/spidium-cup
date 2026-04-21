<?php

namespace App\Http\Controllers;

use App\Models\EventosPartida;
use App\Models\Jogador;
use App\Models\Time;
use Illuminate\Http\Request;

class JogadorController extends Controller
{
    public function store(Request $request, Time $time)
    {
        $request->validate([
            'nome' => 'required',
            'posicao' => 'nullable',
            'numero' => 'nullable|integer',
        ]);

        $time->jogadores()->create([
            'nome' => $request->nome,
            'posicao' => $request->posicao,
            'numero' => $request->numero
        ]);

        return back()->with('success', 'Jogador adicionado!');
    }

    public function destroy(Jogador $jogador)
    {
        $jogador->delete();

        return back()->with('success', 'Jogador removido!');
    }
}
