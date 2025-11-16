<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string', 'max:2000'],
            'preco' => ['required', 'numeric', 'min:0'],
            'imagem' => ['nullable', 'string', 'max:1024'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'estoque' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:ativo,inativo'],
        ];

        // Se for update, adicionar regras específicas
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // Remover validações que não são necessárias em update
        }

        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome do produto.',
            'nome.max' => 'O nome do produto deve ter no máximo 255 caracteres.',
            'descricao.required' => 'Descreva brevemente o produto.',
            'descricao.max' => 'A descrição deve ter no máximo 2000 caracteres.',
            'preco.required' => 'Informe o preço.',
            'preco.numeric' => 'O preço deve ser um número válido.',
            'preco.min' => 'O preço deve ser maior ou igual a zero.',
            'imagem.string' => 'A URL da imagem deve ser um texto válido.',
            'imagem.max' => 'A URL da imagem deve ter no máximo 1024 caracteres.',
            'categoria.string' => 'A categoria deve ser um texto válido.',
            'categoria.max' => 'A categoria deve ter no máximo 255 caracteres.',
            'estoque.integer' => 'O estoque deve ser um número inteiro.',
            'estoque.min' => 'O estoque deve ser maior ou igual a zero.',
            'status.required' => 'Selecione o status do produto.',
            'status.in' => 'Selecione um status válido (ativo ou inativo).',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpar e formatar dados
        if ($this->has('nome')) {
            $this->merge([
                'nome' => trim($this->nome),
            ]);
        }

        if ($this->has('descricao')) {
            $this->merge([
                'descricao' => trim($this->descricao),
            ]);
        }

        if ($this->has('categoria')) {
            $this->merge([
                'categoria' => trim($this->categoria),
            ]);
        }

        if ($this->has('preco')) {
            $this->merge([
                'preco' => (float) $this->preco,
            ]);
        }

        if ($this->has('estoque')) {
            $this->merge([
                'estoque' => (int) $this->estoque,
            ]);
        }
    }
}