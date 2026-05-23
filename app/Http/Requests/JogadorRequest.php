<?php

namespace App\Http\Requests;

use App\Models\Jogador;
use Illuminate\Foundation\Http\FormRequest;

class JogadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'posicao' => 'nullable|string|max:255',
            'numero' => 'nullable|integer|min:0|max:99',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'twitch' => 'nullable|string|max:255',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'instagram' => Jogador::normalizeSocialUsername($this->input('instagram')),
            'twitter' => Jogador::normalizeSocialUsername($this->input('twitter')),
            'twitch' => Jogador::normalizeSocialUsername($this->input('twitch')),
        ]);
    }
}
