<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rol', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
        });

        // Agregar la foreign key DESPUÉS de crear la tabla rol
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreign('id_rol')
                ->references('id')
                ->on('rol')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_rol']);
        });
        
        Schema::dropIfExists('rol');
    }
};