<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materia_grupo', function (Blueprint $table) {
            $table->id('id_mg');
            $table->foreignId('id_materia')->constrained('materias', 'id_materia')->onDelete('cascade');
            $table->foreignId('id_grupo')->constrained('grupos', 'id_grupo')->onDelete('cascade');
            $table->foreignId('id_docente')->constrained('usuarios')->onDelete('cascade');
            $table->string('gestion', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materia_grupo');
    }
};