<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $primaryKey = 'id_grupo';

    protected $fillable = [
        'nombre',
        'codigo',
        'turno',
        'gestion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relaciones
    public function materias(): BelongsToMany
    {
        return $this->belongsToMany(Materia::class, 'materia_grupo', 'id_grupo', 'id_materia')
            ->withPivot('id_docente', 'gestion', 'activo')
            ->withTimestamps();
    }

    public function materiaGrupos(): HasMany
    {
        return $this->hasMany(MateriaGrupo::class, 'id_grupo');
    }
}