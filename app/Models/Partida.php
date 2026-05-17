<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $fillable = [
        'campeonato_id',
        'time_casa_id',
        'time_fora_id',
        'gols_casa',
        'gols_fora',
        'finalizada',
        'data',
    ];

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
                'time_casa_id' => $vencedor->id
            ]);
        } elseif (!$proxima->time_fora_id) {
            $proxima->update([
                'time_fora_id' => $vencedor->id
            ]);
        }
    }
}
