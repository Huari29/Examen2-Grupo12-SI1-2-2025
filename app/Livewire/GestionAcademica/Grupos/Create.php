<?php

namespace App\Livewire\GestionAcademica\Grupos;

use Livewire\Component;
use App\Models\Grupo;
use App\Models\Materia;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Create extends Component
{
    public $codigo = '';
    public $nombre = '';
    public $id_materia = '';
    public $gestion = '';
    public $activo = true;

    protected $rules = [
        'codigo' => 'required|string|max:20|unique:grupos,codigo',
        'nombre' => 'required|string|max:255',
        'id_materia' => 'required|exists:materias,id_materia',
        'gestion' => 'required|string|max:10',
    ];

    protected $messages = [
        'codigo.required' => 'El código es obligatorio',
        'codigo.unique' => 'Este código ya está registrado',
        'nombre.required' => 'El nombre es obligatorio',
        'id_materia.required' => 'Debes seleccionar una materia',
        'id_materia.exists' => 'La materia seleccionada no es válida',
        'gestion.required' => 'La gestión es obligatoria',
    ];

    public function save()
    {
        $this->validate();

        Grupo::create([
            'codigo' => strtoupper($this->codigo),
            'nombre' => $this->nombre,
            'id_materia' => $this->id_materia,
            'gestion' => $this->gestion,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Grupo creado exitosamente.');

        return redirect()->route('grupos.index');
    }

    public function render()
    {
        $materias = Materia::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('livewire.gestion-academica.grupos.create', [
            'materias' => $materias
        ]);
    }
}