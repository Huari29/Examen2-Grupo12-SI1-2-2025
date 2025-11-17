<?php

namespace App\Exports;

use App\Models\Grupo;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

class GruposWord
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    /**
     * Genera el documento Word
     */
    public function descargar()
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

        // Crear documento
        $phpWord = new PhpWord();
        
        // Configurar documento
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        // Agregar sección
        $section = $phpWord->addSection([
            'marginTop' => 1000,
            'marginBottom' => 1000,
            'marginLeft' => 1000,
            'marginRight' => 1000,
        ]);

        // Título
        $section->addText(
            'LISTADO DE GRUPOS',
            ['bold' => true, 'size' => 16, 'color' => '1F2937'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 200]
        );

        $section->addText(
            'Sistema de Gestión Académica',
            ['size' => 11, 'color' => '6B7280'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 400]
        );

        // Información del reporte
        $section->addText(
            'Fecha de generación: ' . now()->format('d/m/Y H:i'),
            ['size' => 10, 'color' => '6B7280'],
            ['spaceAfter' => 100]
        );

        $section->addText(
            'Total de registros: ' . $grupos->count(),
            ['size' => 10, 'color' => '6B7280'],
            ['spaceAfter' => 300]
        );

        // Crear tabla
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'CCCCCC',
            'cellMargin' => 80,
            'alignment' => Jc::CENTER,
            'width' => 100 * 50, // 100% del ancho
        ]);

        // Encabezados de tabla
        $table->addRow(500);
        $table->addCell(800, ['bgColor' => '4F46E5'])->addText('N°', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(2000, ['bgColor' => '4F46E5'])->addText('Código', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(4000, ['bgColor' => '4F46E5'])->addText('Nombre', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(2000, ['bgColor' => '4F46E5'])->addText('Turno', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(1500, ['bgColor' => '4F46E5'])->addText('Gestión', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $table->addCell(1500, ['bgColor' => '4F46E5'])->addText('Estado', ['bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);

        // Datos de la tabla
        foreach ($grupos as $index => $grupo) {
            $table->addRow();
            $table->addCell(800)->addText($index + 1, [], ['alignment' => Jc::CENTER]);
            $table->addCell(2000)->addText($grupo->codigo);
            $table->addCell(4000)->addText($grupo->nombre);
            $table->addCell(2000)->addText($grupo->turno);
            $table->addCell(1500)->addText($grupo->gestion ?? 'N/A', [], ['alignment' => Jc::CENTER]);
            $table->addCell(1500)->addText(
                $grupo->activo ? 'Activo' : 'Inactivo',
                ['color' => $grupo->activo ? '065F46' : '991B1B'],
                ['alignment' => Jc::CENTER]
            );
        }

        // Guardar documento en un archivo temporal
        $filename = 'Grupos_' . now()->format('Y-m-d') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        // Retornar respuesta de descarga
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}