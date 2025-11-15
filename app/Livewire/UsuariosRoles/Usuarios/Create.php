<?php

namespace App\Livewire\UsuariosRoles\Usuarios;

use Livewire\Component;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Create extends Component
{
    public $nombre = '';
    public $correo = '';
    public $password = '';
    public $password_confirmation = '';
    public $id_rol = '';
    public $activo = true;

    protected $rules = [
        'nombre' => 'required|string|max:150',
        'correo' => 'required|email|max:150|unique:usuarios,correo',
        'password' => 'required|string|min:8|confirmed',
        'id_rol' => 'required|exists:rol,id',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio',
        'correo.required' => 'El correo es obligatorio',
        'correo.email' => 'El correo debe ser válido',
        'correo.unique' => 'Este correo ya está registrado',
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'password.confirmed' => 'Las contraseñas no coinciden',
        'id_rol.required' => 'Debes seleccionar un rol',
        'id_rol.exists' => 'El rol seleccionado no es válido',
    ];

    public function save()
    {
        $this->validate();

        User::create([
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'contrasena' => Hash::make($this->password),
            'id_rol' => $this->id_rol,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Usuario creado exitosamente.');

        return redirect()->route('usuarios.index');
    }

    public function render()
    {
        $roles = Rol::orderBy('nombre')->get();

        return view('livewire.gestion-academica.usuarios.create', [
            'roles' => $roles
        ]);
    }
}