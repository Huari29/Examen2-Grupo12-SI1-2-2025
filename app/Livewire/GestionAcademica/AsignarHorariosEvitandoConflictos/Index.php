<?php

namespace App\Livewire\GestionAcademica\AsignarHorariosEvitandoConflictos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MateriaGrupo;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $asignacionToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($asignacionId)
    {
        $this->asignacionToDelete = $asignacionId;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->asignacionToDelete) {
            MateriaGrupo::find($this->asignacionToDelete)->delete();
            $this->confirmingDeletion = false;
            $this->asignacionToDelete = null;
            session()->flash('message', 'Asignación eliminada exitosamente.');
        }
    }

    public function toggleActivo($asignacionId)
    {
        $asignacion = MateriaGrupo::find($asignacionId);
        $asignacion->activo = !$asignacion->activo;
        $asignacion->save();
        session()->flash('message', 'Estado actualizado exitosamente.');
    }

    public function render()
    {
        $asignaciones = MateriaGrupo::with(['materia', 'grupo', 'docente'])
            ->where(function($query) {
                $query->whereHas('materia', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('grupo', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('docente', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('creado_en', 'desc')
            ->paginate(10);

        return view('livewire.gestion-academica.asignar-horarios-evitando-conflictos.index', [
            'asignaciones' => $asignaciones
        ]);
    }
}