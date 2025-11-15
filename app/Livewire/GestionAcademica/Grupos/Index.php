<?php

namespace App\Livewire\GestionAcademica\Grupos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Grupo;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $grupoToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($grupoId)
    {
        $this->grupoToDelete = $grupoId;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->grupoToDelete) {
            Grupo::find($this->grupoToDelete)->delete();
            $this->confirmingDeletion = false;
            $this->grupoToDelete = null;
            session()->flash('message', 'Grupo eliminado exitosamente.');
        }
    }

    public function toggleActivo($grupoId)
    {
        $grupo = Grupo::find($grupoId);
        $grupo->activo = !$grupo->activo;
        $grupo->save();
        session()->flash('message', 'Estado actualizado exitosamente.');
    }

    public function render()
    {
        $grupos = Grupo::with('materias')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('codigo', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.gestion-academica.grupos.index', [
            'grupos' => $grupos
        ]);
    }
}