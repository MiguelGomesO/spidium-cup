<?php

namespace App\Http\Controllers\Concerns;

use App\Models\NotaPublicaPartida;
use App\Models\Partida;

trait CalculaNotasPublicas
{
    /**
     * @return array{
     *     medias: array<int, array{jogador_id: int, media: float, total: int}>,
     *     minhas_notas: array<int, float>
     * }
     */
    protected function estatisticasNotasPublicas(Partida $partida): array
    {
        $agregados = NotaPublicaPartida::query()
            ->where('partida_id', $partida->id)
            ->selectRaw('jogador_id, ROUND(AVG(nota), 2) as media, COUNT(*) as total')
            ->groupBy('jogador_id')
            ->get();

        $medias = $agregados->mapWithKeys(fn ($row) => [
            (int) $row->jogador_id => [
                'jogador_id' => (int) $row->jogador_id,
                'media' => (float) $row->media,
                'total' => (int) $row->total,
            ],
        ])->all();

        $ip = request()->ip();
        $minhasNotas = [];

        if ($ip) {
            $minhasNotas = NotaPublicaPartida::query()
                ->where('partida_id', $partida->id)
                ->where('ip_address', $ip)
                ->pluck('nota', 'jogador_id')
                ->map(fn ($nota) => round((float) $nota, 2))
                ->all();
        }

        return [
            'medias' => $medias,
            'minhas_notas' => $minhasNotas,
        ];
    }
}
