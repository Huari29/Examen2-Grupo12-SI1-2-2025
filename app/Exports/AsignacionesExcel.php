<?php

namespace App\Exports;

use App\Models\DetalleHorario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AsignacionesExcel implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $gestion;

    public function __construct($gestion)
    {
        $this->gestion = $gestion;
    }

    /**
     * Retorna la colección de datos para el Excel
     */
    public function collection()
    {
        $detalles = DetalleHorario::with([
            'materiaGrupo.materia',
            'materiaGrupo.grupo',
            'materiaGrupo.docente',
            'horario',
            'aula'
        ])
        ->where('gestion', $this->gestion)
        ->where('estado', 'Activo')
        ->orderBy('dia_semana')
        ->get();

        return $detalles->map(function ($detalle, $index) {
            return [
                'N°' => $index + 1,
                'Código Materia' => $detalle->materiaGrupo->materia->codigo,
                'Materia' => $detalle->materiaGrupo->materia->nombre,
                'Código Grupo' => $detalle->materiaGrupo->grupo->codigo,
                'Grupo' => $detalle->materiaGrupo->grupo->nombre,
                'Turno' => $detalle->materiaGrupo->grupo->turno,
                'Docente' => $detalle->materiaGrupo->docente->nombre,
                'Día' => $detalle->dia_semana,
                'Hora Inicio' => \Carbon\Carbon::parse($detalle->horario->hora_inicio)->format('H:i'),
                'Hora Fin' => \Carbon\Carbon::parse($detalle->horario->hora_fin)->format('H:i'),
                'Aula' => $detalle->aula->codigo . ' - ' . $detalle->aula->nombre,
                'Capacidad' => $detalle->aula->capacidad,
                'Gestión' => $detalle->gestion,
                'Estado' => $detalle->estado,
            ];
        });
    }

    /**
     * Define los encabezados de las columnas
     */
    public function headings(): array
    {
        return [
            'N°',
            'Código Materia',
            'Materia',
            'Código Grupo',
            'Grupo',
            'Turno',
            'Docente',
            'Día',
            'Hora Inicio',
            'Hora Fin',
            'Aula',
            'Capacidad',
            'Gestión',
            'Estado',
        ];
    }

    /**
     * Aplica estilos al Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Azul
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

        // Aplicar bordes a todas las celdas con datos
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:N' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Centrar la columna N°
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Centrar las columnas de hora
        $sheet->getStyle('I2:J' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Centrar capacidad
        $sheet->getStyle('L2:L' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Ajustar altura de la fila del encabezado
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }

    /**
     * Define el ancho de las columnas
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // N°
            'B' => 15,  // Código Materia
            'C' => 35,  // Materia
            'D' => 15,  // Código Grupo
            'E' => 25,  // Grupo
            'F' => 12,  // Turno
            'G' => 30,  // Docente
            'H' => 12,  // Día
            'I' => 12,  // Hora Inicio
            'J' => 12,  // Hora Fin
            'K' => 25,  // Aula
            'L' => 12,  // Capacidad
            'M' => 12,  // Gestión
            'N' => 10,  // Estado
        ];
    }

    /**
     * Define el título de la hoja
     */
    public function title(): string
    {
        return 'Asignaciones ' . $this->gestion;
    }

    /**
     * Descarga el archivo Excel
     */
    public function descargar()
    {
        return \Maatwebsite\Excel\Facades\Excel::download($this, 'Asignaciones_' . $this->gestion . '.xlsx');
    }
}