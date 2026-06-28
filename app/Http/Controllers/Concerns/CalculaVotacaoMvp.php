<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Jogador;
use App\Models\Partida;
use App\Models\VotoPartida;
use App\Services\Voting\VoteService;

trait CalculaVotacaoMvp
{
    /**
     * @return array{ranking: array<int, array<string, mixed>>, mvp: ?array<string, mixed>, total_votos: int, ip_ja_votou: bool, ja_votou: bool}
     */
    protected function estatisticasVotacao(Partida $partida): array
    {
        $partida->loadMissing(['timeCasa', 'timeFora']);

        $contagem = VotoPartida::query()
            ->where('partida_id', $partida->id)
            ->selectRaw('jogador_id, COUNT(*) as total')
            ->groupBy('jogador_id')
            ->orderByDesc('total')
            ->orderBy('jogador_id')
            ->get();

        $jogadores = Jogador::query()
            ->whereIn('id', $contagem->pluck('jogador_id'))
            ->get()
            ->keyBy('id');

        $ranking = $contagem->map(function ($row) use ($jogadores, $partida) {
            $jogador = $jogadores->get($row->jogador_id);

            return [
                'jogador_id' => (int) $row->jogador_id,
                'nome' => $jogador?->nome ?? 'Jogador',
                'time_id' => $jogador?->time_id,
                'time_nome' => $jogador?->time_id === $partida->time_casa_id
                    ? $partida->timeCasa->nome
                    : $partida->timeFora->nome,
                'total' => (int) $row->total,
            ];
        })->values()->all();

        $mvp = $ranking[0] ?? null;

        if ($mvp && count($ranking) > 1 && $ranking[1]['total'] === $mvp['total']) {
            $empatados = array_values(array_filter(
                $ranking,
                fn (array $item) => $item['total'] === $mvp['total']
            ));
            $mvp['empate'] = true;
            $mvp['empatados'] = $empatados;
        }

        /** @var VoteService $voteService */
        $voteService = app(VoteService::class);
        $jaVotou = $voteService->hasVoteByIpHash($partida);

        return [
            'ranking' => $ranking,
            'mvp' => $mvp,
            'total_votos' => (int) $contagem->sum('total'),
            'ip_ja_votou' => $jaVotou,
            'ja_votou' => $jaVotou,
        ];
    }
}
