<?php

namespace App\Livewire\GestionAcademica\Grupos;

use Livewire\Component;
use App\Models\Grupo;
use App\Models\Materia;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public Grupo $grupo;
    
    public $codigo = '';
    public $nombre = '';
    public $id_materia = '';
    public $gestion = '';
    public $activo = true;

    public function mount(Grupo $grupo)
    {
        $this->grupo = $grupo;
        $this->codigo = $grupo->codigo;
        $this->nombre = $grupo->nombre;
        $this->id_materia = $grupo->id_materia;
        $this->gestion = $grupo->gestion;
        $this->activo = $grupo->activo;
    }

    protected function rules()
    {
        return [
            'codigo' => 'required|string|max:20|unique:grupos,codigo,' . $this->grupo->id_grupo . ',id_grupo',
            'nombre' => 'required|string|max:255',
            'id_materia' => 'required|exists:materias,id_materia',
            'gestion' => 'required|string|max:10',
        ];
    }

    protected $messages = [
        'codigo.required' => 'El código es obligatorio',
        'codigo.unique' => 'Este código ya está registrado',
        'nombre.required' => 'El nombre es obligatorio',
        'id_materia.required' => 'Debes seleccionar una materia',
        'id_materia.exists' => 'La materia seleccionada no es válida',
        'gestion.required' => 'La gestión es obligatoria',
    ];

    public function update()
    {
        $this->validate();

        $this->grupo->update([
            'codigo' => strtoupper($this->codigo),
            'nombre' => $this->nombre,
            'id_materia' => $this->id_materia,
            'gestion' => $this->gestion,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Grupo actualizado exitosamente.');

        return redirect()->route('grupos.index');
    }

    public function render()
    {
        $materias = Materia::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('livewire.gestion-academica.grupos.edit', [
            'materias' => $materias
        ]);
    }
}