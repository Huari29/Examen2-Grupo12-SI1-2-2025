<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            [
                'nombre' => 'Grupo A',
                'codigo' => 'A-2025',
                'turno' => 'Mañana',
                'gestion' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Grupo B',
                'codigo' => 'B-2025',
                'turno' => 'Mañana',
                'gestion' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Grupo C',
                'codigo' => 'C-2025',
                'turno' => 'Tarde',
                'gestion' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Grupo D',
                'codigo' => 'D-2025',
                'turno' => 'Tarde',
                'gestion' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Grupo E',
                'codigo' => 'E-2025',
                'turno' => 'Noche',
                'gestion' => '2-2025',
                'activo' => true
            ]
        ];

        foreach ($grupos as $grupo) {
            Grupo::create($grupo);
        }
    }
}