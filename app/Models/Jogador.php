<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Jogador extends Model
{
    protected $table = 'jogadores';

    protected $fillable = [
        'nome',
        'posicao',
        'numero',
        'time_id',
        'instagram',
        'twitter',
        'twitch',
    ];

    public static function normalizeSocialUsername(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $value = ltrim($value, '@');

        return $value === '' ? null : $value;
    }

    protected function instagramUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->instagram
                ? 'https://instagram.com/' . $this->instagram
                : null
        );
    }

    protected function twitterUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->twitter
                ? 'https://x.com/' . $this->twitter
                : null
        );
    }

    protected function twitchUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->twitch
                ? 'https://twitch.tv/' . $this->twitch
                : null
        );
    }

    public function time()
    {
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

    public function participacoes()
    {
        return $this->hasMany(ParticipacaoPartida::class);
    }

    public function votosPartida()
    {
        return $this->hasMany(VotoPartida::class);
    }

    public function notasPublicasPartida()
    {
        return $this->hasMany(NotaPublicaPartida::class);
    }

    public function scopeComEstatisticas($query)
    {
        return $query
            ->withCount('participacoes as jogos_disputados')
            ->withCount([
                'participacoes as mvps_count' => fn ($q) => $q->where('mvp', true),
            ])
            ->withAvg('participacoes as media_notas', 'nota');
    }
}
