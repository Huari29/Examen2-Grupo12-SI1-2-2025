<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $table = 'asistencia';
    protected $primaryKey = 'id_asistencia';

    protected $fillable = [
        'id_detalle',
        'id_docente',
        'estado',
        'fecha',
        'metodo_registro',
        'observacion',
        'registrado_por'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    // Relaciones
    public function detalleHorario(): BelongsTo
    {
        return $this->belongsTo(DetalleHorario::class, 'id_detalle');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_docente');
    }

    public function registrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}