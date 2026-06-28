<?php

namespace Tests\Feature;

use App\Models\Campeonato;
use App\Models\Jogador;
use App\Models\Partida;
use App\Models\Time;
use App\Models\VotoPartida;
use App\Services\Voting\VoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class VotoPartidaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{partida: Partida, jogador: Jogador, timeCasa: Time, timeFora: Time}
     */
    private function criarPartidaFinalizada(): array
    {
        $campeonato = Campeonato::create(['nome' => 'Spidium Cup 2026']);

        $timeCasa = Time::create(['nome' => 'Time Casa']);
        $timeFora = Time::create(['nome' => 'Time Fora']);

        $partida = Partida::create([
            'campeonato_id' => $campeonato->id,
            'time_casa_id' => $timeCasa->id,
            'time_fora_id' => $timeFora->id,
            'status' => Partida::STATUS_FINALIZADA,
            'data' => now(),
        ]);

        $jogador = Jogador::create([
            'time_id' => $timeCasa->id,
            'nome' => 'Jogador Teste',
            'numero' => 10,
        ]);

        return compact('partida', 'jogador', 'timeCasa', 'timeFora');
    }

    public function test_registra_voto_valido(): void
    {
        ['partida' => $partida, 'jogador' => $jogador] = $this->criarPartidaFinalizada();

        $response = $this->postJson(route('partidas.votos.store', $partida), [
            'jogador_id' => $jogador->id,
            'visitor_id' => 'visitor-valido-12345678',
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Voto registrado com sucesso!',
                'ja_votou' => true,
                'jogador_votado_id' => $jogador->id,
            ]);

        $this->assertDatabaseHas('votos_partida', [
            'partida_id' => $partida->id,
            'jogador_id' => $jogador->id,
            'visitor_id' => 'visitor-valido-12345678',
        ]);

        $voto = VotoPartida::first();
        $this->assertNotNull($voto->ip_hash);
        $this->assertNotNull($voto->user_agent);
    }

    public function test_rejeita_voto_duplicado_por_fingerprint(): void
    {
        ['partida' => $partida, 'jogador' => $jogador] = $this->criarPartidaFinalizada();

        $visitorId = 'visitor-duplicado-12345678';

        VotoPartida::create([
            'partida_id' => $partida->id,
            'jogador_id' => $jogador->id,
            'visitor_id' => $visitorId,
            'ip_hash' => hash('sha256', '10.0.0.1' . config('app.key')),
            'user_agent' => 'TestAgent/1.0',
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.2'])
            ->postJson(route('partidas.votos.store', $partida), [
                'jogador_id' => $jogador->id,
                'visitor_id' => $visitorId,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Você já votou nesta partida.',
                'ja_votou' => true,
            ]);
    }

    public function test_rejeita_voto_duplicado_por_ip(): void
    {
        ['partida' => $partida, 'jogador' => $jogador] = $this->criarPartidaFinalizada();

        $ip = '192.168.1.50';
        $ipHash = hash('sha256', $ip . config('app.key'));

        VotoPartida::create([
            'partida_id' => $partida->id,
            'jogador_id' => $jogador->id,
            'visitor_id' => 'visitor-antigo-12345678',
            'ip_hash' => $ipHash,
            'user_agent' => 'TestAgent/1.0',
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->postJson(route('partidas.votos.store', $partida), [
                'jogador_id' => $jogador->id,
                'visitor_id' => 'visitor-novo-123456789',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Você já votou nesta partida.',
                'ja_votou' => true,
            ]);
    }

    public function test_aplica_rate_limit_na_votacao(): void
    {
        ['partida' => $partida, 'jogador' => $jogador] = $this->criarPartidaFinalizada();

        RateLimiter::clear('voting');

        for ($i = 1; $i <= 5; $i++) {
            $this->postJson(route('partidas.votos.store', $partida), [
                'jogador_id' => $jogador->id,
                'visitor_id' => 'visitor-rate-' . $i . '-12345678',
            ])->assertStatus($i === 1 ? 200 : 422);
        }

        $response = $this->postJson(route('partidas.votos.store', $partida), [
            'jogador_id' => $jogador->id,
            'visitor_id' => 'visitor-rate-6-12345678',
        ]);

        $response->assertStatus(429);
    }

    public function test_retorna_404_para_partida_inexistente(): void
    {
        $response = $this->postJson('/partidas/99999/votos', [
            'jogador_id' => 1,
            'visitor_id' => 'visitor-inexistente-12345678',
        ]);

        $response->assertNotFound();
    }

    public function test_vote_service_gera_hash_de_ip(): void
    {
        $service = app(VoteService::class);

        $hash = $service->generateIpHash('127.0.0.1');

        $this->assertSame(
            hash('sha256', '127.0.0.1' . config('app.key')),
            $hash,
        );
    }
}
