<?php

namespace App\Livewire\UsuariosRoles\Usuarios;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $usuarioToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($usuarioId)
    {
        $this->usuarioToDelete = $usuarioId;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->usuarioToDelete) {
            User::find($this->usuarioToDelete)->delete();
            $this->confirmingDeletion = false;
            $this->usuarioToDelete = null;
            session()->flash('message', 'Usuario eliminado exitosamente.');
        }
    }

    public function toggleActivo($usuarioId)
    {
        $usuario = User::find($usuarioId);
        $usuario->activo = !$usuario->activo;
        $usuario->save();
        session()->flash('message', 'Estado actualizado exitosamente.');
    }

    public function render()
    {
        $usuarios = User::with('rol')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('correo', 'like', '%' . $this->search . '%')
            ->orderBy('creado_en', 'desc')
            ->paginate(10);

        return view('livewire.gestion-academica.usuarios.index', [
            'usuarios' => $usuarios
        ]);
    }
}