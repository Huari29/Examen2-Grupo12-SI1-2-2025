<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materia;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        $materias = [
            [
                'nombre' => 'Sistemas de Información I',
                'codigo' => 'SIS-101',
                'carga_horaria' => 80,
                'gestion_default' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Base de Datos I',
                'codigo' => 'BD-101',
                'carga_horaria' => 100,
                'gestion_default' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Programación I',
                'codigo' => 'PROG-101',
                'carga_horaria' => 120,
                'gestion_default' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Algoritmos y Estructuras de Datos',
                'codigo' => 'AED-101',
                'carga_horaria' => 100,
                'gestion_default' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Ingeniería de Software',
                'codigo' => 'IS-201',
                'carga_horaria' => 80,
                'gestion_default' => '2-2025',
                'activo' => true
            ],
            [
                'nombre' => 'Redes de Computadoras',
                'codigo' => 'REDES-201',
                'carga_horaria' => 80,
                'gestion_default' => '2-2025',
                'activo' => true
            ]
        ];

        foreach ($materias as $materia) {
            Materia::create($materia);
        }
    }
}