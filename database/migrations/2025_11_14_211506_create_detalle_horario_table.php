<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_horario', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->foreignId('id_horario')->constrained('horarios')->onDelete('cascade');
            $table->foreignId('id_aula')->constrained('aulas', 'id_aula')->onDelete('cascade');
            $table->foreignId('id_mg')->constrained('materia_grupo', 'id_mg')->onDelete('cascade');
            $table->string('dia_semana', 15);
            $table->string('estado', 20)->default('activo');
            $table->string('gestion', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_horario');
    }
};