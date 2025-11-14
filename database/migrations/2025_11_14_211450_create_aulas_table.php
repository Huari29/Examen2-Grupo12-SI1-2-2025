<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id('id_aula');
            $table->string('nombre', 100);
            $table->string('codigo', 50);
            $table->integer('capacidad')->nullable();
            $table->string('ubicacion', 150)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};