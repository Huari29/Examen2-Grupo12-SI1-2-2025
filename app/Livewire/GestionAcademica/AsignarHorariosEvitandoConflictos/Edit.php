<?php

namespace App\Livewire\GestionAcademica\AsignarHorariosEvitandoConflictos;

use Livewire\Component;
use App\Models\DetalleHorario;
use App\Models\MateriaGrupo;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Horario;
use App\Models\Aula;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    // El detalle de horario que se está editando
    public DetalleHorario $detalleHorario;
    
    // Datos de la asignación base (materia-grupo-docente)
    public $id_materia = '';
    public $id_grupo = '';
    public $id_docente = '';
    
    // Datos del detalle de horario
    public $id_horario = '';
    public $dia_semana = '';
    public $id_aula = '';
    public $gestion = '';
    public $estado = 'Activo';
    
    // ID de la asignación materia-grupo
    public $id_mg = null;
    
    // Mensajes de conflicto
    public $conflictos = [];

    /**
     * Se ejecuta al montar el componente
     * Carga los datos del detalle existente
     * @param DetalleHorario $detalleHorario - El detalle a editar (inyección de dependencia por route model binding)
     */
    public function mount(DetalleHorario $detalleHorario)
    {
        // Guardar referencia al detalle
        $this->detalleHorario = $detalleHorario;
        
        // Cargar datos de la asignación base
        $this->id_mg = $detalleHorario->id_mg;
        $this->id_materia = $detalleHorario->materiaGrupo->id_materia;
        $this->id_grupo = $detalleHorario->materiaGrupo->id_grupo;
        $this->id_docente = $detalleHorario->materiaGrupo->id_docente;
        
        // Cargar datos del detalle de horario
        $this->id_horario = $detalleHorario->id_horario;
        $this->dia_semana = $detalleHorario->dia_semana;
        $this->id_aula = $detalleHorario->id_aula;
        $this->gestion = $detalleHorario->gestion;
        $this->estado = $detalleHorario->estado;
    }

    /**
     * Reglas de validación del formulario
     */
    protected function rules()
    {
        return [
            'id_materia' => 'required|exists:materias,id_materia',
            'id_grupo' => 'required|exists:grupos,id_grupo',
            'id_docente' => 'required|exists:usuarios,id',
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
        'id_materia.required' => 'Debes seleccionar una materia',
        'id_materia.exists' => 'La materia seleccionada no es válida',
        'id_grupo.required' => 'Debes seleccionar un grupo',
        'id_grupo.exists' => 'El grupo seleccionado no es válido',
        'id_docente.required' => 'Debes seleccionar un docente',
        'id_docente.exists' => 'El docente seleccionado no es válido',
        'id_horario.required' => 'Debes seleccionar un horario',
        'id_horario.exists' => 'El horario seleccionado no es válido',
        'dia_semana.required' => 'Debes seleccionar un día de la semana',
        'dia_semana.in' => 'El día seleccionado no es válido',
        'id_aula.required' => 'Debes seleccionar un aula',
        'id_aula.exists' => 'El aula seleccionada no es válida',
        'gestion.required' => 'La gestión es obligatoria',
    ];

    /**
     * Se ejecuta cuando cambia la selección de materia
     * Verifica si necesita actualizar la asignación base
     */
    public function updatedIdMateria()
    {
        $this->verificarAsignacionBase();
    }

    /**
     * Se ejecuta cuando cambia la selección de grupo
     */
    public function updatedIdGrupo()
    {
        $this->verificarAsignacionBase();
    }

    /**
     * Se ejecuta cuando cambia la selección de docente
     */
    public function updatedIdDocente()
    {
        $this->verificarAsignacionBase();
    }

    /**
     * Verifica si la combinación materia-grupo-docente cambió
     * Si cambió, busca o creará una nueva asignación base
     */
    private function verificarAsignacionBase()
    {
        // Solo verificar si hay materia, grupo y docente seleccionados
        if ($this->id_materia && $this->id_grupo && $this->id_docente) {
            // Verificar si es la misma asignación original
            $asignacionOriginal = $this->detalleHorario->materiaGrupo;
            
            if ($asignacionOriginal->id_materia == $this->id_materia &&
                $asignacionOriginal->id_grupo == $this->id_grupo &&
                $asignacionOriginal->id_docente == $this->id_docente) {
                // No cambió, mantener el mismo id_mg
                $this->id_mg = $asignacionOriginal->id_mg;
            } else {
                // Cambió, buscar si existe esta nueva combinación
                $asignacion = MateriaGrupo::where('id_materia', $this->id_materia)
                    ->where('id_grupo', $this->id_grupo)
                    ->where('id_docente', $this->id_docente)
                    ->first();
                
                if ($asignacion) {
                    // Ya existe esta combinación, usarla
                    $this->id_mg = $asignacion->id_mg;
                } else {
                    // No existe, se creará al guardar
                    $this->id_mg = null;
                }
            }
        }
    }

    /**
     * Se ejecuta cuando cambia el horario
     * Valida conflictos en tiempo real
     */
    public function updatedIdHorario()
    {
        $this->validarConflictos();
    }

    /**
     * Se ejecuta cuando cambia el día de la semana
     */
    public function updatedDiaSemana()
    {
        $this->validarConflictos();
    }

    /**
     * Se ejecuta cuando cambia el aula
     */
    public function updatedIdAula()
    {
        $this->validarConflictos();
    }

    /**
     * Valida conflictos de horario
     * Excluye el registro actual de la validación
     */
    private function validarConflictos()
    {
        // Limpiar conflictos previos
        $this->conflictos = [];
        
        // Solo validar si tenemos todos los datos necesarios
        if (!$this->id_horario || !$this->dia_semana || !$this->gestion) {
            return;
        }

        // 1. Validar conflicto de AULA (excluyendo el registro actual)
        if ($this->id_aula) {
            $conflictoAula = DetalleHorario::tieneConflictoAula(
                $this->id_aula,
                $this->id_horario,
                $this->dia_semana,
                $this->gestion,
                $this->detalleHorario->id_detalle // Excluir el registro actual
            );
            
            if ($conflictoAula) {
                $this->conflictos[] = 'El aula ya está ocupada en este horario y día.';
            }
        }

        // 2. Validar conflicto de DOCENTE (excluyendo el registro actual)
        if ($this->id_docente) {
            $conflictoDocente = DetalleHorario::tieneConflictoDocente(
                $this->id_docente,
                $this->id_horario,
                $this->dia_semana,
                $this->gestion,
                $this->detalleHorario->id_detalle // Excluir el registro actual
            );
            
            if ($conflictoDocente) {
                $this->conflictos[] = 'El docente ya tiene clase asignada en este horario y día.';
            }
        }

        // 3. Validar conflicto de GRUPO (excluyendo el registro actual)
        if ($this->id_grupo) {
            $conflictoGrupo = DetalleHorario::tieneConflictoGrupo(
                $this->id_grupo,
                $this->id_horario,
                $this->dia_semana,
                $this->gestion,
                $this->detalleHorario->id_detalle // Excluir el registro actual
            );
            
            if ($conflictoGrupo) {
                $this->conflictos[] = 'El grupo ya tiene clase asignada en este horario y día.';
            }
        }
    }

    /**
     * Actualiza la asignación de horario
     * Crea nueva asignación base si cambió la combinación materia-grupo-docente
     */
    public function update()
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

        // Si cambió la asignación base y no existe, crearla
        if (!$this->id_mg) {
            $materiaGrupo = MateriaGrupo::create([
                'id_materia' => $this->id_materia,
                'id_grupo' => $this->id_grupo,
                'id_docente' => $this->id_docente,
                'gestion' => $this->gestion,
                'activo' => true,
            ]);
            
            $this->id_mg = $materiaGrupo->id_mg;
        }

        // Actualizar el detalle de horario
        $this->detalleHorario->update([
            'id_mg' => $this->id_mg,
            'id_horario' => $this->id_horario,
            'id_aula' => $this->id_aula,
            'dia_semana' => $this->dia_semana,
            'gestion' => $this->gestion,
            'estado' => $this->estado,
        ]);

        // Mensaje de éxito
        session()->flash('message', 'Asignación de horario actualizada exitosamente.');

        // Redirigir al listado
        return redirect()->route('asignar-horarios.index');
    }

    /**
     * Renderiza la vista con todos los datos necesarios
     */
    public function render()
    {
        // Obtener materias activas
        $materias = Materia::where('activo', true)->orderBy('nombre')->get();
        
        // Obtener grupos activos
        $grupos = Grupo::where('activo', true)->orderBy('nombre')->get();
        
        // Obtener docentes (usuarios con rol Docente)
        $docentes = User::whereHas('rol', function($query) {
            $query->where('nombre', 'Docente');
        })->where('activo', true)->orderBy('nombre')->get();
        
        // Obtener horarios disponibles
        $horarios = Horario::orderBy('hora_inicio')->get();
        
        // Obtener aulas activas
        $aulas = Aula::where('activo', true)->orderBy('nombre')->get();
        
        // Días de la semana disponibles
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return view('livewire.gestion-academica.asignar-horarios-evitando-conflictos.edit', [
            'materias' => $materias,
            'grupos' => $grupos,
            'docentes' => $docentes,
            'horarios' => $horarios,
            'aulas' => $aulas,
            'dias' => $dias,
        ]);
    }
}