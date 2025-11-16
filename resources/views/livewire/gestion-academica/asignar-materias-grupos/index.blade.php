<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            {{-- Título principal --}}
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Asignar Materias a Grupos</h1>
            {{-- Descripción --}}
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gestiona qué materias tiene cada grupo y quién las imparte</p>
        </div>
        {{-- Botón para crear nueva asignación --}}
        <a href="{{ route('asignar-materias-grupos.create') }}" 
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
            + Nueva Asignación
        </a>
    </div>

    <!-- Search -->
    <div class="max-w-md">
        {{-- Input de búsqueda con actualización en tiempo real (wire:model.live) --}}
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Buscar por materia, grupo, docente o gestión..."
            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
    </div>

    <!-- Messages -->
    {{-- Muestra mensaje de éxito si existe en la sesión --}}
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
                        {{-- Encabezados de la tabla --}}
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Materia</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Grupo</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Docente</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Gestión</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Horarios</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Estado</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    {{-- Itera sobre cada asignación --}}
                    @forelse ($asignaciones as $asignacion)
                        <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            {{-- Columna: Materia --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    {{-- Código de la materia en negrita --}}
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $asignacion->materia->codigo }}
                                    </span>
                                    {{-- Nombre de la materia en gris --}}
                                    <span class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $asignacion->materia->nombre }}
                                    </span>
                                </div>
                            </td>
                            
                            {{-- Columna: Grupo --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $asignacion->grupo->codigo }}
                                    </span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $asignacion->grupo->nombre }}
                                    </span>
                                </div>
                            </td>
                            
                            {{-- Columna: Docente --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- Avatar con iniciales del docente --}}
                                    <div class="flex size-8 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 text-xs font-semibold">
                                        {{ $asignacion->docente->initials() }}
                                    </div>
                                    {{-- Nombre del docente --}}
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $asignacion->docente->nombre }}
                                    </span>
                                </div>
                            </td>
                            
                            {{-- Columna: Gestión --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $asignacion->gestion }}</span>
                            </td>
                            
                            {{-- Columna: Cantidad de horarios asignados --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold 
                                    {{ $asignacion->detallesHorario->count() > 0 
                                        ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300' 
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                                    {{ $asignacion->detallesHorario->count() }} 
                                    {{ $asignacion->detallesHorario->count() == 1 ? 'horario' : 'horarios' }}
                                </span>
                            </td>
                            
                            {{-- Columna: Estado (Activo/Inactivo) con botón toggle --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <button wire:click="toggleActivo({{ $asignacion->id_mg }})"
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold transition
                                        {{ $asignacion->activo 
                                            ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300' 
                                            : 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300' }}">
                                    {{ $asignacion->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            
                            {{-- Columna: Acciones (Editar/Eliminar) --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    {{-- Botón Editar --}}
                                    <a href="{{ route('asignar-materias-grupos.edit', $asignacion->id_mg) }}" 
                                       class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                                        Editar
                                    </a>
                                    {{-- Botón Eliminar --}}
                                    <button wire:click="confirmDelete({{ $asignacion->id_mg }})"
                                        class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando no hay resultados --}}
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron asignaciones de materias a grupos
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación (solo se muestra si hay múltiples páginas) --}}
        @if($asignaciones->hasPages())
            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3 bg-gray-50 dark:bg-gray-800">
                {{ $asignaciones->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    {{-- Modal de confirmación de eliminación (solo se muestra si $confirmingDeletion es true) --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        {{-- Icono de advertencia --}}
                        <div class="flex-shrink-0">
                            <div class="flex size-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                <svg class="size-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                        </div>
                        {{-- Mensaje de confirmación --}}
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">¿Eliminar Asignación?</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Esta acción no se puede deshacer. Se eliminará permanentemente esta asignación y todos los horarios asociados.
                            </p>
                        </div>
                    </div>
                </div>
                {{-- Botones del modal --}}
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
                    {{-- Botón Cancelar (cierra el modal) --}}
                    <button wire:click="$set('confirmingDeletion', false)"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Cancelar
                    </button>
                    {{-- Botón Eliminar (ejecuta la eliminación) --}}
                    <button wire:click="delete"
                        class="px-4 py-2 rounded-lg text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Info Card -->
    <div class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-4">
        <div class="flex gap-3">
            {{-- Icono de información --}}
            <svg class="w-5 h-5 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <div>
                <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Información</p>
                <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                    Aquí defines qué materias tiene cada grupo y qué docente las imparte. Después podrás asignar los horarios específicos en "Asignar Horarios Evitando Conflictos".
                </p>
            </div>
        </div>
    </div>
</div>