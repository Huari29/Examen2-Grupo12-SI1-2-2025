<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'nombre' => ['required', 'string', 'max:150'],
            'correo' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('usuarios', 'correo'),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'nombre' => $input['nombre'],
            'correo' => $input['correo'],
            'contrasena' => Hash::make($input['password']),
            'activo' => true,
            'id_rol' => null, // Puedes poner un rol por defecto aquí si quieres
        ]);
    }
}