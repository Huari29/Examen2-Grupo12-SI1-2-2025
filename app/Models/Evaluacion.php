<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';
    protected $primaryKey = 'id_evaluacion';
    public $incrementing = false;

    protected $fillable = [
        'id_evaluacion',
        'id_docente',
        'gestion',
        'evaluado_por',
        'porcentaje',
        'porcentaje_cumplimiento',
        'observacion'
    ];

    protected $casts = [
        'porcentaje' => 'decimal:2',
        'porcentaje_cumplimiento' => 'decimal:2'
    ];

    // Relaciones
    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_docente');
    }
}