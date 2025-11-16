<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MateriaGrupo extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'materia_grupo';
    
    // Clave primaria de la tabla
    protected $primaryKey = 'id_mg';
    
    // Nombres personalizados para created_at y updated_at
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'id_materia',
        'id_grupo',
        'id_docente',
        'gestion',
        'activo'
    ];

    // Conversión automática de tipos de datos
    protected $casts = [
        'activo' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    /**
     * Relación: Una asignación pertenece a una materia
     */
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'id_materia', 'id_materia');
    }

    /**
     * Relación: Una asignación pertenece a un grupo
     */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    /**
     * Relación: Una asignación pertenece a un docente (usuario)
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_docente', 'id');
    }

    /**
     * Relación: Una asignación materia-grupo puede tener múltiples detalles de horario
     */
    public function detallesHorario(): HasMany
    {
        return $this->hasMany(DetalleHorario::class, 'id_mg', 'id_mg');
    }
}