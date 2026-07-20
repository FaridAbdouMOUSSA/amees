<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'role' => ['required', 'in:eleve,etablissement'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)                    // Minimum 8 caractères
                    ->mixedCase()                   // Majuscule + minuscule
                    ->numbers()                     // Chiffres
                    ->symbols()                     // Caractères spéciaux (!@#$ etc.)
                    ->uncompromised(),              // Vérifie que le mot de passe n'a pas été compromis
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.mixed_case' => 'Le mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un caractère spécial (ex: !@#$%...).',
            'password.uncompromised' => 'Ce mot de passe a été compromis dans une fuite de données. Veuillez en choisir un autre.',
        ];
    }
}