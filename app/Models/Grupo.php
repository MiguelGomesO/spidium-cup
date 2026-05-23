<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
        'nome',
        'campeonato_id',
    ];

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function times()
    {
        return $this->belongsToMany(Time::class);
    }
}
