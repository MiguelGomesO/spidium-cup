<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    protected $fillable = [
        'nome',
        'formato',
        'qtd_times',
    ];

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class);
    }

    public function times()
    {
        return $this->belongsToMany(Time::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}
