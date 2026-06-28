<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaPublicaPartida extends Model
{
    protected $table = 'notas_publicas_partida';

    protected $fillable = [
        'partida_id',
        'jogador_id',
        'ip_address',
        'nota',
    ];

    protected $casts = [
        'nota' => 'float',
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
