<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'grupos';
    
    // Clave primaria de la tabla
    protected $primaryKey = 'id_grupo';
    
    // Nombres personalizados para created_at y updated_at
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'codigo',
        'nombre',
        'turno',
        'activo'
    ];

    // Conversión automática de tipos de datos
    protected $casts = [
        'activo' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    /**
     * Relación: Un grupo puede tener múltiples asignaciones materia-grupo
     */
    public function materiaGrupos(): HasMany
    {
        return $this->hasMany(MateriaGrupo::class, 'id_grupo', 'id_grupo');
    }

    /**
     * Relación: Un grupo puede tener múltiples materias a través de materiaGrupos
     * (Alias para materiaGrupos - lo que busca el código)
     */
    public function materias(): HasMany
    {
        return $this->hasMany(MateriaGrupo::class, 'id_grupo', 'id_grupo');
    }
}