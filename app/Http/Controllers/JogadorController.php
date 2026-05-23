<?php

namespace App\Http\Controllers;

use App\Http\Requests\JogadorRequest;
use App\Models\Jogador;
use App\Models\Time;

class JogadorController extends Controller
{
    public function store(JogadorRequest $request, Time $time)
    {
        $time->jogadores()->create($request->validated());

        return back()->with('success', 'Jogador adicionado!');
    }

    public function update(JogadorRequest $request, Jogador $jogador)
    {
        $jogador->update($request->validated());

        return back()->with('success', 'Jogador atualizado!');
    }

    public function destroy(Jogador $jogador)
    {
        $jogador->delete();

        return back()->with('success', 'Jogador removido!');
    }
}
