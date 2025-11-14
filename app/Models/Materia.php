<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Materia extends Model
{
    protected $table = 'materias';
    protected $primaryKey = 'id_materia';

    protected $fillable = [
        'nombre',
        'codigo',
        'carga_horaria',
        'gestion_default',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relaciones
    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'materia_grupo', 'id_materia', 'id_grupo')
            ->withPivot('id_docente', 'gestion', 'activo')
            ->withTimestamps();
    }

    public function materiaGrupos(): HasMany
    {
        return $this->hasMany(MateriaGrupo::class, 'id_materia');
    }
}