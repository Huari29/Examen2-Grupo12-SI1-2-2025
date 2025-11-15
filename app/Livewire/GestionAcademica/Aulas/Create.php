<?php

namespace App\Livewire\GestionAcademica\Aulas;

use Livewire\Component;
use App\Models\Aula;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Create extends Component
{
    public $codigo = '';
    public $nombre = '';
    public $capacidad = '';
    public $ubicacion = '';
    public $activo = true;

    protected $rules = [
        'codigo' => 'required|string|max:20|unique:aulas,codigo',
        'nombre' => 'required|string|max:255',
        'capacidad' => 'required|integer|min:1|max:500',
        'ubicacion' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'codigo.required' => 'El código es obligatorio',
        'codigo.unique' => 'Este código ya está registrado',
        'nombre.required' => 'El nombre es obligatorio',
        'capacidad.required' => 'La capacidad es obligatoria',
        'capacidad.integer' => 'La capacidad debe ser un número',
        'capacidad.min' => 'La capacidad debe ser al menos 1 persona',
        'capacidad.max' => 'La capacidad no puede exceder 500 personas',
    ];

    public function save()
    {
        $this->validate();

        Aula::create([
            'codigo' => strtoupper($this->codigo),
            'nombre' => $this->nombre,
            'capacidad' => $this->capacidad,
            'ubicacion' => $this->ubicacion,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Aula creada exitosamente.');

        return redirect()->route('aulas.index');
    }

    public function render()
    {
        return view('livewire.gestion-academica.aulas.create');
    }
}