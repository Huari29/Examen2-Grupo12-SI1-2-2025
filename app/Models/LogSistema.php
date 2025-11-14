<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogSistema extends Model
{
    protected $table = 'log_sistema';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'accion',
        'descripcion',
        'ip',
        'modulo',
        'navegador',
        'creado_en',
        'fecha_hora'
    ];

    protected $casts = [
        'creado_en' => 'datetime',
        'fecha_hora' => 'datetime'
    ];

    // Relaciones
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}