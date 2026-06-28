<?php

namespace App\Services\Voting;

use App\Models\Jogador;
use App\Models\Partida;
use App\Models\VotoPartida;
use App\Services\Voting\Exceptions\AlreadyVotedException;
use Illuminate\Http\Request;

class VoteService
{
    public function __construct(
        private readonly Request $request,
    ) {}

    public function generateIpHash(?string $ip = null): string
    {
        $ip ??= $this->request->ip() ?? '';

        return hash('sha256', $ip . config('app.key'));
    }

    public function hasAlreadyVoted(Partida $partida, string $visitorId, ?string $ipHash = null): bool
    {
        if ($this->hasVoteByVisitorId($partida, $visitorId)) {
            return true;
        }

        $ipHash ??= $this->generateIpHash();

        return $this->hasVoteByIpHash($partida, $ipHash);
    }

    public function hasVoteByVisitorId(Partida $partida, string $visitorId): bool
    {
        return VotoPartida::query()
            ->where('partida_id', $partida->id)
            ->where('visitor_id', $visitorId)
            ->exists();
    }

    public function hasVoteByIpHash(Partida $partida, ?string $ipHash = null): bool
    {
        $ipHash ??= $this->generateIpHash();

        return VotoPartida::query()
            ->where('partida_id', $partida->id)
            ->where('ip_hash', $ipHash)
            ->exists();
    }

    public function canVote(Partida $partida, string $visitorId, ?string $ipHash = null): bool
    {
        if (! $partida->isFinalizada()) {
            return false;
        }

        return ! $this->hasAlreadyVoted($partida, $visitorId, $ipHash);
    }

    /**
     * @throws AlreadyVotedException
     */
    public function registerVote(
        Partida $partida,
        Jogador $jogador,
        string $visitorId,
        ?string $userAgent = null,
        ?string $ipHash = null,
    ): VotoPartida {
        if (! $partida->isFinalizada()) {
            throw new \InvalidArgumentException('A votação só está disponível em partidas finalizadas.');
        }

        if (! in_array($jogador->time_id, [$partida->time_casa_id, $partida->time_fora_id], true)) {
            throw new \InvalidArgumentException('Este jogador não participa desta partida.');
        }

        $ipHash ??= $this->generateIpHash();
        $userAgent ??= $this->request->userAgent();

        if ($this->hasAlreadyVoted($partida, $visitorId, $ipHash)) {
            throw new AlreadyVotedException();
        }

        return VotoPartida::create([
            'partida_id' => $partida->id,
            'jogador_id' => $jogador->id,
            'visitor_id' => $visitorId,
            'ip_hash' => $ipHash,
            'user_agent' => $userAgent,
        ]);
    }
}
