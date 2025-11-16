<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones
     * Crea la tabla materia_grupo que relaciona materias, grupos y docentes
     */
    public function up(): void
    {
        Schema::create('materia_grupo', function (Blueprint $table) {
            // Clave primaria autoincremental
            $table->id('id_mg');
            
            // Relación con materias
            $table->unsignedBigInteger('id_materia');
            $table->foreign('id_materia')->references('id_materia')->on('materias')->onDelete('cascade');
            
            // Relación con grupos
            $table->unsignedBigInteger('id_grupo');
            $table->foreign('id_grupo')->references('id_grupo')->on('grupos')->onDelete('cascade');
            
            // Relación con docentes (usuarios)
            $table->unsignedBigInteger('id_docente');
            $table->foreign('id_docente')->references('id')->on('usuarios')->onDelete('cascade');
            
            // Gestión académica (ej: 2-2025)
            $table->string('gestion', 20);
            
            // Estado de la asignación
            $table->boolean('activo')->default(true);
            
            // Timestamps personalizados
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
            
            // CONSTRAINT ÚNICO: No puede haber dos asignaciones iguales de materia-grupo en la misma gestión
            // Esto permite que la misma materia esté en diferentes grupos
            // Pero NO permite que la misma materia esté DOS veces en el MISMO grupo en la MISMA gestión
            $table->unique(['id_materia', 'id_grupo', 'gestion'], 'unique_materia_grupo_gestion');
        });
    }

    /**
     * Revierte las migraciones
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_grupo');
    }
};