<?php

namespace App\Livewire\GestionAcademica\Reportes;

use Livewire\Component;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Aula;
use App\Exports\HorarioPorGrupoPDF;
use App\Exports\HorarioPorDocentePDF;
use App\Exports\HorarioPorAulaPDF;
use App\Exports\AsignacionesExcel;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    // Filtros para reportes
    public $tipo_reporte = '';
    public $id_grupo = '';
    public $id_docente = '';
    public $id_aula = '';
    public $gestion = '';

    /**
     * Reglas de validación
     */
    protected function rules()
    {
        $rules = [
            'tipo_reporte' => 'required',
            'gestion' => 'required|string',
        ];

        // Agregar validación según el tipo de reporte
        if ($this->tipo_reporte === 'horario_grupo') {
            $rules['id_grupo'] = 'required|exists:grupos,id_grupo';
        } elseif ($this->tipo_reporte === 'horario_docente') {
            $rules['id_docente'] = 'required|exists:usuarios,id';
        } elseif ($this->tipo_reporte === 'horario_aula') {
            $rules['id_aula'] = 'required|exists:aulas,id_aula';
        }

        return $rules;
    }

    /**
     * Mensajes de error personalizados
     */
    protected $messages = [
        'tipo_reporte.required' => 'Debes seleccionar un tipo de reporte',
        'id_grupo.required' => 'Debes seleccionar un grupo',
        'id_docente.required' => 'Debes seleccionar un docente',
        'id_aula.required' => 'Debes seleccionar un aula',
        'gestion.required' => 'La gestión es obligatoria',
    ];

    /**
     * Se ejecuta cuando cambia el tipo de reporte
     * Limpia los filtros anteriores
     */
    public function updatedTipoReporte()
    {
        $this->id_grupo = '';
        $this->id_docente = '';
        $this->id_aula = '';
    }

    /**
     * Genera el reporte según el tipo seleccionado
     */
    public function generarReporte()
    {
        // Validar los campos
        $this->validate();

        try {
            // Generar reporte según el tipo
            switch ($this->tipo_reporte) {
                case 'horario_grupo':
                    return $this->descargarHorarioGrupo();
                
                case 'horario_docente':
                    return $this->descargarHorarioDocente();
                
                case 'horario_aula':
                    return $this->descargarHorarioAula();
                
                case 'asignaciones_excel':
                    return $this->descargarAsignacionesExcel();
                
                default:
                    session()->flash('error', 'Tipo de reporte no válido.');
                    return;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Descarga el horario de un grupo en PDF
     */
    private function descargarHorarioGrupo()
    {
        $exporter = new HorarioPorGrupoPDF($this->id_grupo, $this->gestion);
        return $exporter->descargar();
    }

    /**
     * Descarga el horario de un docente en PDF
     */
    private function descargarHorarioDocente()
    {
        $exporter = new HorarioPorDocentePDF($this->id_docente, $this->gestion);
        return $exporter->descargar();
    }

    /**
     * Descarga el horario de un aula en PDF
     */
    private function descargarHorarioAula()
    {
        $exporter = new HorarioPorAulaPDF($this->id_aula, $this->gestion);
        return $exporter->descargar();
    }

    /**
     * Descarga todas las asignaciones en Excel
     */
    private function descargarAsignacionesExcel()
    {
        $exporter = new AsignacionesExcel($this->gestion);
        return $exporter->descargar();
    }

    /**
     * Renderiza la vista con los datos necesarios
     */
    public function render()
    {
        // Obtener grupos activos
        $grupos = Grupo::where('activo', true)->orderBy('codigo')->get();
        
        // Obtener docentes activos
        $docentes = User::whereHas('rol', function($query) {
            $query->where('nombre', 'Docente');
        })->where('activo', true)->orderBy('nombre')->get();
        
        // Obtener aulas activas
        $aulas = Aula::where('activo', true)->orderBy('codigo')->get();

        return view('livewire.gestion-academica.reportes.index', [
            'grupos' => $grupos,
            'docentes' => $docentes,
            'aulas' => $aulas,
        ]);
    }
}