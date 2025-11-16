<?php

namespace App\Livewire\GestionAcademica\AsignarHorariosEvitandoConflictos;

use Livewire\Component;
use App\Models\DetalleHorario;
use App\Models\MateriaGrupo;
use App\Models\Horario;
use App\Models\Aula;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Create extends Component
{
    // Paso 1: Seleccionar una asignación materia-grupo-docente existente
    public $id_mg = '';
    
    // Paso 2: Seleccionar Horario, Día y Aula
    public $id_horario = '';
    public $dia_semana = '';
    public $id_aula = '';
    
    // Datos adicionales
    public $gestion = '';
    public $estado = 'Activo';
    
    // Información de la asignación seleccionada (para mostrar al usuario)
    public $asignacionSeleccionada = null;
    
    // Mensajes de conflicto
    public $conflictos = [];

    /**
     * Reglas de validación del formulario
     */
    protected function rules()
    {
        return [
            'id_mg' => 'required|exists:materia_grupo,id_mg',
            'id_horario' => 'required|exists:horarios,id',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'id_aula' => 'required|exists:aulas,id_aula',
            'gestion' => 'required|string|max:20',
        ];
    }

    /**
     * Mensajes de error personalizados
     */
    protected $messages = [
        'id_mg.required' => 'Debes seleccionar una asignación materia-grupo',
        'id_mg.exists' => 'La asignación seleccionada no es válida',
        'id_horario.required' => 'Debes seleccionar un horario',
        'id_horario.exists' => 'El horario seleccionado no es válido',
        'dia_semana.required' => 'Debes seleccionar un día de la semana',
        'dia_semana.in' => 'El día seleccionado no es válido',
        'id_aula.required' => 'Debes seleccionar un aula',
        'id_aula.exists' => 'El aula seleccionada no es válida',
        'gestion.required' => 'La gestión es obligatoria',
    ];

    /**
     * Se ejecuta cuando cambia la asignación materia-grupo seleccionada
     * Carga la información de la asignación para mostrarla al usuario
     */
    public function updatedIdMg()
    {
        if ($this->id_mg) {
            // Cargar la asignación con sus relaciones
            $this->asignacionSeleccionada = MateriaGrupo::with(['materia', 'grupo', 'docente'])
                ->find($this->id_mg);
            
            // Auto-completar la gestión con la de la asignación
            if ($this->asignacionSeleccionada) {
                $this->gestion = $this->asignacionSeleccionada->gestion;
            }
        } else {
            $this->asignacionSeleccionada = null;
            $this->gestion = '';
        }
        
        // Limpiar conflictos cuando cambia la asignación
        $this->conflictos = [];
    }

    /**
     * Se ejecuta cuando cambian los campos de horario, día o aula
     * Valida conflictos en tiempo real
     */
    public function updatedIdHorario()
    {
        $this->validarConflictos();
    }

    public function updatedDiaSemana()
    {
        $this->validarConflictos();
    }

    public function updatedIdAula()
    {
        $this->validarConflictos();
    }

    /**
     * Valida conflictos de horario
     * Verifica que no haya cruces de aula, docente o grupo
     */
    private function validarConflictos()
    {
        // Limpiar conflictos previos
        $this->conflictos = [];
        
        // Solo validar si tenemos todos los datos necesarios
        if (!$this->id_mg || !$this->id_horario || !$this->dia_semana || !$this->gestion) {
            return;
        }

        // Cargar la asignación si no está cargada
        if (!$this->asignacionSeleccionada) {
            $this->asignacionSeleccionada = MateriaGrupo::with(['materia', 'grupo', 'docente'])
                ->find($this->id_mg);
        }

        // 1. Validar conflicto de AULA
        if ($this->id_aula) {
            $conflictoAula = DetalleHorario::tieneConflictoAula(
                $this->id_aula,
                $this->id_horario,
                $this->dia_semana,
                $this->gestion
            );
            
            if ($conflictoAula) {
                $this->conflictos[] = 'El aula ya está ocupada en este horario y día.';
            }
        }

        // 2. Validar conflicto de DOCENTE
        if ($this->asignacionSeleccionada) {
            $conflictoDocente = DetalleHorario::tieneConflictoDocente(
                $this->asignacionSeleccionada->id_docente,
                $this->id_horario,
                $this->dia_semana,
                $this->gestion
            );
            
            if ($conflictoDocente) {
                $this->conflictos[] = 'El docente ya tiene clase asignada en este horario y día.';
            }
        }

        // 3. Validar conflicto de GRUPO
        if ($this->asignacionSeleccionada) {
            $conflictoGrupo = DetalleHorario::tieneConflictoGrupo(
                $this->asignacionSeleccionada->id_grupo,
                $this->id_horario,
                $this->dia_semana,
                $this->gestion
            );
            
            if ($conflictoGrupo) {
                $this->conflictos[] = 'El grupo ya tiene clase asignada en este horario y día.';
            }
        }
    }

    /**
     * Guarda el detalle de horario
     */
    public function save()
    {
        // Validar todos los campos
        $this->validate();

        // Validar conflictos una última vez
        $this->validarConflictos();
        
        // Si hay conflictos, no permitir guardar
        if (count($this->conflictos) > 0) {
            $this->addError('conflictos', 'No se puede guardar debido a conflictos de horario.');
            return;
        }

        // Crear el detalle de horario
        DetalleHorario::create([
            'id_mg' => $this->id_mg,
            'id_horario' => $this->id_horario,
            'id_aula' => $this->id_aula,
            'dia_semana' => $this->dia_semana,
            'gestion' => $this->gestion,
            'estado' => $this->estado,
        ]);

        // Mensaje de éxito
        session()->flash('message', 'Horario asignado exitosamente.');

        // Redirigir al listado
        return redirect()->route('asignar-horarios.index');
    }

    /**
     * Renderiza la vista con todos los datos necesarios
     */
    public function render()
    {
        // Obtener asignaciones materia-grupo activas con sus relaciones
        // Solo mostramos las que están activas
        $asignaciones = MateriaGrupo::with(['materia', 'grupo', 'docente'])
            ->where('activo', true)
            ->orderBy('id_mg', 'desc')
            ->get();
        
        // Obtener horarios disponibles
        $horarios = Horario::orderBy('hora_inicio')->get();
        
        // Obtener aulas activas
        $aulas = Aula::where('activo', true)->orderBy('nombre')->get();
        
        // Días de la semana disponibles
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return view('livewire.gestion-academica.asignar-horarios-evitando-conflictos.create', [
            'asignaciones' => $asignaciones,
            'horarios' => $horarios,
            'aulas' => $aulas,
            'dias' => $dias,
        ]);
    }
}