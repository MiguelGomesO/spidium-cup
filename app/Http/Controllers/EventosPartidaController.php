<?php

namespace App\Http\Controllers;

use App\Models\EventosPartida;
use App\Models\ParticipacaoPartida;
use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventosPartidaController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'partida_id' => 'required|exists:partidas,id',
            'jogador_id' => 'required|exists:jogadores,id',
            'tipo' => 'required|in:gol,cartao_amarelo,cartao_vermelho',
            'assistencia_id' => 'nullable|exists:jogadores,id',
            'time_id' => 'required|in:casa,fora',
            'minuto' => 'required|integer|min:1|max:90',
            'relacionado_id' => 'nullable|exists:eventos_partida,id'
        ]);


        $expulso = EventosPartida::where('partida_id', $request->partida_id)
            ->where('jogador_id', $request->jogador_id)
            ->where('tipo', 'cartao_vermelho')
            ->where('minuto', '<=', $request->minuto)
            ->exists();

        if($expulso) {
            throw ValidationException::withMessages([
                'jogador_id' => 'Jogador já foi expulso e não pode mais participar da partida.'
            ]);
            return;
        }

        $evento = EventosPartida::create([
            'partida_id' => $request->partida_id,
            'jogador_id' => $request->jogador_id,
            'assistencia_id' => $request->assistencia_id,
            'tipo' => $request->tipo,
            'time_id' => $request->time_id,
            'minuto' => $request->minuto,
        ]);

        ParticipacaoPartida::firstOrCreate([
            'partida_id' => $request->partida_id,
            'jogador_id' => $request->jogador_id,
        ]);

        if ($request->filled('assistencia_id')) {
            ParticipacaoPartida::firstOrCreate([
                'partida_id' => $request->partida_id,
                'jogador_id' => $request->assistencia_id,
            ]);
        }

        if($data['tipo'] === 'gol') {
            $this->atualizarPlacar($data['partida_id']);
        }

        $partida = Partida::findOrFail($request->partida_id);

        return response()->json([
            'success' => true,
            'evento' => $evento->load(['jogador', 'assistencia']),
            'placar' => [
                'casa' => $partida->gols_casa,
                'fora' => $partida->gols_fora,
            ]
        ]);
    }

    public function destroy(EventosPartida $evento)
    {

        $partida = $evento->partida;

        if($evento->tipo === 'gol') {
            if ($evento->jogador->time_id === $partida->time_casa_id) {
                $partida->decrement('gols_casa');
            } else {
                $partida->decrement('gols_fora');
            }
        }

        $evento->delete();

        return response()->json([
            'success' => true,
            'placar' => [
                'casa' => $partida->gols_casa,
                'fora' => $partida->gols_fora,
            ]
        ]);
    }

    public function atualizarPlacar($partidaId)
    {
        $partida = Partida::with('eventos')->find($partidaId);

        $golsTimeA = $partida->eventos->where('tipo', 'gol')->where('time_id', 'casa')->count();

        $golsTimeB = $partida->eventos->where('tipo', 'gol')->where('time_id', 'fora')->count();

        $partida->update([
            'gols_casa' => $golsTimeA,
            'gols_fora' => $golsTimeB,
        ]);
    }
}
