<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventosPartida extends Model
{
    protected $table = 'eventos_partida';

    protected $fillable = [
        'partida_id',
        'jogador_id',
        'tipo',
        'minuto'
    ];

    public function jogador()
    {
        return $this->belongsTo(Jogador::class);
    }

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }
}
