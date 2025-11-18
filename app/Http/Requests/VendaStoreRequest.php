<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'produto_id' => [
                'required',
                'integer',
                'exists:produtos,id',
                Rule::exists('produtos', 'id')->where(function ($query) {
                    $query->where('status', 'ativo');
                }),
            ],
            'quantidade' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'produto_id.required' => 'Selecione um produto válido.',
            'produto_id.exists' => 'O produto informado não foi encontrado ou está indisponível.',
            'produto_id.integer' => 'O ID do produto deve ser um número inteiro.',
            'quantidade.required' => 'Informe a quantidade desejada.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade mínima é 1.',
            'quantidade.max' => 'A quantidade máxima permitida é 999.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'quantidade' => (int) $this->quantidade,
        ]);
    }
}
