<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Gestión de Usuarios</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Administra los usuarios del sistema</p>
        </div>
        <a href="{{ route('usuarios.create') }}" 
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
            + Nuevo Usuario
        </a>
    </div>

    <!-- Search -->
    <div class="max-w-md">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Buscar por nombre o correo..."
            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
    </div>

    <!-- Messages -->
    @if (session()->has('message'))
        <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-800 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full bg-white dark:bg-gray-900">
                <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Nombre</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Correo</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Rol</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Último Login</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Estado</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($usuarios as $usuario)
                        <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-semibold">
                                        {{ $usuario->initials() }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $usuario->nombre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $usuario->correo }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $usuario->rol?->nombre === 'Administrador' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300' : '' }}
                                    {{ $usuario->rol?->nombre === 'Docente' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300' : '' }}
                                    {{ $usuario->rol?->nombre === 'Coordinador Académico' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300' : '' }}">
                                    {{ $usuario->rol?->nombre ?? 'Sin rol' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $usuario->ultimo_login ? $usuario->ultimo_login->format('d/m/Y H:i') : 'Nunca' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button wire:click="toggleActivo({{ $usuario->id }})"
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold transition
                                        {{ $usuario->activo ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300' }}">
                                    {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" 
                                       class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">Editar</a>
                                    <button wire:click="confirmDelete({{ $usuario->id }})"
                                        class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No se encontraron usuarios</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3 bg-gray-50 dark:bg-gray-800">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex size-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                <svg class="size-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">¿Eliminar Usuario?</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Esta acción no se puede deshacer.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="$set('confirmingDeletion', false)"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Cancelar</button>
                    <button wire:click="delete"
                        class="px-4 py-2 rounded-lg text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition">Eliminar</button>
                </div>
            </div>
        </div>
    @endif
</div>