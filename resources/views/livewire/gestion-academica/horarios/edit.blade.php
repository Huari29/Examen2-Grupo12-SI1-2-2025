<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Editar Horario</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Modifica el bloque de tiempo</p>
        </div>
        <a href="{{ route('horarios.index') }}" 
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
                
                <!-- Info del horario actual -->
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/40 px-3 py-1">
                            <span class="text-xs font-semibold text-blue-800 dark:text-blue-300">#{{ $horario->id }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Horario actual</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Hora Inicio y Hora Fin (Grid) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Hora Inicio -->
                    <div>
                        <label for="hora_inicio" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Hora de Inicio <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="time" 
                            id="hora_inicio"
                            wire:model="hora_inicio"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                {{ $errors->has('hora_inicio') 
                                    ? 'border-red-500 dark:border-red-500' 
                                    : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        @error('hora_inicio')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora Fin -->
                    <div>
                        <label for="hora_fin" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                            Hora de Fin <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="time" 
                            id="hora_fin"
                            wire:model="hora_fin"
                            class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                {{ $errors->has('hora_fin') 
                                    ? 'border-red-500 dark:border-red-500' 
                                    : 'border-gray-300 dark:border-gray-700' }}
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        @error('hora_fin')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview de duración -->
                @if($hora_inicio && $hora_fin)
                    <div class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Duración del bloque</p>
                                <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                    @php
                                        try {
                                            $inicio = \Carbon\Carbon::createFromFormat('H:i', $hora_inicio);
                                            $fin = \Carbon\Carbon::createFromFormat('H:i', $hora_fin);
                                            $duracion = $inicio->diffInMinutes($fin);
                                            $horas = floor($duracion / 60);
                                            $minutos = $duracion % 60;
                                            echo "{$horas} hora(s)";
                                            if ($minutos > 0) {
                                                echo " y {$minutos} minuto(s)";
                                            }
                                        } catch (\Exception $e) {
                                            echo "Formato de hora inválido";
                                        }
                                    @endphp
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('horarios.index') }}" 
                       class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Cancelar
                    </a>
                    <button 
                        type="submit"
                        class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition">
                        Actualizar Horario
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
                    Modificar este horario puede afectar las asignaciones existentes que lo usan.
                </p>
            </div>
        </div>
    </div>
</div>