<?php

namespace App\Livewire\GestionAcademica\Horarios;

use Livewire\Component;
use App\Models\Horario;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public Horario $horario;
    
    public $hora_inicio = '';
    public $hora_fin = '';

    public function mount(Horario $horario)
    {
        $this->horario = $horario;
        $this->hora_inicio = \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i');
        $this->hora_fin = \Carbon\Carbon::parse($horario->hora_fin)->format('H:i');
    }

    protected $rules = [
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
    ];

    protected $messages = [
        'hora_inicio.required' => 'La hora de inicio es obligatoria',
        'hora_inicio.date_format' => 'Formato de hora inválido (HH:MM)',
        'hora_fin.required' => 'La hora de fin es obligatoria',
        'hora_fin.date_format' => 'Formato de hora inválido (HH:MM)',
        'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio',
    ];

    public function update()
    {
        $this->validate();

        $this->horario->update([
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
        ]);

        session()->flash('message', 'Horario actualizado exitosamente.');

        return redirect()->route('horarios.index');
    }

    public function render()
    {
        return view('livewire.gestion-academica.horarios.edit');
    }
}