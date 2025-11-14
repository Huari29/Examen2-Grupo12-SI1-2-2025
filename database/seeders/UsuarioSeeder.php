<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Administrador Principal',
                'email' => 'admin@ficct.edu.bo',
                'password' => Hash::make('123456789'),//admin
                'email_verified_at' => now()
            ],
            [
                'name' => 'Coordinador Académico',
                'email' => 'coordinador@ficct.edu.bo',
                'password' => Hash::make('123456789'),//coordinador
                'email_verified_at' => now()
            ],
            [
                'name' => 'Dr. Juan Pérez',
                'email' => 'jperez@ficct.edu.bo',
                'password' => Hash::make('123456789'),//docente
                'email_verified_at' => now()
            ],
            [
                'name' => 'Dra. María González',
                'email' => 'mgonzalez@ficct.edu.bo',
                'password' => Hash::make('123456789'),//docente
                'email_verified_at' => now()
            ],
            [
                'name' => 'Ing. Carlos Rodríguez',
                'email' => 'crodriguez@ficct.edu.bo',
                'password' => Hash::make('123456789'),//docente
                'email_verified_at' => now()
            ]
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}