<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    public const STATUS_FINALIZADA = 'finalizada';

    public const STATUS_AO_VIVO = 'ao_vivo';

    public const STATUS_EM_ANDAMENTO = 'em_andamento';

    public static function statuses(): array
    {
        return [
            self::STATUS_FINALIZADA => 'Finalizada',
            self::STATUS_AO_VIVO => 'Ao vivo',
            self::STATUS_EM_ANDAMENTO => 'Em andamento',
        ];
    }

    protected $casts = [
        'data' => 'datetime',
    ];

    protected $fillable = [
        'campeonato_id',
        'time_casa_id',
        'time_fora_id',
        'gols_casa',
        'gols_fora',
        'status',
        'data',
        'fase',
        'ordem',
    ];

    protected $attributes = [
        'status' => self::STATUS_EM_ANDAMENTO,
        'gols_casa' => 0,
        'gols_fora' => 0,
    ];

    public function isFinalizada(): bool
    {
        return $this->status === self::STATUS_FINALIZADA;
    }

    public function isAoVivo(): bool
    {
        return $this->status === self::STATUS_AO_VIVO;
    }

    public function isEmAndamento(): bool
    {
        return $this->status === self::STATUS_EM_ANDAMENTO;
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function scopeFinalizadas(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FINALIZADA);
    }

    public function scopeAoVivo(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AO_VIVO);
    }

    public function scopeEmAndamento(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_EM_ANDAMENTO);
    }

    public function scopeNaoFinalizadas(Builder $query): Builder
    {
        return $query->where('status', '!=', self::STATUS_FINALIZADA);
    }

    public function timeCasa()
    {
        return $this->belongsTo(Time::class, 'time_casa_id');
    }

    public function timeFora()
    {
        return $this->belongsTo(Time::class, 'time_fora_id');
    }

    public function eventos()
    {
        return $this->hasMany(EventosPartida::class);
    }

    public function participacoes()
    {
        return $this->hasMany(ParticipacaoPartida::class);
    }

    public function votos()
    {
        return $this->hasMany(VotoPartida::class);
    }

    public function notasPublicas()
    {
        return $this->hasMany(NotaPublicaPartida::class);
    }

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function proximaPartida()
    {
        return $this->belongsTo(Partida::class, 'proxima_partida_id');
    }

    public function vencedor()
    {
        if ($this->gols_casa > $this->gols_fora) {
            return $this->timeCasa;
        }

        if ($this->gols_fora > $this->gols_casa) {
            return $this->timeFora;
        }

        return null;
    }

    public function avancarVencedor()
    {
        $vencedor = $this->vencedor();

        if (!$vencedor || $this->proximaPartida) {
            return;
        }

        $proxima = $this->proximaPartida;

        if (!$proxima->time_casa_id) {
            $proxima->update([
                'time_casa_id' => $vencedor->id,
            ]);
        } elseif (!$proxima->time_fora_id) {
            $proxima->update([
                'time_fora_id' => $vencedor->id,
            ]);
        }
    }
}
