<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CalculaNotasPublicas;
use App\Models\Jogador;
use App\Models\NotaPublicaPartida;
use App\Models\Partida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotaPublicaPartidaController extends Controller
{
    use CalculaNotasPublicas;

    public function store(Request $request, Partida $partida): JsonResponse
    {
        if (! $partida->isFinalizada()) {
            throw ValidationException::withMessages([
                'partida' => 'As notas só podem ser enviadas em partidas finalizadas.',
            ]);
        }

        $data = $request->validate([
            'jogador_id' => ['required', 'integer', 'exists:jogadores,id'],
            'nota' => ['required', 'numeric', 'min:0', 'max:10'],
        ]);

        $jogador = Jogador::findOrFail($data['jogador_id']);

        if (! in_array($jogador->time_id, [$partida->time_casa_id, $partida->time_fora_id], true)) {
            throw ValidationException::withMessages([
                'jogador_id' => 'Este jogador não participa desta partida.',
            ]);
        }

        $nota = round((float) $data['nota'], 2);
        $ip = $request->ip();

        $existente = NotaPublicaPartida::query()
            ->where('partida_id', $partida->id)
            ->where('jogador_id', $jogador->id)
            ->where('ip_address', $ip)
            ->exists();

        NotaPublicaPartida::updateOrCreate(
            [
                'partida_id' => $partida->id,
                'jogador_id' => $jogador->id,
                'ip_address' => $ip,
            ],
            ['nota' => $nota]
        );

        return response()->json([
            'message' => $existente
                ? 'Nota atualizada com sucesso!'
                : 'Nota registrada com sucesso!',
            'atualizada' => $existente,
            'jogador_id' => $jogador->id,
            'nota' => $nota,
            ...$this->estatisticasNotasPublicas($partida),
        ]);
    }
}
