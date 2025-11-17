<?php

namespace App\Exports;

use App\Models\User;
use App\Models\DetalleHorario;
use Barryvdh\DomPDF\Facade\Pdf;

class HorarioPorDocentePDF
{
    protected $docente;
    protected $gestion;

    public function __construct($id_docente, $gestion)
    {
        // Cargar el docente
        $this->docente = User::findOrFail($id_docente);
        $this->gestion = $gestion;
    }

    /**
     * Genera el PDF del horario
     */
    public function descargar()
    {
        // Obtener todos los detalles de horario del docente para la gestión
        $detalles = DetalleHorario::with(['materiaGrupo.materia', 'materiaGrupo.grupo', 'horario', 'aula'])
            ->whereHas('materiaGrupo', function($query) {
                $query->where('id_docente', $this->docente->id)
                      ->where('gestion', $this->gestion);
            })
            ->where('estado', 'Activo')
            ->get();

        // Organizar los datos en una matriz de días y horas
        $horarioOrganizado = $this->organizarHorario($detalles);
        
        // Obtener todos los horarios únicos ordenados
        $horarios = $detalles->pluck('horario')->unique()->sortBy('hora_inicio')->values();

        // Días de la semana
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        // Generar el PDF
        $pdf = Pdf::loadView('exports.horario-docente-pdf', [
            'docente' => $this->docente,
            'gestion' => $this->gestion,
            'horarioOrganizado' => $horarioOrganizado,
            'horarios' => $horarios,
            'dias' => $dias
        ]);

        // Configurar orientación horizontal
        $pdf->setPaper('a4', 'landscape');

        // Retornar el PDF para descarga
        return $pdf->download('Horario_Docente_' . str_replace(' ', '_', $this->docente->nombre) . '_' . $this->gestion . '.pdf');
    }

    /**
     * Organiza el horario en una matriz [dia][horario_id] = detalle
     */
    private function organizarHorario($detalles)
    {
        $organizado = [];

        foreach ($detalles as $detalle) {
            $dia = $detalle->dia_semana;
            $horario_id = $detalle->id_horario;
            
            $organizado[$dia][$horario_id] = $detalle;
        }

        return $organizado;
    }
}
