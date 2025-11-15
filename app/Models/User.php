<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;
    
    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'id_rol',
        'activo',
    ];

    protected $hidden = [
        'contrasenia',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'creado_en' => 'datetime',
            'actualizado_en' => 'datetime',
            'ultimo_login' => 'datetime',
        ];
    }

    // ==================== CONFIGURACIÓN PARA LARAVEL AUTH ====================
    
    /**
     * Get the password for the user (Laravel usa 'password' por defecto)
     */
    public function getAuthPassword()
    {
        return $this->contrasenia;
    }

    /**
     * Get the name of the unique identifier for the user (Laravel usa 'email' por defecto)
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    // ==================== ACCESSORS PARA COMPATIBILIDAD ====================
    
    /**
     * Accessor para 'name' (Fortify y otros paquetes lo esperan)
     */
    public function getNameAttribute()
    {
        return $this->attributes['nombre'] ?? '';
    }

    /**
     * Accessor para 'email' (Fortify y otros paquetes lo esperan)
     */
    public function getEmailAttribute()
    {
        return $this->attributes['correo'] ?? '';
    }

    /**
     * Mutator para 'name'
     */
    public function setNameAttribute($value)
    {
        $this->attributes['nombre'] = $value;
    }

    /**
     * Mutator para 'email'
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['correo'] = $value;
    }

    /**
     * Mutator para 'password'
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['contrasenia'] = bcrypt($value);
    }

    // ==================== RELACIONES ====================
    
    /**
     * Relación con Rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id');
    }

    /**
     * Relación con MateriaGrupo (grupos asignados al docente)
     */
    public function materiasGrupos()
    {
        return $this->hasMany(MateriaGrupo::class, 'id_docente', 'id');
    }

    /**
     * Relación con Asistencia
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'registrado_por', 'id');
    }

    /**
     * Relación con LogSistema
     */
    public function logs()
    {
        return $this->hasMany(LogSistema::class, 'id_usuario', 'id');
    }

    /**
     * Relación con AuditoriaAcademica (como solicitante)
     */
    public function auditoriasAcademicas()
    {
        return $this->hasMany(AuditoriaAcademica::class, 'id_solicitante', 'id');
    }

    /**
     * Relación con Notificacion
     */
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario', 'id');
    }

    /**
     * Relación con Evaluacion (evaluadas por este usuario)
     */
    public function evaluacionesRealizadas()
    {
        return $this->hasMany(Evaluacion::class, 'evaluado_por', 'id');
    }

    /**
     * Relación con Evaluacion (revisadas por este usuario)
     */
    public function evaluacionesRevisadas()
    {
        return $this->hasMany(Evaluacion::class, 'id_docente', 'id');
    }

    // ==================== MÉTODOS AUXILIARES ====================
    
    /**
     * Get user initials
     */
    public function initials(): string
    {
        return Str::of($this->nombre)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $roleName): bool
    {
        return $this->rol && $this->rol->nombre === $roleName;
    }

    /**
     * Verificar si el usuario es docente
     */
    public function isDocente(): bool
    {
        return $this->hasRole('Docente');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Administrador');
    }
}