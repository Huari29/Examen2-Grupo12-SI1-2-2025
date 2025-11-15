<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener los roles
        $adminRol = Rol::where('nombre', 'Administrador')->first();
        $coordinadorRol = Rol::where('nombre', 'Coordinador Académico')->first();
        $docenteRol = Rol::where('nombre', 'Docente')->first();

        $usuarios = [
            [
                'nombre' => 'Administrador Principal',
                'correo' => 'admin@ficct.edu.bo',
                'contrasena' => Hash::make('123456789'),
                'id_rol' => $adminRol?->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Coordinador Académico',
                'correo' => 'coordinador@ficct.edu.bo',
                'contrasena' => Hash::make('123456789'),
                'id_rol' => $coordinadorRol?->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Dr. Juan Pérez',
                'correo' => 'jperez@ficct.edu.bo',
                'contrasena' => Hash::make('123456789'),
                'id_rol' => $docenteRol?->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Dra. María González',
                'correo' => 'mgonzalez@ficct.edu.bo',
                'contrasena' => Hash::make('123456789'),
                'id_rol' => $docenteRol?->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Ing. Carlos Rodríguez',
                'correo' => 'crodriguez@ficct.edu.bo',
                'contrasena' => Hash::make('123456789'),
                'id_rol' => $docenteRol?->id,
                'activo' => true,
            ]
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}