<?php

namespace App\Http\Controllers;

use App\Models\MelhorMomentoPartida;
use App\Models\Partida;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MelhorMomentoPartidaController extends Controller
{
    public function store(Request $request, Partida $partida): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => 'nullable|string|max:120',
            'descricao' => 'nullable|string|max:500',
            'fonte' => 'required|in:upload,twitch',
            'twitch_url' => 'nullable|required_if:fonte,twitch|url|max:500',
            'arquivo' => 'nullable|required_if:fonte,upload|file|mimes:jpeg,jpg,png,gif,webp,mp4,webm|max:51200',
        ]);

        $ordem = (int) $partida->momentos()->max('ordem') + 1;

        if ($data['fonte'] === 'twitch') {
            $slug = MelhorMomentoPartida::extractTwitchClipSlug($data['twitch_url']);

            if ($slug === null) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'twitch_url' => 'Cole um link válido de clip da Twitch (clips.twitch.tv ou twitch.tv/.../clip/...).',
                    ]);
            }

            MelhorMomentoPartida::create([
                'partida_id' => $partida->id,
                'titulo' => $data['titulo'] ?? null,
                'descricao' => $data['descricao'] ?? null,
                'tipo' => MelhorMomentoPartida::TIPO_TWITCH_CLIP,
                'arquivo' => $slug,
                'ordem' => $ordem,
            ]);
        } else {
            $mime = $request->file('arquivo')->getMimeType();
            $tipo = str_starts_with($mime, 'video/')
                ? MelhorMomentoPartida::TIPO_VIDEO
                : MelhorMomentoPartida::TIPO_IMAGEM;

            $path = $request->file('arquivo')->store(
                "partidas/{$partida->id}/momentos",
                'public',
            );

            MelhorMomentoPartida::create([
                'partida_id' => $partida->id,
                'titulo' => $data['titulo'] ?? null,
                'descricao' => $data['descricao'] ?? null,
                'tipo' => $tipo,
                'arquivo' => $path,
                'ordem' => $ordem,
            ]);
        }

        return redirect()
            ->route('partidas.show', ['partida' => $partida, 'tab' => 'momentos'])
            ->with('success', 'Momento adicionado com sucesso!');
    }

    public function destroy(Partida $partida, MelhorMomentoPartida $momento): RedirectResponse
    {
        if ($momento->partida_id !== $partida->id) {
            abort(404);
        }

        $momento->delete();

        return redirect()
            ->route('partidas.show', ['partida' => $partida, 'tab' => 'momentos'])
            ->with('success', 'Momento removido.');
    }
}
