<?php

namespace App\Livewire\GestionAcademica\AsignarHorariosEvitandoConflictos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DetalleHorario; // Cambiamos de MateriaGrupo a DetalleHorario
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Propiedad para el término de búsqueda
    public $search = '';
    
    // Propiedades para controlar el modal de eliminación
    public $confirmingDeletion = false;
    public $detalleToDelete = null;

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
     * @param int $detalleId - ID del detalle a eliminar
     */
    public function confirmDelete($detalleId)
    {
        $this->detalleToDelete = $detalleId;
        $this->confirmingDeletion = true;
    }

    /**
     * Elimina el detalle de horario seleccionado
     * También muestra un mensaje de éxito
     */
    public function delete()
    {
        // Verificar que hay un detalle seleccionado
        if ($this->detalleToDelete) {
            // Buscar y eliminar el detalle
            DetalleHorario::find($this->detalleToDelete)->delete();
            
            // Cerrar el modal
            $this->confirmingDeletion = false;
            $this->detalleToDelete = null;
            
            // Mostrar mensaje de éxito
            session()->flash('message', 'Asignación eliminada exitosamente.');
        }
    }

    /**
     * Cambia el estado del detalle (Activo/Inactivo)
     * @param int $detalleId - ID del detalle a cambiar
     */
    public function toggleEstado($detalleId)
    {
        // Buscar el detalle
        $detalle = DetalleHorario::find($detalleId);
        
        // Cambiar el estado
        $detalle->estado = $detalle->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $detalle->save();
        
        // Mostrar mensaje de éxito
        session()->flash('message', 'Estado actualizado exitosamente.');
    }

    /**
     * Renderiza la vista con los detalles de horario
     * Incluye búsqueda por materia, grupo, docente, aula y día
     */
    public function render()
    {
        // Consulta los detalles con todas sus relaciones
        $detalles = DetalleHorario::with([
            'materiaGrupo.materia',  // Incluye la materia
            'materiaGrupo.grupo',    // Incluye el grupo
            'materiaGrupo.docente',  // Incluye el docente
            'horario',               // Incluye el bloque horario
            'aula'                   // Incluye el aula
        ])
        // Filtra por término de búsqueda
        ->where(function($query) {
            $query
                // Busca en materia
                ->whereHas('materiaGrupo.materia', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo', 'like', '%' . $this->search . '%');
                })
                // O busca en grupo
                ->orWhereHas('materiaGrupo.grupo', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo', 'like', '%' . $this->search . '%');
                })
                // O busca en docente
                ->orWhereHas('materiaGrupo.docente', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                })
                // O busca en aula
                ->orWhereHas('aula', function($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo', 'like', '%' . $this->search . '%');
                })
                // O busca por día de la semana
                ->orWhere('dia_semana', 'like', '%' . $this->search . '%');
        })
        // Ordena por día y hora
        ->orderBy('dia_semana')
        ->orderBy('id_horario')
        // Pagina los resultados (10 por página)
        ->paginate(10);

        // Retorna la vista con los detalles
        return view('livewire.gestion-academica.asignar-horarios-evitando-conflictos.index', [
            'detalles' => $detalles
        ]);
    }
}