<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVotoPartidaRequest extends FormRequest
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
            'jogador_id' => ['required', 'integer', 'exists:jogadores,id'],
            'visitor_id' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }
}
