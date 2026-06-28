<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MelhorMomentoPartida extends Model
{
    public const TIPO_IMAGEM = 'imagem';

    public const TIPO_VIDEO = 'video';

    public const TIPO_TWITCH_CLIP = 'twitch_clip';

    protected $table = 'melhores_momentos_partida';

    protected $fillable = [
        'partida_id',
        'titulo',
        'descricao',
        'tipo',
        'arquivo',
        'ordem',
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }

    public function isVideo(): bool
    {
        return $this->tipo === self::TIPO_VIDEO;
    }

    public function isTwitchClip(): bool
    {
        return $this->tipo === self::TIPO_TWITCH_CLIP;
    }

    public function isUpload(): bool
    {
        return in_array($this->tipo, [self::TIPO_IMAGEM, self::TIPO_VIDEO], true);
    }

    public function url(): string
    {
        if ($this->isTwitchClip()) {
            return 'https://clips.twitch.tv/' . $this->arquivo;
        }

        return Storage::disk('public')->url($this->arquivo);
    }

    public function twitchEmbedUrl(): string
    {
        $configured = parse_url(config('app.url'), PHP_URL_HOST);
        $current = request()->getHost();

        $parents = array_values(array_unique(array_filter([$configured, $current])));

        $query = 'clip=' . rawurlencode($this->arquivo);

        foreach ($parents as $parent) {
            $query .= '&parent=' . rawurlencode($parent);
        }

        return 'https://clips.twitch.tv/embed?' . $query;
    }

    public static function extractTwitchClipSlug(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);

        if (preg_match('#clips\.twitch\.tv/([^/?\s]+)#i', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('#twitch\.tv/[^/]+/clip/([^/?\s]+)#i', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected static function booted(): void
    {
        static::deleting(function (MelhorMomentoPartida $momento) {
            if ($momento->isUpload() && $momento->arquivo) {
                Storage::disk('public')->delete($momento->arquivo);
            }
        });
    }
}
