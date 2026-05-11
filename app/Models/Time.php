<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $fillable = [
        'nome',
        'cor',
        'logo',
        'uniforme',
        'estadio'
    ];

    public function campeonatos()
    {
        return $this->belongsToMany(Campeonato::class);
    }

    public function jogadores()
    {
        return $this->hasMany(Jogador::class);
    }

    public function partidasCasa()
    {
        return $this->hasMany(Partida::class, 'time_casa_id');
    }

    public function partidasFora()
    {
        return $this->hasMany(Partida::class, 'time_fora_id');
    }

    public function grupos()
    {
        return $this->belongsToMany(Time::class);
    }
}
