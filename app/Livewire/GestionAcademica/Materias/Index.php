<?php

namespace App\Livewire\GestionAcademica\Materias;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Materia;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $materiaToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($materiaId)
    {
        $this->materiaToDelete = $materiaId;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->materiaToDelete) {
            Materia::find($this->materiaToDelete)->delete();
            $this->confirmingDeletion = false;
            $this->materiaToDelete = null;
            session()->flash('message', 'Materia eliminada exitosamente.');
        }
    }

    public function toggleActivo($materiaId)
    {
        $materia = Materia::find($materiaId);
        $materia->activo = !$materia->activo;
        $materia->save();
        session()->flash('message', 'Estado actualizado exitosamente.');
    }

    public function render()
    {
        $materias = Materia::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('codigo', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.gestion-academica.materias.index', [
            'materias' => $materias
        ]);
    }
}