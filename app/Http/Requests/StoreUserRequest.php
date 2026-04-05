<?php

namespace App\Http\Requests;

use App\Rules\CPF;
use App\Rules\Email;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'nome'     => 'required|string|min:3|max:255',
            'cpf'      => ['required', 'string', 'unique:usuarios,cpf', new CPF],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'unique:usuarios,email',
                new Email
            ],
            'senha' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.unique' => 'This CPF alredy exists.',
            'email.unique' => 'This Email alredy exists.',
            'nome' => 'The nome field is required and must be a string between 3 and 255 characters.',
            'senha' => 'The senha field is required and must be a string with at least 6 characters. The password confirmation must match.',
        ];
    }
}
