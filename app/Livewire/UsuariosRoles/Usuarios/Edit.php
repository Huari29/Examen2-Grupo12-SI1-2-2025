<?php

namespace App\Livewire\UsuariosRoles\Usuarios;

use Livewire\Component;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public User $usuario;
    
    public $nombre = '';
    public $correo = '';
    public $password = '';
    public $password_confirmation = '';
    public $id_rol = '';
    public $activo = true;

    public function mount(User $usuario)
    {
        $this->usuario = $usuario;
        $this->nombre = $usuario->nombre;
        $this->correo = $usuario->correo;
        $this->id_rol = $usuario->id_rol;
        $this->activo = $usuario->activo;
    }

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|max:150|unique:usuarios,correo,' . $this->usuario->id . ',id',
            'password' => 'nullable|string|min:8|confirmed',
            'id_rol' => 'required|exists:rol,id',
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio',
        'correo.required' => 'El correo es obligatorio',
        'correo.email' => 'El correo debe ser válido',
        'correo.unique' => 'Este correo ya está registrado',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'password.confirmed' => 'Las contraseñas no coinciden',
        'id_rol.required' => 'Debes seleccionar un rol',
        'id_rol.exists' => 'El rol seleccionado no es válido',
    ];

    public function update()
    {
        $this->validate();

        $data = [
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'id_rol' => $this->id_rol,
            'activo' => $this->activo,
        ];

        // Solo actualizar contraseña si se proporcionó una nueva
        if (!empty($this->password)) {
            $data['contrasena'] = Hash::make($this->password);
        }

        $this->usuario->update($data);

        session()->flash('message', 'Usuario actualizado exitosamente.');

        return redirect()->route('usuarios.index');
    }

    public function render()
    {
        $roles = Rol::orderBy('nombre')->get();

        return view('livewire.gestion-academica.usuarios.edit', [
            'roles' => $roles
        ]);
    }
}