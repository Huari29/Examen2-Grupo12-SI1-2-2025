<?php

namespace App\Livewire\GestionAcademica\Aulas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Aula;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $aulaToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($aulaId)
    {
        $this->aulaToDelete = $aulaId;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->aulaToDelete) {
            Aula::find($this->aulaToDelete)->delete();
            $this->confirmingDeletion = false;
            $this->aulaToDelete = null;
            session()->flash('message', 'Aula eliminada exitosamente.');
        }
    }

    public function toggleActivo($aulaId)
    {
        $aula = Aula::find($aulaId);
        $aula->activo = !$aula->activo;
        $aula->save();
        session()->flash('message', 'Estado actualizado exitosamente.');
    }

    public function render()
    {
        $aulas = Aula::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('codigo', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.gestion-academica.aulas.index', [
            'aulas' => $aulas
        ]);
    }
}