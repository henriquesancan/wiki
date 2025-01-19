<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ShowRevenueRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta solicitação.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtém as regras de validação aplicáveis à solicitação.
     *
     * @return array<string, string> Regras de validação da solicitação
     */
    public function rules(): array
    {
        return [
            'rule' => 'required|string|in:between,greater,smaller',
            'billions' => 'nullable',
            'range' => 'nullable',
        ];
    }

    /**
     * Modifica o comportamento do validador aplicando validações condicionais.
     *
     * @param Validator $validator Instância do validador para adicionar regras condicionais.
     *
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('billions', 'required', function ($input) {
            return in_array($input->rule, ['greater', 'smaller']);
        });

        $validator->sometimes('range', 'required', function ($input) {
            return $input->rule == 'between';
        });
    }
}
