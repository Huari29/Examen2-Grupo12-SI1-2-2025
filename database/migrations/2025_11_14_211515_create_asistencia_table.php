<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->foreignId('id_detalle')->constrained('detalle_horario', 'id_detalle')->onDelete('cascade');
            $table->foreignId('id_docente')->constrained('usuarios')->onDelete('cascade');
            $table->string('estado', 20)->default('presente');
            $table->date('fecha');
            $table->string('metodo_registro', 20)->nullable();
            $table->text('observacion')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};