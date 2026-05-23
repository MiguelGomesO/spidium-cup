<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jogador extends Model
{
    protected $table = 'jogadores';

    protected $fillable = [
        'nome',
        'posicao',
        'numero',
        'time_id'
    ];

    public function time() {
        return $this->belongsTo(Time::class);
    }

    public function eventos()
    {
        return $this->hasMany(EventosPartida::class, 'jogador_id');
    }

    public function eventosComoAssistencia()
    {
        return $this->hasMany(EventosPartida::class, 'assistencia_id');
    }
}
