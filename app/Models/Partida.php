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
        'data'
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
}
