<?php

namespace App\Exports;

use App\Models\Grupo;
use App\Models\DetalleHorario;
use Barryvdh\DomPDF\Facade\Pdf;

class HorarioPorGrupoPDF
{
    protected $grupo;
    protected $gestion;

    public function __construct($id_grupo, $gestion)
    {
        // Cargar el grupo con sus relaciones
        $this->grupo = Grupo::with(['materiaGrupos.materia', 'materiaGrupos.docente'])
            ->findOrFail($id_grupo);
        $this->gestion = $gestion;
    }

    /**
     * Genera el PDF del horario
     */
    public function descargar()
    {
        // Obtener todos los detalles de horario del grupo para la gestión
        $detalles = DetalleHorario::with(['materiaGrupo.materia', 'materiaGrupo.docente', 'horario', 'aula'])
            ->whereHas('materiaGrupo', function($query) {
                $query->where('id_grupo', $this->grupo->id_grupo)
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
        $pdf = Pdf::loadView('exports.horario-grupo-pdf', [
            'grupo' => $this->grupo,
            'gestion' => $this->gestion,
            'horarioOrganizado' => $horarioOrganizado,
            'horarios' => $horarios,
            'dias' => $dias
        ]);

        // Configurar orientación horizontal
        $pdf->setPaper('a4', 'landscape');

        // Retornar el PDF para descarga
        return $pdf->download('Horario_' . $this->grupo->codigo . '_' . $this->gestion . '.pdf');
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