<?php

namespace App\Livewire\GestionAcademica\AsignarHorariosEvitandoConflictos;

use Livewire\Component;
use App\Models\MateriaGrupo;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public MateriaGrupo $materiaGrupo;
    
    public $id_materia = '';
    public $id_grupo = '';
    public $id_docente = '';
    public $gestion = '';
    public $activo = true;

    public function mount(MateriaGrupo $materiaGrupo)
    {
        $this->materiaGrupo = $materiaGrupo;
        $this->id_materia = $materiaGrupo->id_materia;
        $this->id_grupo = $materiaGrupo->id_grupo;
        $this->id_docente = $materiaGrupo->id_docente;
        $this->gestion = $materiaGrupo->gestion;
        $this->activo = $materiaGrupo->activo;
    }

    protected function rules()
    {
        return [
            'id_materia' => 'required|exists:materias,id_materia',
            'id_grupo' => 'required|exists:grupos,id_grupo',
            'id_docente' => 'required|exists:usuarios,id',
            'gestion' => 'required|string|max:20',
        ];
    }

    protected $messages = [
        'id_materia.required' => 'Debes seleccionar una materia',
        'id_materia.exists' => 'La materia seleccionada no es válida',
        'id_grupo.required' => 'Debes seleccionar un grupo',
        'id_grupo.exists' => 'El grupo seleccionado no es válido',
        'id_docente.required' => 'Debes seleccionar un docente',
        'id_docente.exists' => 'El docente seleccionado no es válido',
        'gestion.required' => 'La gestión es obligatoria',
    ];

    public function update()
    {
        $this->validate();

        // Verificar que no exista ya esta combinación (excluyendo el registro actual)
        $existe = MateriaGrupo::where('id_materia', $this->id_materia)
            ->where('id_grupo', $this->id_grupo)
            ->where('gestion', $this->gestion)
            ->where('id_mg', '!=', $this->materiaGrupo->id_mg)
            ->exists();

        if ($existe) {
            $this->addError('id_grupo', 'Ya existe una asignación para esta materia, grupo y gestión.');
            return;
        }

        $this->materiaGrupo->update([
            'id_materia' => $this->id_materia,
            'id_grupo' => $this->id_grupo,
            'id_docente' => $this->id_docente,
            'gestion' => $this->gestion,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Asignación actualizada exitosamente.');

        return redirect()->route('asignar-horarios.index');
    }

    public function render()
    {
        $materias = Materia::where('activo', true)->orderBy('nombre')->get();
        $grupos = Grupo::where('activo', true)->orderBy('nombre')->get();
        $docentes = User::whereHas('rol', function($query) {
            $query->where('nombre', 'Docente');
        })->where('activo', true)->orderBy('nombre')->get();

        return view('livewire.gestion-academica.asignar-horarios-evitando-conflictos.edit', [
            'materias' => $materias,
            'grupos' => $grupos,
            'docentes' => $docentes
        ]);
    }
}