<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Nuevo Grupo</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Registra un nuevo grupo en el sistema</p>
        </div>
        <a href="{{ route('grupos.index') }}" 
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
            <form wire:submit="save" class="space-y-6">
                
                <!-- Código -->
                <div>
                    <label for="codigo" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                        Código <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="codigo"
                        wire:model="codigo"
                        placeholder="Ej: SIS-1A"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                            {{ $errors->has('codigo') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                            bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400">
                    @error('codigo')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                        Nombre del Grupo <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nombre"
                        wire:model="nombre"
                        placeholder="Ej: Grupo A"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                            {{ $errors->has('nombre') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                            bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400">
                    @error('nombre')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Materia y Gestión (Grid) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Materia -->
                    <div>
                        <label for="id_materia" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Materia <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="id_materia"
                            wire:model="id_materia"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                {{ $errors->has('id_materia') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">Seleccionar materia</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id_materia }}">{{ $materia->codigo }} - {{ $materia->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_materia')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gestión -->
                    <div>
                        <label for="gestion" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Gestión <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="gestion"
                            wire:model="gestion"
                            placeholder="2-2025"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                {{ $errors->has('gestion') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400">
                        @error('gestion')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                        Grupo activo
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('grupos.index') }}" 
                       class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition">
                        Guardar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <div>
                <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Información</p>
                <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                    El código debe ser único. Asegúrate de seleccionar la materia correcta para el grupo.
                </p>
            </div>
        </div>
    </div>
</div>