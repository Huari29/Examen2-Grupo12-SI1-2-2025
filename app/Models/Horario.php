<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'descripcion',
        'hora_inicio',
        'hora_fin'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i'
    ];

    // Relaciones
    public function detallesHorario(): HasMany
    {
        return $this->hasMany(DetalleHorario::class, 'id_horario');
    }
}