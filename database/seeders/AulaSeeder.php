<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aula;

class AulaSeeder extends Seeder
{
    public function run(): void
    {
        $aulas = [
            [
                'nombre' => 'Laboratorio de Computación 1',
                'codigo' => 'LAB-01',
                'capacidad' => 30,
                'ubicacion' => 'Edificio A - Planta Baja',
                'activo' => true
            ],
            [
                'nombre' => 'Laboratorio de Computación 2',
                'codigo' => 'LAB-02',
                'capacidad' => 30,
                'ubicacion' => 'Edificio A - Planta Baja',
                'activo' => true
            ],
            [
                'nombre' => 'Aula Magna',
                'codigo' => 'AULA-101',
                'capacidad' => 80,
                'ubicacion' => 'Edificio B - Primer Piso',
                'activo' => true
            ],
            [
                'nombre' => 'Aula 102',
                'codigo' => 'AULA-102',
                'capacidad' => 40,
                'ubicacion' => 'Edificio B - Primer Piso',
                'activo' => true
            ],
            [
                'nombre' => 'Aula 201',
                'codigo' => 'AULA-201',
                'capacidad' => 35,
                'ubicacion' => 'Edificio B - Segundo Piso',
                'activo' => true
            ],
            [
                'nombre' => 'Aula 202',
                'codigo' => 'AULA-202',
                'capacidad' => 35,
                'ubicacion' => 'Edificio B - Segundo Piso',
                'activo' => true
            ],
            [
                'nombre' => 'Sala de Conferencias',
                'codigo' => 'CONF-01',
                'capacidad' => 50,
                'ubicacion' => 'Edificio C - Primer Piso',
                'activo' => true
            ]
        ];

        foreach ($aulas as $aula) {
            Aula::create($aula);
        }
    }
}