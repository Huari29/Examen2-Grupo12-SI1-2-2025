<?php

namespace App\Livewire\GestionAcademica\AsignarMateriasGrupos;

use Livewire\Component;
use App\Models\MateriaGrupo;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    // La asignación materia-grupo que se está editando
    public MateriaGrupo $materiaGrupo;
    
    // Campos del formulario
    public $id_materia = '';
    public $id_grupo = '';
    public $id_docente = '';
    public $gestion = '';
    public $activo = true;

    /**
     * Se ejecuta al montar el componente
     * Carga los datos de la asignación existente
     * @param MateriaGrupo $materiaGrupo - La asignación a editar (inyección de dependencia por route model binding)
     */
    public function mount(MateriaGrupo $materiaGrupo)
    {
        // Guardar referencia a la asignación
        $this->materiaGrupo = $materiaGrupo;
        
        // Cargar datos existentes en el formulario
        $this->id_materia = $materiaGrupo->id_materia;
        $this->id_grupo = $materiaGrupo->id_grupo;
        $this->id_docente = $materiaGrupo->id_docente;
        $this->gestion = $materiaGrupo->gestion;
        $this->activo = $materiaGrupo->activo;
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
        'gestion.required' => 'La gestión es obligatoria',
    ];

    /**
     * Actualiza la asignación materia-grupo-docente
     * Valida que no exista duplicado (excluyendo el registro actual)
     */
    public function update()
    {
        // Validar todos los campos
        $this->validate();

        // Verificar que no exista ya esta combinación materia-grupo-gestion
        // (excluyendo el registro actual que estamos editando)
        $existe = MateriaGrupo::where('id_materia', $this->id_materia)
            ->where('id_grupo', $this->id_grupo)
            ->where('gestion', $this->gestion)
            ->where('id_mg', '!=', $this->materiaGrupo->id_mg) // Excluir el registro actual
            ->exists();

        if ($existe) {
            // Si existe, mostrar error
            $this->addError('id_grupo', 'Esta materia ya está asignada a este grupo en esta gestión.');
            return;
        }

        // Actualizar la asignación
        $this->materiaGrupo->update([
            'id_materia' => $this->id_materia,
            'id_grupo' => $this->id_grupo,
            'id_docente' => $this->id_docente,
            'gestion' => $this->gestion,
            'activo' => $this->activo,
        ]);

        // Mensaje de éxito
        session()->flash('message', 'Asignación actualizada exitosamente.');

        // Redirigir al listado
        return redirect()->route('asignar-materias-grupos.index');
    }

    /**
     * Renderiza la vista con los datos necesarios
     */
    public function render()
    {
        // Obtener materias activas ordenadas por nombre
        $materias = Materia::where('activo', true)->orderBy('nombre')->get();
        
        // Obtener grupos activos ordenados por nombre
        $grupos = Grupo::where('activo', true)->orderBy('nombre')->get();
        
        // Obtener docentes (usuarios con rol Docente) activos ordenados por nombre
        $docentes = User::whereHas('rol', function($query) {
            $query->where('nombre', 'Docente');
        })->where('activo', true)->orderBy('nombre')->get();

        return view('livewire.gestion-academica.asignar-materias-grupos.edit', [
            'materias' => $materias,
            'grupos' => $grupos,
            'docentes' => $docentes,
        ]);
    }
}