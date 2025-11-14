<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaAcademica extends Model
{
    protected $table = 'auditoria_academica';
    protected $primaryKey = 'id_auditoria';

    protected $fillable = [
        'id_solicitante',
        'descripcion',
        'estado',
        'fecha_solicitud',
        'atendido_por',
        'respuesta'
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime'
    ];

    // Relaciones
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_solicitante');
    }
}