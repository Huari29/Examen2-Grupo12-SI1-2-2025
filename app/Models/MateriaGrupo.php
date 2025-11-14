<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MateriaGrupo extends Model
{
    protected $table = 'materia_grupo';
    protected $primaryKey = 'id_mg';

    protected $fillable = [
        'id_materia',
        'id_grupo',
        'id_docente',
        'gestion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relaciones
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_docente');
    }

    public function detallesHorario(): HasMany
    {
        return $this->hasMany(DetalleHorario::class, 'id_mg');
    }
}