<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Editar Aula</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Modifica los datos del aula</p>
        </div>
        <a href="{{ route('aulas.index') }}" 
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Volver
        </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
        <div class="p-6">
            <form wire:submit="update" class="space-y-6">
                
                <!-- Código -->
                <div>
                    <label for="codigo" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                        Código <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="codigo"
                        wire:model="codigo"
                        placeholder="Ej: LAB-101"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                            {{ $errors->has('codigo') 
                                ? 'border-red-500 dark:border-red-500' 
                                : 'border-gray-300 dark:border-gray-700' }}
                            bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400">
                    @error('codigo')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                        Nombre del Aula <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nombre"
                        wire:model="nombre"
                        placeholder="Ej: Laboratorio de Computación 1"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                            {{ $errors->has('nombre') 
                                ? 'border-red-500 dark:border-red-500' 
                                : 'border-gray-300 dark:border-gray-700' }}
                            bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400">
                    @error('nombre')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacidad y Ubicación (Grid) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Capacidad -->
                    <div>
                        <label for="capacidad" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Capacidad (personas) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="capacidad"
                            wire:model="capacidad"
                            placeholder="30"
                            min="1"
                            max="500"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                {{ $errors->has('capacidad') 
                                    ? 'border-red-500 dark:border-red-500' 
                                    : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400">
                        @error('capacidad')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <label for="ubicacion" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Ubicación
                        </label>
                        <input 
                            type="text" 
                            id="ubicacion"
                            wire:model="ubicacion"
                            placeholder="Ej: Edificio A - Planta Baja"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400">
                    </div>
                </div>

                <!-- Estado Activo -->
                <div class="flex items-center gap-3">
                    <input 
                        type="checkbox" 
                        id="activo"
                        wire:model="activo"
                        class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <label for="activo" class="text-sm font-medium text-gray-900 dark:text-white">
                        Aula activa
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('aulas.index') }}" 
                       class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition">
                        Actualizar Aula
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="rounded-lg border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20 p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 flex-shrink-0 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <div>
                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Advertencia</p>
                <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-400">
                    Al modificar el código o capacidad de un aula, verifica que no afecte a horarios ya asignados.
                </p>
            </div>
        </div>
    </div>
</div>