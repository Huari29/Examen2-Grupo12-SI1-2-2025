<?php

namespace App\Livewire\GestionAcademica\Horarios;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Horario;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $confirmingDeletion = false;
    public $horarioToDelete = null;

    public function confirmDelete($horarioId)
    {
        $this->horarioToDelete = $horarioId;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->horarioToDelete) {
            Horario::find($this->horarioToDelete)->delete();
            $this->confirmingDeletion = false;
            $this->horarioToDelete = null;
            session()->flash('message', 'Horario eliminado exitosamente.');
        }
    }

    public function render()
    {
        return view('livewire.gestion-academica.horarios.index', [
            'horarios' => Horario::orderBy('hora_inicio', 'asc')->paginate(10)
        ]);
    }
}