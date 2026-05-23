<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipacaoPartida extends Model
{
    protected $table = 'participacoes_partida';

    protected $fillable = [
        'partida_id',
        'jogador_id',
        'nota',
        'mvp',
    ];

    protected $casts = [
        'nota' => 'float',
        'mvp' => 'boolean',
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
