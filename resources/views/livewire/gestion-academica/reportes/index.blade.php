<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Reportes de Horarios</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Genera y descarga reportes en PDF y Excel</p>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('message'))
        <div class="rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-4">
            <p class="text-sm text-green-800 dark:text-green-300">{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4">
            <p class="text-sm text-red-800 dark:text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Formulario de Reportes -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
        <div class="p-6">
            <form wire:submit="generarReporte" class="space-y-6">
                
                <!-- Tipo de Reporte -->
                <div>
                    <label for="tipo_reporte" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                        Tipo de Reporte <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="tipo_reporte"
                        wire:model.live="tipo_reporte"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                            {{ $errors->has('tipo_reporte') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                            bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="">Seleccionar tipo de reporte</option>
                        <option value="horario_grupo">📄 Horario por Grupo (PDF)</option>
                        <option value="horario_docente">📄 Horario por Docente (PDF)</option>
                        <option value="horario_aula">📄 Horario por Aula (PDF)</option>
                        <option value="asignaciones_excel">📊 Todas las Asignaciones (Excel)</option>
                    </select>
                    @error('tipo_reporte')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Filtros dinámicos según el tipo de reporte -->
                @if($tipo_reporte)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Grupo (solo para horario_grupo) -->
                        @if($tipo_reporte === 'horario_grupo')
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
                        @endif

                        <!-- Docente (solo para horario_docente) -->
                        @if($tipo_reporte === 'horario_docente')
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
                        @endif

                        <!-- Aula (solo para horario_aula) -->
                        @if($tipo_reporte === 'horario_aula')
                            <div>
                                <label for="id_aula" class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">
                                    Aula <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="id_aula"
                                    wire:model="id_aula"
                                    class="w-full rounded-lg border px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                        {{ $errors->has('id_aula') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-700' }}
                                        bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                    <option value="">Seleccionar aula</option>
                                    @foreach($aulas as $aula)
                                        <option value="{{ $aula->id_aula }}">{{ $aula->codigo }} - {{ $aula->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('id_aula')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Gestión (para todos) -->
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
                    </div>
                @endif

                <!-- Botón Generar -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button 
                        type="submit"
                        @disabled(!$tipo_reporte)
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-sm font-semibold transition
                            {{ !$tipo_reporte 
                                ? 'bg-gray-400 dark:bg-gray-600 text-gray-200 dark:text-gray-400 cursor-not-allowed' 
                                : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Generar y Descargar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/40">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-900 dark:text-blue-300">Horario por Grupo</p>
                    <p class="text-xs text-blue-700 dark:text-blue-400">PDF horizontal</p>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="rounded-lg border border-purple-200 dark:border-purple-800 bg-purple-50 dark:bg-purple-900/20 p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/40">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-purple-900 dark:text-purple-300">Horario por Docente</p>
                    <p class="text-xs text-purple-700 dark:text-purple-400">PDF horizontal</p>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/40">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-900 dark:text-green-300">Horario por Aula</p>
                    <p class="text-xs text-green-700 dark:text-green-400">PDF horizontal</p>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="rounded-lg border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/20 p-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/40">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-orange-900 dark:text-orange-300">Asignaciones</p>
                    <p class="text-xs text-orange-700 dark:text-orange-400">Excel completo</p>
                </div>
            </div>
        </div>
    </div>
</div>