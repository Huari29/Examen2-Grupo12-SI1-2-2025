<?php

namespace App\Exports;

use App\Models\Grupo;
use Barryvdh\DomPDF\Facade\Pdf;

class GruposPDF
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    /**
     * Genera el PDF de grupos
     */
    public function descargar()
    {
        // Query base
        $query = Grupo::query();

        // Aplicar filtros de búsqueda si existen
        if (isset($this->filtros['search']) && $this->filtros['search']) {
            $search = $this->filtros['search'];
            $query->where(function($q) use ($search) {
                $q->where('codigo', 'like', '%' . $search . '%')
                  ->orWhere('nombre', 'like', '%' . $search . '%');
            });
        }

        // Obtener grupos
        $grupos = $query->orderBy('id_grupo', 'desc')->get();

        // Generar el PDF
        $pdf = Pdf::loadView('exports.grupos-pdf', [
            'grupos' => $grupos,
            'fecha' => now()->format('d/m/Y H:i'),
        ]);

        // Configurar tamaño y orientación
        $pdf->setPaper('a4', 'portrait');

        // Retornar el PDF para descarga
        return $pdf->download('Grupos_' . now()->format('Y-m-d') . '.pdf');
    }
}