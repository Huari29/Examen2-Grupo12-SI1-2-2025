<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetalleHorario extends Model
{
    protected $table = 'detalle_horario';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_horario',
        'id_aula',
        'id_mg',
        'dia_semana',
        'estado',
        'gestion'
    ];

    // Relaciones
    public function horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class, 'id_horario');
    }

    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

    public function materiaGrupo(): BelongsTo
    {
        return $this->belongsTo(MateriaGrupo::class, 'id_mg');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_detalle');
    }
}