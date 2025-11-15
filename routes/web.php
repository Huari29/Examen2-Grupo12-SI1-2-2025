<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\GestionAcademica\Materias;
use App\Livewire\GestionAcademica\Grupos;
use App\Livewire\GestionAcademica\Aulas;
use App\Livewire\UsuariosRoles\Usuarios;
use App\Livewire\Docentes;
use App\Livewire\GestionAcademica\Horarios;
use App\Livewire\Asistencia;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ==================== RUTAS PROTEGIDAS CON AUTENTICACIÓN ====================
Route::middleware(['auth'])->group(function () {
    
    // Settings
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // ==================== SOLO ADMINISTRADOR ====================
    Route::middleware(['role:Administrador'])->group(function () {
        // Usuarios
        Route::get('/usuarios', Usuarios\Index::class)->name('usuarios.index');
        Route::get('/usuarios/create', Usuarios\Create::class)->name('usuarios.create');
        Route::get('/usuarios/{usuario}/edit', Usuarios\Edit::class)->name('usuarios.edit');
    });

    // ==================== ADMINISTRADOR Y COORDINADOR ====================
    Route::middleware(['role:Administrador,Coordinador Académico'])->group(function () {
        
        // Materias
        Route::get('/materias', Materias\Index::class)->name('materias.index');
        Route::get('/materias/crear', Materias\Create::class)->name('materias.create');
        Route::get('/materias/{materia}/editar', Materias\Edit::class)->name('materias.edit');
        
        // Grupos
        Route::get('/grupos', Grupos\Index::class)->name('grupos.index');
        Route::get('/grupos/crear', Grupos\Create::class)->name('grupos.create');
        Route::get('/grupos/{grupo}/editar', Grupos\Edit::class)->name('grupos.edit');
        
        // Aulas
        Route::get('/aulas', Aulas\Index::class)->name('aulas.index');
        Route::get('/aulas/crear', Aulas\Create::class)->name('aulas.create');
        Route::get('/aulas/{aula}/editar', Aulas\Edit::class)->name('aulas.edit');
        
        // Horarios
        Route::get('/horarios', Horarios\Index::class)->name('horarios.index');
        Route::get('/horarios/crear', Horarios\Create::class)->name('horarios.create');
    });

    // ==================== TODOS LOS USUARIOS AUTENTICADOS ====================
    
    // Asistencia (accesible para todos)
    Route::get('/asistencia', Asistencia\Index::class)->name('asistencia.index');
    Route::get('/asistencia/registro', Asistencia\Registro::class)->name('asistencia.registro');
});