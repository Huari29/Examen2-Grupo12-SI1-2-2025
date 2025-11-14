<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aula extends Model
{
    protected $table = 'aulas';
    protected $primaryKey = 'id_aula';

    protected $fillable = [
        'nombre',
        'codigo',
        'capacidad',
        'ubicacion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relaciones
    public function detallesHorario(): HasMany
    {
        return $this->hasMany(DetalleHorario::class, 'id_aula');
    }
}