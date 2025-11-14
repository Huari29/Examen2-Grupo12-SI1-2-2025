<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GestionAcademica extends Model
{
    protected $table = 'gestion_academica';
    protected $primaryKey = 'id_gestion';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'codigo',
        'activo',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'creado_en'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'creado_en' => 'date'
    ];
}