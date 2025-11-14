<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';

    protected $fillable = [
        'id_usuario',
        'titulo',
        'mensaje',
        'tipo',
        'url_accion',
        'leido'
    ];

    protected $casts = [
        'leido' => 'boolean'
    ];

    // Relaciones
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}