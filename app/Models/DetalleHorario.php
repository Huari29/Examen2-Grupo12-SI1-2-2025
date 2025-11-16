<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleHorario extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'detalle_horario';
    
    // Clave primaria de la tabla
    protected $primaryKey = 'id_detalle';
    
    // Nombres personalizados para created_at y updated_at
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null; // No hay updated_at según tu diagrama
    
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'id_mg',           // ID de la asignación materia-grupo-docente
        'id_horario',      // ID del bloque horario (ej: 07:00-09:00)
        'id_aula',         // ID del aula donde se dará la clase
        'dia_semana',      // Día de la semana (Lunes, Martes, etc.)
        'gestion',         // Gestión académica (ej: 2-2025)
        'estado'           // Estado del detalle (Activo, Cancelado, etc.)
    ];

    // Conversión automática de tipos de datos
    protected $casts = [
        'creado_en' => 'datetime', // Convierte creado_en a objeto Carbon
    ];

    /**
     * Relación: Un detalle de horario pertenece a una asignación materia-grupo
     * Esto nos permite acceder a la materia, grupo y docente desde el detalle
     */
    public function materiaGrupo(): BelongsTo
    {
        return $this->belongsTo(MateriaGrupo::class, 'id_mg', 'id_mg');
    }

    /**
     * Relación: Un detalle de horario pertenece a un bloque horario
     * Esto nos da acceso a hora_inicio y hora_fin
     */
    public function horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class, 'id_horario', 'id');
    }

    /**
     * Relación: Un detalle de horario pertenece a un aula
     * Esto nos permite saber en qué aula se dará la clase
     */
    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }

    /**
     * Scope: Filtra detalles activos
     * Uso: DetalleHorario::activos()->get()
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    /**
     * Scope: Filtra por gestión
     * Uso: DetalleHorario::porGestion('2-2025')->get()
     */
    public function scopePorGestion($query, $gestion)
    {
        return $query->where('gestion', $gestion);
    }

    /**
     * Scope: Filtra por día de la semana
     * Uso: DetalleHorario::porDia('Lunes')->get()
     */
    public function scopePorDia($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    /**
     * Verifica si hay conflicto de aula
     * Retorna true si el aula ya está ocupada en ese horario y día
     */
    public static function tieneConflictoAula($id_aula, $id_horario, $dia_semana, $gestion, $excluir_id = null)
    {
        $query = self::where('id_aula', $id_aula)
            ->where('id_horario', $id_horario)
            ->where('dia_semana', $dia_semana)
            ->where('gestion', $gestion)
            ->where('estado', 'Activo');
        
        // Si estamos editando, excluir el registro actual
        if ($excluir_id) {
            $query->where('id_detalle', '!=', $excluir_id);
        }
        
        return $query->exists();
    }

    /**
     * Verifica si hay conflicto de docente
     * Retorna true si el docente ya tiene clase en ese horario y día
     */
    public static function tieneConflictoDocente($id_docente, $id_horario, $dia_semana, $gestion, $excluir_id = null)
    {
        $query = self::whereHas('materiaGrupo', function($q) use ($id_docente) {
            $q->where('id_docente', $id_docente);
        })
            ->where('id_horario', $id_horario)
            ->where('dia_semana', $dia_semana)
            ->where('gestion', $gestion)
            ->where('estado', 'Activo');
        
        // Si estamos editando, excluir el registro actual
        if ($excluir_id) {
            $query->where('id_detalle', '!=', $excluir_id);
        }
        
        return $query->exists();
    }

    /**
     * Verifica si hay conflicto de grupo
     * Retorna true si el grupo ya tiene clase en ese horario y día
     */
    public static function tieneConflictoGrupo($id_grupo, $id_horario, $dia_semana, $gestion, $excluir_id = null)
    {
        $query = self::whereHas('materiaGrupo', function($q) use ($id_grupo) {
            $q->where('id_grupo', $id_grupo);
        })
            ->where('id_horario', $id_horario)
            ->where('dia_semana', $dia_semana)
            ->where('gestion', $gestion)
            ->where('estado', 'Activo');
        
        // Si estamos editando, excluir el registro actual
        if ($excluir_id) {
            $query->where('id_detalle', '!=', $excluir_id);
        }
        
        return $query->exists();
    }
}