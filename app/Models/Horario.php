<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $primaryKey = 'id';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'hora_inicio',
        'hora_fin'
    ];

    // Solo cast para timestamps, no para TIME
    protected $casts = [
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    // Relaciones
    public function detallesHorario(): HasMany
    {
        return $this->hasMany(DetalleHorario::class, 'id_horario', 'id');
    }
}