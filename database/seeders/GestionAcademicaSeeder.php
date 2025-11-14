<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GestionAcademica;
use Carbon\Carbon;

class GestionAcademicaSeeder extends Seeder
{
    public function run(): void
    {
        $gestiones = [
            [
                'nombre' => 'Segundo Semestre 2024',
                'codigo' => '2-2024',
                'activo' => false,
                'estado' => 'Finalizado',
                'fecha_inicio' => Carbon::parse('2024-08-01'),
                'fecha_fin' => Carbon::parse('2024-12-20'),
                'creado_en' => Carbon::parse('2024-07-01')
            ],
            [
                'nombre' => 'Primer Semestre 2025',
                'codigo' => '1-2025',
                'activo' => false,
                'estado' => 'Finalizado',
                'fecha_inicio' => Carbon::parse('2025-02-01'),
                'fecha_fin' => Carbon::parse('2025-06-30'),
                'creado_en' => Carbon::parse('2025-01-15')
            ],
            [
                'nombre' => 'Segundo Semestre 2025',
                'codigo' => '2-2025',
                'activo' => true,
                'estado' => 'En curso',
                'fecha_inicio' => Carbon::parse('2025-08-01'),
                'fecha_fin' => Carbon::parse('2025-12-20'),
                'creado_en' => Carbon::now()
            ]
        ];

        foreach ($gestiones as $gestion) {
            GestionAcademica::create($gestion);
        }
    }
}