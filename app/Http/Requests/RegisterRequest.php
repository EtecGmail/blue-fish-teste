<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para registro de novos usuários
 *
 * Principios SOLID aplicados:
 * - Single Responsibility Principle (SRP): Classe tem apenas uma responsabilidade de validação
 * - Open/Closed Principle (OCP): Aberta para extensão através de novas regras
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/u', // Apenas letras e espaços
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'lowercase',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // Mínimo: uma letra minúscula, uma maiúscula e um número
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],
            'telefone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\d\s\-\(\)\+]+$/', // Apenas números, espaços, hífens, parênteses e sinal de mais
            ],
            'endereco' => [
                'nullable',
                'string',
                'max:500',
            ],
            'termos' => [
                'required',
                'accepted',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.regex' => 'O nome deve conter apenas letras e espaços.',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, informe um e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado.',

            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.max' => 'A senha não pode ter mais de 255 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password.regex' => 'A senha deve conter pelo menos uma letra minúscula, uma maiúscula e um número.',

            'password_confirmation.required' => 'Por favor, confirme sua senha.',
            'password_confirmation.min' => 'A confirmação de senha deve ter pelo menos 8 caracteres.',
            'password_confirmation.max' => 'A confirmação de senha não pode ter mais de 255 caracteres.',

            'telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'telefone.regex' => 'Por favor, informe um telefone válido.',

            'endereco.max' => 'O endereço não pode ter mais de 500 caracteres.',

            'termos.required' => 'Você deve aceitar os termos de uso.',
            'termos.accepted' => 'Você deve aceitar os termos de uso.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'password_confirmation' => 'confirmação de senha',
            'telefone' => 'telefone',
            'endereco' => 'endereço',
            'termos' => 'termos de uso',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitizar e-mail
        if ($this->has('email')) {
            $this->merge([
                'email' => trim(strtolower($this->input('email'))),
            ]);
        }

        // Sanitizar nome (remover espaços extras)
        if ($this->has('name')) {
            $this->merge([
                'name' => preg_replace('/\s+/', ' ', trim($this->input('name'))),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validações adicionais podem ser adicionadas aqui
            // Por exemplo: verificar idade mínima, validar CPF, etc.
        });
    }
}
