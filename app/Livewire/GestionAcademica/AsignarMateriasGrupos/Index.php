<?php

namespace App\Livewire\GestionAcademica\AsignarMateriasGrupos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MateriaGrupo;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Propiedad para el término de búsqueda
    public $search = '';
    
    // Propiedades para controlar el modal de eliminación
    public $confirmingDeletion = false;
    public $asignacionToDelete = null;

    /**
     * Se ejecuta cuando el usuario escribe en el buscador
     * Reinicia la paginación a la primera página
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Abre el modal de confirmación de eliminación
     * @param int $asignacionId - ID de la asignación materia-grupo a eliminar
     */
    public function confirmDelete($asignacionId)
    {
        $this->asignacionToDelete = $asignacionId;
        $this->confirmingDeletion = true;
    }

    /**
     * Elimina la asignación materia-grupo seleccionada
     * También elimina en cascada todos los horarios asociados (por foreign key)
     */
    public function delete()
    {
        // Verificar que hay una asignación seleccionada
        if ($this->asignacionToDelete) {
            // Buscar y eliminar la asignación
            MateriaGrupo::find($this->asignacionToDelete)->delete();
            
            // Cerrar el modal
            $this->confirmingDeletion = false;
            $this->asignacionToDelete = null;
            
            // Mostrar mensaje de éxito
            session()->flash('message', 'Asignación eliminada exitosamente.');
        }
    }

    /**
     * Cambia el estado de la asignación (Activo/Inactivo)
     * @param int $asignacionId - ID de la asignación a cambiar
     */
    public function toggleActivo($asignacionId)
    {
        // Buscar la asignación
        $asignacion = MateriaGrupo::find($asignacionId);
        
        // Cambiar el estado
        $asignacion->activo = !$asignacion->activo;
        $asignacion->save();
        
        // Mostrar mensaje de éxito
        session()->flash('message', 'Estado actualizado exitosamente.');
    }

    /**
     * Renderiza la vista con las asignaciones materia-grupo
     * Incluye búsqueda por materia, grupo y docente
     */
    public function render()
    {
        // Consulta las asignaciones con todas sus relaciones
        $asignaciones = MateriaGrupo::with(['materia', 'grupo', 'docente'])
            // Filtra por término de búsqueda
            ->where(function($query) {
                $query
                    // Busca en materia
                    ->whereHas('materia', function($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%')
                          ->orWhere('codigo', 'like', '%' . $this->search . '%');
                    })
                    // O busca en grupo
                    ->orWhereHas('grupo', function($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%')
                          ->orWhere('codigo', 'like', '%' . $this->search . '%');
                    })
                    // O busca en docente
                    ->orWhereHas('docente', function($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%');
                    })
                    // O busca por gestión
                    ->orWhere('gestion', 'like', '%' . $this->search . '%');
            })
            // Ordena por más recientes
            ->orderBy('id_mg', 'desc')
            // Pagina los resultados (10 por página)
            ->paginate(10);

        // Retorna la vista con las asignaciones
        return view('livewire.gestion-academica.asignar-materias-grupos.index', [
            'asignaciones' => $asignaciones
        ]);
    }
}