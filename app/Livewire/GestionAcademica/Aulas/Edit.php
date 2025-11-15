<?php

namespace App\Livewire\GestionAcademica\Aulas;

use Livewire\Component;
use App\Models\Aula;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app.sidebar')]
class Edit extends Component
{
    public Aula $aula;
    
    public $codigo = '';
    public $nombre = '';
    public $capacidad = '';
    public $ubicacion = '';
    public $activo = true;

    public function mount(Aula $aula)
    {
        $this->aula = $aula;
        $this->codigo = $aula->codigo;
        $this->nombre = $aula->nombre;
        $this->capacidad = $aula->capacidad;
        $this->ubicacion = $aula->ubicacion;
        $this->activo = $aula->activo;
    }

    protected function rules()
    {
        return [
            'codigo' => 'required|string|max:20|unique:aulas,codigo,' . $this->aula->id_aula . ',id_aula',
            'nombre' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1|max:500',
            'ubicacion' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'codigo.required' => 'El código es obligatorio',
        'codigo.unique' => 'Este código ya está registrado',
        'nombre.required' => 'El nombre es obligatorio',
        'capacidad.required' => 'La capacidad es obligatoria',
        'capacidad.integer' => 'La capacidad debe ser un número',
        'capacidad.min' => 'La capacidad debe ser al menos 1 persona',
        'capacidad.max' => 'La capacidad no puede exceder 500 personas',
    ];

    public function update()
    {
        $this->validate();

        $this->aula->update([
            'codigo' => strtoupper($this->codigo),
            'nombre' => $this->nombre,
            'capacidad' => $this->capacidad,
            'ubicacion' => $this->ubicacion,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Aula actualizada exitosamente.');

        return redirect()->route('aulas.index');
    }

    public function render()
    {
        return view('livewire.gestion-academica.aulas.edit');
    }
}