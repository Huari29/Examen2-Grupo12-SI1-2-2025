<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->integer('id_evaluacion')->primary();
            $table->foreignId('id_docente')->constrained('usuarios')->onDelete('cascade');
            $table->string('gestion', 20)->nullable();
            $table->integer('evaluado_por')->nullable();
            $table->decimal('porcentaje', 5, 2)->nullable();
            $table->decimal('porcentaje_cumplimiento', 5, 2)->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};