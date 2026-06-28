<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotoPartida extends Model
{
    protected $table = 'votos_partida';

    protected $fillable = [
        'partida_id',
        'jogador_id',
        'visitor_id',
        'ip_hash',
        'user_agent',
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }

    public function jogador()
    {
        return $this->belongsTo(Jogador::class);
    }
}
