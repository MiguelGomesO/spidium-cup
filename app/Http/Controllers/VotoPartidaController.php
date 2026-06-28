<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CalculaVotacaoMvp;
use App\Http\Requests\StoreVotoPartidaRequest;
use App\Models\Jogador;
use App\Models\Partida;
use App\Services\Voting\Exceptions\AlreadyVotedException;
use App\Services\Voting\VoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class VotoPartidaController extends Controller
{
    use CalculaVotacaoMvp;

    public function store(
        StoreVotoPartidaRequest $request,
        Partida $partida,
        VoteService $voteService,
    ): JsonResponse {
        $data = $request->validated();
        $jogador = Jogador::findOrFail($data['jogador_id']);

        try {
            $voteService->registerVote(
                $partida,
                $jogador,
                $data['visitor_id'],
            );
        } catch (AlreadyVotedException $exception) {
            return response()->json([
                ...$this->estatisticasVotacao($partida),
                'message' => $exception->getMessage(),
                'ja_votou' => true,
            ], 422);
        } catch (\InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'voto' => $exception->getMessage(),
            ]);
        }

        return response()->json([
            ...$this->estatisticasVotacao($partida),
            'message' => 'Voto registrado com sucesso!',
            'ja_votou' => true,
            'jogador_votado_id' => $jogador->id,
        ]);
    }
}
