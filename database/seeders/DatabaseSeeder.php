<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolSeeder::class,
            UsuarioSeeder::class,
            GestionAcademicaSeeder::class,
            MateriaSeeder::class,
            GrupoSeeder::class,
            AulaSeeder::class,
        ]);
    }
}