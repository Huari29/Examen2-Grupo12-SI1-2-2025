<?php

namespace App\Livewire\GestionAcademica\Materias;

use Livewire\Component;
use App\Models\Materia;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public Materia $materia;
    
    public $codigo = '';
    public $nombre = '';
    public $carga_horaria = '';
    public $gestion_default = '';
    public $activo = true;

    public function mount(Materia $materia)
    {
        $this->materia = $materia;
        $this->codigo = $materia->codigo;
        $this->nombre = $materia->nombre;
        $this->carga_horaria = $materia->carga_horaria;
        $this->gestion_default = $materia->gestion_default;
        $this->activo = $materia->activo;
    }

    protected function rules()
    {
        return [
            'codigo' => 'required|string|max:20|unique:materias,codigo,' . $this->materia->id_materia . ',id_materia',
            'nombre' => 'required|string|max:255',
            'carga_horaria' => 'required|integer|min:1|max:200',
            'gestion_default' => 'required|string|max:10',
        ];
    }

    protected $messages = [
        'codigo.required' => 'El código es obligatorio',
        'codigo.unique' => 'Este código ya está registrado',
        'nombre.required' => 'El nombre es obligatorio',
        'carga_horaria.required' => 'La carga horaria es obligatoria',
        'carga_horaria.integer' => 'La carga horaria debe ser un número',
        'carga_horaria.min' => 'La carga horaria debe ser al menos 1 hora',
        'carga_horaria.max' => 'La carga horaria no puede exceder 200 horas',
        'gestion_default.required' => 'La gestión es obligatoria',
    ];

    public function update()
    {
        $this->validate();

        $this->materia->update([
            'codigo' => strtoupper($this->codigo),
            'nombre' => $this->nombre,
            'carga_horaria' => $this->carga_horaria,
            'gestion_default' => $this->gestion_default,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Materia actualizada exitosamente.');

        return redirect()->route('materias.index');
    }

    public function render()
    {
        return view('livewire.gestion-academica.materias.edit');
    }
}