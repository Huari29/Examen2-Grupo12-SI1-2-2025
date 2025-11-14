<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso completo al sistema'
            ],
            [
                'nombre' => 'Coordinador',
                'descripcion' => 'Gestión de horarios y asignaciones'
            ],
            [
                'nombre' => 'Autoridad',
                'descripcion' => 'Visualización de reportes y estadísticas'
            ],
            [
                'nombre' => 'Docente',
                'descripcion' => 'Visualización de carga horaria y registro de asistencia'
            ]
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}