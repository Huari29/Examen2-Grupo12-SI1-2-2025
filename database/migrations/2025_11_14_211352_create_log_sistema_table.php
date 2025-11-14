<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_sistema', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
            $table->string('accion', 50);
            $table->text('descripcion');
            $table->string('ip', 50)->nullable();
            $table->string('modulo', 100)->nullable();
            $table->string('navegador', 250)->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->dateTime('fecha_hora')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_sistema');
    }
};