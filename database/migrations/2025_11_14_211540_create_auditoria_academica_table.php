<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_academica', function (Blueprint $table) {
            $table->id('id_auditoria');
            $table->foreignId('id_solicitante')->constrained('usuarios')->onDelete('cascade');
            $table->text('descripcion');
            $table->string('estado', 20)->default('pendiente');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->integer('atendido_por')->nullable();
            $table->text('respuesta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_academica');
    }
};