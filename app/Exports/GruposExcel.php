<?php

namespace App\Exports;

use App\Models\Grupo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GruposExcel implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    /**
     * Retorna la colección de datos
     */
    public function collection()
    {
        // Query base
        $query = Grupo::query();

        // Aplicar filtros
        if (isset($this->filtros['search']) && $this->filtros['search']) {
            $search = $this->filtros['search'];
            $query->where(function($q) use ($search) {
                $q->where('codigo', 'like', '%' . $search . '%')
                  ->orWhere('nombre', 'like', '%' . $search . '%');
            });
        }

        $grupos = $query->orderBy('id_grupo', 'desc')->get();

        return $grupos->map(function ($grupo, $index) {
            return [
                'N°' => $index + 1,
                'Código' => $grupo->codigo,
                'Nombre' => $grupo->nombre,
                'Turno' => $grupo->turno,
                'Gestión' => $grupo->gestion ?? 'N/A',
                'Estado' => $grupo->activo ? 'Activo' : 'Inactivo',
                'Fecha Creación' => $grupo->creado_en ? $grupo->creado_en->format('d/m/Y H:i') : 'N/A',
            ];
        });
    }

    /**
     * Encabezados
     */
    public function headings(): array
    {
        return [
            'N°',
            'Código',
            'Nombre',
            'Turno',
            'Gestión',
            'Estado',
            'Fecha Creación',
        ];
    }

    /**
     * Estilos
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo del encabezado
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Bordes para todas las celdas
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:G' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Centrar columna N°
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Altura del encabezado
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }

    /**
     * Ancho de columnas
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 15,
            'C' => 35,
            'D' => 15,
            'E' => 15,
            'F' => 12,
            'G' => 20,
        ];
    }

    /**
     * Título de la hoja
     */
    public function title(): string
    {
        return 'Grupos';
    }

    /**
     * Descarga el Excel
     */
    public function descargar()
    {
        return \Maatwebsite\Excel\Facades\Excel::download($this, 'Grupos_' . now()->format('Y-m-d') . '.xlsx');
    }
}