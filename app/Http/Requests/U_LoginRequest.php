<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;


use Illuminate\Foundation\Http\FormRequest;

class U_LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required',
            'email'=>['required','email',Rule::unique('users')],
            'password'=>['required',Rule::unique('users')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'Le champ Name est réquis!',
            'email.required'=>'Le champ Email est réquis!',
            'email.email'=>'Ce champ est un mail!',
            'email.unique'=>'Ce mail existe déjà!',
            'password.required'=>'Le champ Password est réquis!',
            'password.unique'=>'Ce mot de passe existe déjà!!',
        ];
    }
}
