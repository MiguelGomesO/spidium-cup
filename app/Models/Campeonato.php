<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    protected $fillable = [
        'nome',
        'tipo',
        'data_inicio',
        'qtd_times',
        'formato'
    ];

    public function times()
    {
        return $this->belongsToMany(Time::class);
    }
}
