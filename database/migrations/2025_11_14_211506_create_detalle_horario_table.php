<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones
     * Crea la tabla detalle_horario que relaciona asignaciones con horarios, aulas y días
     */
    public function up(): void
    {
        Schema::create('detalle_horario', function (Blueprint $table) {
            // Clave primaria autoincremental
            $table->id('id_detalle');
            
            // Relación con materia_grupo (asignación materia-grupo-docente)
            $table->unsignedBigInteger('id_mg');
            $table->foreign('id_mg')->references('id_mg')->on('materia_grupo')->onDelete('cascade');
            
            // Relación con horarios (bloque de tiempo)
            $table->unsignedBigInteger('id_horario');
            $table->foreign('id_horario')->references('id')->on('horarios')->onDelete('cascade');
            
            // Relación con aulas
            $table->unsignedBigInteger('id_aula');
            $table->foreign('id_aula')->references('id_aula')->on('aulas')->onDelete('cascade');
            
            // Día de la semana (Lunes, Martes, Miércoles, etc.)
            $table->string('dia_semana', 15);
            
            // Gestión académica (ej: 2-2025)
            $table->string('gestion', 20);
            
            // Estado del detalle (Activo, Cancelado, Reprogramado, etc.)
            $table->string('estado', 20)->default('Activo');
            
            // Timestamp de creación
            $table->timestamp('creado_en')->useCurrent();
            
            // Índice compuesto para optimizar búsquedas de conflictos
            $table->index(['id_horario', 'dia_semana', 'gestion', 'estado']);
        });
    }

    /**
     * Revierte las migraciones
     * Elimina la tabla detalle_horario
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_horario');
    }
};