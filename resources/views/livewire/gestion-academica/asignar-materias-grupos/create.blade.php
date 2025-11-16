<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            {{-- Título principal --}}
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Nueva Asignación de Materia a Grupo</h1>
            {{-- Descripción --}}
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Define qué materia tendrá un grupo y quién la impartirá</p>
        </div>
        {{-- Botón Volver --}}
        <a href="{{ route('asignar-materias-grupos.index') }}" 
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
                
                <!-- Materia -->
                <div>
                    <label for="id_materia" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                        Materia <span class="text-red-500">*</span>
                    </label>
                    {{-- Select de materias --}}
                    <select 
                        id="id_materia"
                        wire:model="id_materia"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                            {{ $errors->has('id_materia') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                            bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="">Seleccionar materia</option>
                        {{-- Itera sobre las materias disponibles --}}
                        @foreach($materias as $materia)
                            <option value="{{ $materia->id_materia }}">{{ $materia->codigo }} - {{ $materia->nombre }}</option>
                        @endforeach
                    </select>
                    {{-- Muestra error de validación si existe --}}
                    @error('id_materia')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grupo y Docente (Grid de 2 columnas) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Grupo -->
                    <div>
                        <label for="id_grupo" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Grupo <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="id_grupo"
                            wire:model="id_grupo"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                {{ $errors->has('id_grupo') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">Seleccionar grupo</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id_grupo }}">{{ $grupo->codigo }} - {{ $grupo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_grupo')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Docente -->
                    <div>
                        <label for="id_docente" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Docente <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="id_docente"
                            wire:model="id_docente"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                {{ $errors->has('id_docente') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">Seleccionar docente</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}">{{ $docente->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_docente')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
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
                        placeholder="Ej: 2-2025"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                            {{ $errors->has('gestion') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                            bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400">
                    @error('gestion')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado Activo -->
                <div class="flex items-center gap-3">
                    {{-- Checkbox para marcar como activo --}}
                    <input 
                        type="checkbox" 
                        id="activo"
                        wire:model="activo"
                        class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <label for="activo" class="text-sm font-medium text-gray-900 dark:text-white">
                        Asignación activa
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    {{-- Botón Cancelar --}}
                    <a href="{{ route('asignar-materias-grupos.index') }}" 
                       class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Cancelar
                    </a>
                    {{-- Botón Guardar --}}
                    <button 
                        type="submit"
                        class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition">
                        Guardar Asignación
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                    Esta asignación define que una materia pertenece a un grupo y será impartida por un docente específico. 
                    No se permite duplicar la misma materia en el mismo grupo para la misma gestión.
                    Después podrás asignar los horarios específicos (días y horas) en "Asignar Horarios Evitando Conflictos".
                </p>
            </div>
        </div>
    </div>
</div>