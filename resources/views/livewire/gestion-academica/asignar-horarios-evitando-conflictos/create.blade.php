<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            {{-- Título principal --}}
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Asignar Horario</h1>
            {{-- Descripción --}}
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Asigna horarios (día, hora y aula) a una materia-grupo</p>
        </div>
        {{-- Botón Volver --}}
        <a href="{{ route('asignar-horarios.index') }}" 
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Volver
        </a>
    </div>

    <!-- Alerta de Conflictos -->
    {{-- Muestra advertencias si hay conflictos de horario --}}
    @if(filled($conflictos))
        <div class="rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4">
            <div class="flex gap-3">
                {{-- Icono de alerta --}}
                <svg class="w-5 h-5 flex-shrink-0 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <div class="flex-1">
                    {{-- Título de la alerta --}}
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">Conflictos detectados</p>
                    {{-- Lista de conflictos --}}
                    <ul class="mt-2 text-sm text-red-700 dark:text-red-400 list-disc list-inside space-y-1">
                        @foreach($conflictos as $conflicto)
                            <li>{{ $conflicto }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
        <div class="p-6">
            <form wire:submit="save" class="space-y-8">
                
                <!-- PASO 1: Seleccionar Asignación Materia-Grupo -->
                <div class="space-y-4">
                    {{-- Encabezado del paso 1 --}}
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-semibold text-sm">
                            1
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Seleccionar Asignación</h3>
                    </div>

                    <!-- Asignación Materia-Grupo -->
                    <div>
                        <label for="id_mg" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Asignación Materia-Grupo-Docente <span class="text-red-500">*</span>
                        </label>
                        {{-- Select de asignaciones con actualización automática --}}
                        <select 
                            id="id_mg"
                            wire:model.live="id_mg"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                {{ $errors->has('id_mg') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">Seleccionar asignación</option>
                            {{-- Itera sobre las asignaciones disponibles --}}
                            @foreach($asignaciones as $asignacion)
                                <option value="{{ $asignacion->id_mg }}">
                                    {{ $asignacion->materia->codigo }} - {{ $asignacion->materia->nombre }} | 
                                    Grupo: {{ $asignacion->grupo->codigo }} | 
                                    Docente: {{ $asignacion->docente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        {{-- Muestra error de validación si existe --}}
                        @error('id_mg')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Información de la asignación seleccionada -->
                    @if($asignacionSeleccionada)
                        <div class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-4">
                            <div class="flex items-start gap-3">
                                {{-- Icono de información --}}
                                <svg class="w-5 h-5 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Asignación seleccionada</p>
                                    <div class="mt-2 grid grid-cols-2 gap-3 text-xs text-blue-700 dark:text-blue-400">
                                        {{-- Muestra detalles de la asignación --}}
                                        <div>
                                            <span class="font-medium">Materia:</span> 
                                            {{ $asignacionSeleccionada->materia->codigo }} - {{ $asignacionSeleccionada->materia->nombre }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Grupo:</span> 
                                            {{ $asignacionSeleccionada->grupo->codigo }} - {{ $asignacionSeleccionada->grupo->nombre }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Docente:</span> 
                                            {{ $asignacionSeleccionada->docente->nombre }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Gestión:</span> 
                                            {{ $asignacionSeleccionada->gestion }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- PASO 2: Detalles de Horario (Día - Horario - Aula) -->
                <div class="space-y-4">
                    {{-- Encabezado del paso 2 --}}
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 font-semibold text-sm">
                            2
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detalles de Horario</h3>
                    </div>

                    <!-- Día de la Semana y Horario (Grid) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Día de la Semana -->
                        <div>
                            <label for="dia_semana" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                                Día de la Semana <span class="text-red-500">*</span>
                            </label>
                            {{-- Select de días con validación de conflictos en tiempo real --}}
                            <select 
                                id="dia_semana"
                                wire:model.live="dia_semana"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                    {{ $errors->has('dia_semana') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                    bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="">Seleccionar día</option>
                                {{-- Itera sobre los días de la semana --}}
                                @foreach($dias as $dia)
                                    <option value="{{ $dia }}">{{ $dia }}</option>
                                @endforeach
                            </select>
                            @error('dia_semana')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Horario -->
                        <div>
                            <label for="id_horario" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                                Horario <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="id_horario"
                                wire:model.live="id_horario"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                    {{ $errors->has('id_horario') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                    bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="">Seleccionar horario</option>
                                {{-- Itera sobre los bloques horarios disponibles --}}
                                @foreach($horarios as $horario)
                                    <option value="{{ $horario->id }}">
                                        {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_horario')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Aula y Gestión (Grid) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Aula -->
                        <div>
                            <label for="id_aula" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                                Aula <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="id_aula"
                                wire:model.live="id_aula"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                    {{ $errors->has('id_aula') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                    bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="">Seleccionar aula</option>
                                {{-- Itera sobre las aulas disponibles --}}
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id_aula }}">{{ $aula->codigo }} - {{ $aula->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_aula')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gestión (Auto-completada, solo lectura) -->
                        <div>
                            <label for="gestion" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                                Gestión <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="gestion"
                                wire:model="gestion"
                                readonly
                                class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 cursor-not-allowed">
                            @error('gestion')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                La gestión se completa automáticamente según la asignación seleccionada
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    {{-- Botón Cancelar --}}
                    <a href="{{ route('asignar-horarios.index') }}" 
                       class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Cancelar
                    </a>
                    {{-- Botón Guardar (deshabilitado si hay conflictos) --}}
                    <button 
                        type="submit"
                        @disabled(filled($conflictos))
                        class="px-6 py-2.5 rounded-lg text-sm font-semibold transition
                            {{ filled($conflictos)
                                ? 'bg-gray-400 dark:bg-gray-600 text-gray-200 dark:text-gray-400 cursor-not-allowed' 
                                : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        Asignar Horario
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
                    El sistema valida automáticamente que no haya conflictos de horario. No podrás guardar si:
                </p>
                {{-- Lista de validaciones --}}
                <ul class="mt-2 text-sm text-blue-700 dark:text-blue-400 list-disc list-inside space-y-1">
                    <li>El aula ya está ocupada en ese horario y día</li>
                    <li>El docente ya tiene clase en ese horario y día</li>
                    <li>El grupo ya tiene clase asignada en ese horario y día</li>
                </ul>
            </div>
        </div>
    </div>
</div>