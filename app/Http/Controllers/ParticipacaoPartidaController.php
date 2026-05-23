<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\ParticipacaoPartida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ParticipacaoPartidaController extends Controller
{
    public function sync(Request $request, Partida $partida)
    {
        $partida->load(['timeCasa.jogadores', 'timeFora.jogadores']);

        $jogadorIds = $partida->timeCasa->jogadores
            ->merge($partida->timeFora->jogadores)
            ->pluck('id')
            ->all();

        $validated = $request->validate([
            'participantes' => 'nullable|array',
            'participantes.*' => ['integer', Rule::in($jogadorIds)],
            'notas' => 'nullable|array',
            'notas.*' => 'nullable|numeric|min:0|max:10',
            'mvp_jogador_id' => ['nullable', 'integer', Rule::in($jogadorIds)],
        ]);

        $participantes = collect($validated['participantes'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        $notas = $validated['notas'] ?? [];
        $mvpId = isset($validated['mvp_jogador_id']) ? (int) $validated['mvp_jogador_id'] : null;

        if ($mvpId && ! $participantes->contains($mvpId)) {
            $participantes->push($mvpId);
        }

        DB::transaction(function () use ($partida, $participantes, $notas, $mvpId) {
            ParticipacaoPartida::where('partida_id', $partida->id)
                ->whereNotIn('jogador_id', $participantes)
                ->delete();

            foreach ($participantes as $jogadorId) {
                $nota = $notas[$jogadorId] ?? null;
                $nota = ($nota === '' || $nota === null) ? null : round((float) $nota, 2);

                ParticipacaoPartida::updateOrCreate(
                    [
                        'partida_id' => $partida->id,
                        'jogador_id' => $jogadorId,
                    ],
                    [
                        'nota' => $nota,
                        'mvp' => $mvpId === $jogadorId,
                    ]
                );
            }

            if ($mvpId) {
                ParticipacaoPartida::where('partida_id', $partida->id)
                    ->where('jogador_id', '!=', $mvpId)
                    ->update(['mvp' => false]);
            } else {
                ParticipacaoPartida::where('partida_id', $partida->id)->update(['mvp' => false]);
            }
        });

        return back()->with('success', 'Desempenho dos jogadores salvo.');
    }
}
