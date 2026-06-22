<div>
    {{-- ====== MENSAJES FLASH ====== --}}
    @if (session('mensaje'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)">
            {{ session('mensaje') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            {{ session('error') }}
        </div>
    @endif

    {{-- ======================================================= --}}
    {{-- ====================  VISTA: LISTA  ===================== --}}
    {{-- ======================================================= --}}
    @if ($vista === 'lista')

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Lista de Pacientes</h1>
                <p class="text-xs text-gray-400">/ lista-de-pacientes /</p>
            </div>
            <button wire:click="abrirModalCrear"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-2 w-fit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Agregar Paciente
            </button>
        </div>

        {{-- BUSCADOR --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
            <h2 class="text-center font-semibold text-gray-700 mb-4">Buscar Paciente:</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" wire:model.live.debounce.400ms="buscarNombres" placeholder="Nombres"
                       class="border border-orange-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                <input type="text" wire:model.live.debounce.400ms="buscarApellidoPaterno" placeholder="Apellido Paterno"
                       class="border border-orange-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                <input type="text" wire:model.live.debounce.400ms="buscarApellidoMaterno" placeholder="Apellido Materno"
                       class="border border-orange-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="bg-white rounded-xl shadow-sm px-6 py-3 mb-6 flex flex-wrap items-center justify-around gap-3 text-sm text-gray-500">
            <select wire:model.live="orden" class="bg-transparent focus:outline-none cursor-pointer">
                <option value="reciente">El más reciente ⇅</option>
                <option value="antiguo">El más antiguo ⇅</option>
                <option value="az">Nombre A–Z</option>
                <option value="za">Nombre Z–A</option>
            </select>
            <select wire:model.live="filtroTratamiento" class="bg-transparent focus:outline-none cursor-pointer">
                <option value="">Todos los tratamientos 🦷</option>
                @foreach ($tratamientos as $t)
                    <option value="{{ $t->id_tratamiento }}">{{ $t->nombre }}</option>
                @endforeach
            </select>
            <select wire:model.live="filtroFolder" class="bg-transparent focus:outline-none cursor-pointer">
                <option value="">Todos los folders 🗄️</option>
                @foreach ($folders as $f)
                    <option value="{{ $f->id_folder }}">{{ $f->codigo_archivo }}</option>
                @endforeach
            </select>
            <select wire:model.live="filtroSexo" class="bg-transparent focus:outline-none cursor-pointer">
                <option value="">Todos los géneros ⚥</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                <option value="O">Otro</option>
            </select>
        </div>

        {{-- LISTADO --}}
        <div class="space-y-4">
            @forelse ($listaPacientes as $p)
                <div class="bg-white rounded-xl shadow-sm p-4 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4 min-w-[220px]">
                        <div class="h-14 w-14 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0"
                             style="background-color: {{ $p->color_avatar }}">
                            {{ $p->iniciales }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">{{ $p->nombres }}</p>
                            <p class="text-xs text-gray-400">{{ $p->apellidos }}</p>
                        </div>
                    </div>
                    <div class="text-xs">
                        <p class="text-sky-500 font-medium">Folder: {{ $p->folder?->codigo_archivo ?? 'Sin asignar' }}</p>
                        <p class="text-red-400">Nro. Paciente: <span class="font-bold text-red-500">#{{ $p->id_paciente }}</span></p>
                    </div>
                    <div class="text-xs text-center min-w-[160px]">
                        <p class="text-gray-400">Último tratamiento:</p>
                        <p class="font-semibold text-gray-700">{{ $p->ultimoTratamiento() ?? 'Sin registros' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="verPaciente({{ $p->id_paciente }})"
                                class="bg-orange-400 hover:bg-orange-500 text-white text-xs font-semibold px-4 py-2 rounded-lg flex items-center gap-1">
                            🦷 Revisar
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-10 text-center text-gray-400 text-sm">
                    No se encontraron pacientes con los filtros seleccionados.
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $listaPacientes->links() }}
        </div>

        {{-- MODAL: REGISTRO DE PACIENTE --}}
        @if ($modalAbierto)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="cerrarModal">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-700 text-lg">Registrar Nuevo Paciente</h3>
                        <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>
                    <form wire:submit.prevent="guardarPaciente" class="px-6 py-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">CI / DNI *</label>
                                <input type="text" wire:model="ci_dni" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                @error('ci_dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Fecha de nacimiento *</label>
                                <input type="date" wire:model="fecha_nacimiento" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                @error('fecha_nacimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nombres *</label>
                                <input type="text" wire:model="nombres" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                @error('nombres') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Apellido Paterno *</label>
                                <input type="text" wire:model="apellido_paterno" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                @error('apellido_paterno') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Apellido Materno</label>
                                <input type="text" wire:model="apellido_materno" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Sexo *</label>
                                <select wire:model="sexo" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                    <option value="">Seleccionar...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="O">Otro</option>
                                </select>
                                @error('sexo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Teléfono</label>
                                <input type="text" wire:model="telefono" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Folder</label>
                                <select wire:model="id_folder" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                    <option value="">Sin asignar</option>
                                    @foreach ($folders as $f)
                                        <option value="{{ $f->id_folder }}">{{ $f->codigo_archivo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dirección</label>
                            <input type="text" wire:model="direccion" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Antecedentes médicos</label>
                            <textarea wire:model="antecedentes_medicos" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none"></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-2 border-t">
                            <button type="button" wire:click="cerrarModal" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700">Cancelar</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg">Registrar paciente</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    @endif


    {{-- ======================================================= --}}
    {{-- ===========  VISTA: FICHA / HISTORIA CLÍNICA  =========== --}}
    {{-- ======================================================= --}}
    @if ($vista === 'ficha' && $paciente)

        {{-- ENCABEZADO --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Historia Clínica Odontológica</h1>
                <p class="text-xs text-gray-400">/ paciente / {{ \Illuminate\Support\Str::slug($paciente->nombres . '-' . $paciente->apellido_paterno) }}-{{ $paciente->id_paciente }}</p>
            </div>
            <button wire:click="volverALista"
                    class="bg-slate-600 hover:bg-slate-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-2">
                Atrás
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1m1-9l-5 5m0 0l5 5m-5-5h12"/>
                </svg>
            </button>
        </div>

        {{-- TARJETA DE IDENTIFICACIÓN --}}
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between flex-wrap gap-4 mb-4">
            <div>
                <p class="text-xs text-gray-400">Paciente:</p>
                <p class="font-bold text-gray-800 text-lg leading-tight">{{ $paciente->nombres }}</p>
                <p class="text-sm text-gray-500">{{ $paciente->apellidos }}</p>
            </div>
            <div class="h-16 w-16 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0"
                 style="background-color: {{ $paciente->color_avatar }}">
                {{ $paciente->iniciales }}
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg px-5 py-2 text-right">
                <p class="text-sm text-gray-500">Folder: <span class="font-semibold text-gray-700">{{ $paciente->folder?->codigo_archivo ?? 'Sin asignar' }}</span></p>
                <p class="text-orange-500 font-bold">Nro. Paciente: #{{ $paciente->id_paciente }}</p>
            </div>
        </div>

        {{-- ÚLTIMO TRATAMIENTO + ODONTOGRAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                <p class="text-xs text-gray-400 mb-1">Último tratamiento registrado:</p>
                <p class="font-bold text-gray-800 text-lg">{{ $paciente->ultimoTratamiento() ?? 'Sin registros' }}</p>
            </div>
            <button wire:click="irAOdontograma"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-sm p-5 flex flex-col items-center justify-center gap-1 font-semibold transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Odontograma
            </button>
        </div>

        {{-- DATOS BÁSICOS / ANTECEDENTES MÉDICOS --}}
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-xs text-gray-400 mb-1">CI/DNI: <span class="text-gray-600">{{ $paciente->ci_dni }}</span> · Tel: <span class="text-gray-600">{{ $paciente->telefono ?? '—' }}</span></p>
                <p class="text-xs text-gray-400 mb-1">Dirección: <span class="text-gray-600">{{ $paciente->direccion ?? '—' }}</span></p>
                <p class="text-xs text-gray-400 mb-1">Antecedentes médicos:</p>
                <p class="text-gray-700 text-sm">{{ $paciente->antecedentes_medicos ?: 'Sin antecedentes registrados.' }}</p>
            </div>
            <button wire:click="abrirModalDatosBasicos"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg flex items-center gap-1 flex-shrink-0">
                Actualizar
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
        </div>

        {{-- ELIMINAR --}}
        <div class="flex justify-end">
            <button wire:click="abrirModalEliminar"
                    class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar Paciente
            </button>
        </div>

        {{-- MODAL: EDITAR DATOS BÁSICOS --}}
        @if ($modalDatosBasicos)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="$set('modalDatosBasicos', false)">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                    <div class="bg-orange-500 text-white text-center font-bold py-3">Actualizar Datos del Paciente</div>
                    <form wire:submit.prevent="guardarDatosBasicos" class="px-6 py-5 space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Nombres:</label>
                            <input type="text" wire:model="f_nombres" class="w-full border border-blue-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 focus:outline-none">
                            @error('f_nombres') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Apellido Paterno:</label>
                            <input type="text" wire:model="f_apellido_paterno" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                            @error('f_apellido_paterno') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Apellido Materno:</label>
                            <input type="text" wire:model="f_apellido_materno" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Sexo:</label>
                                <select wire:model="f_sexo" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                    <option value="M">Varón</option>
                                    <option value="F">Mujer</option>
                                    <option value="O">Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Teléfono:</label>
                                <input type="text" wire:model="f_telefono" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Dirección:</label>
                            <input type="text" wire:model="f_direccion" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Folder:</label>
                            <select wire:model="f_id_folder" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                <option value="">Sin asignar</option>
                                @foreach ($folders as $f)
                                    <option value="{{ $f->id_folder }}">{{ $f->codigo_archivo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Antecedentes médicos:</label>
                            <textarea wire:model="f_antecedentes_medicos" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none"></textarea>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <button type="button" wire:click="$set('modalDatosBasicos', false)" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-lg flex items-center gap-1">Actualizar ✎</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- MODAL: CONFIRMAR ELIMINACIÓN --}}
        @if ($modalEliminar)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-8 text-center">
                    <div class="mx-auto h-14 w-14 rounded-full border-2 border-gray-700 flex items-center justify-center text-2xl font-bold text-gray-700 mb-4">!</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Cuidado</h3>
                    <p class="text-sm text-gray-500 mb-6">¿Está seguro de eliminar a este paciente?</p>
                    <div class="flex justify-center gap-3">
                        <button wire:click="confirmarEliminarPaciente" class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg">Sí Eliminar</button>
                        <button wire:click="$set('modalEliminar', false)" class="bg-slate-700 hover:bg-slate-800 text-white text-sm font-semibold px-6 py-2.5 rounded-lg">Cancelar</button>
                    </div>
                </div>
            </div>
        @endif

    @endif


    {{-- ======================================================= --}}
    {{-- ================  VISTA: ODONTOGRAMA  ================== --}}
    {{-- ======================================================= --}}
    @if ($vista === 'odontograma' && $paciente)

        @php
            $arcadaSuperior   = [18,17,16,15,14,13,12,11,  21,22,23,24,25,26,27,28];
            $arcadaInferior   = [48,47,46,45,44,43,42,41,  31,32,33,34,35,36,37,38];
            $deciduosSuperior = [55,54,53,52,51,  61,62,63,64,65];
            $deciduosInferior = [85,84,83,82,81,  71,72,73,74,75];
        @endphp

        {{-- ENCABEZADO --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Odontograma del Paciente</h1>
                <p class="text-xs text-gray-400">/ odontograma / {{ \Illuminate\Support\Str::slug($paciente->nombres . '-' . $paciente->apellido_paterno) }}-{{ $paciente->id_paciente }}</p>
            </div>
            <button wire:click="volverAFicha"
                    class="bg-slate-600 hover:bg-slate-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-2">
                Atrás
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1m1-9l-5 5m0 0l5 5m-5-5h12"/>
                </svg>
            </button>
        </div>

        {{-- TARJETA PACIENTE --}}
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center justify-between flex-wrap gap-4 mb-4">
            <div class="flex items-center gap-4">
                <div class="text-xs text-gray-500">
                    <p>Folder: <span class="font-semibold text-sky-600">{{ $paciente->folder?->codigo_archivo ?? 'Sin asignar' }}</span></p>
                    <p>Paciente: <span class="font-semibold text-orange-500">#{{ $paciente->id_paciente }}</span></p>
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $paciente->nombres }}</p>
                    <p class="text-sm text-gray-500">{{ $paciente->apellidos }}</p>
                </div>
            </div>
            <div class="h-14 w-14 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0"
                 style="background-color: {{ $paciente->color_avatar }}">
                {{ $paciente->iniciales }}
            </div>
        </div>

        {{-- LEYENDA --}}
        <div class="bg-white rounded-xl shadow-sm p-3 mb-4 flex flex-wrap gap-4 justify-center text-xs text-gray-500">
            @foreach ($coloresEstado as $estado => $color)
                <span class="flex items-center gap-1">
                    <span class="inline-block w-3 h-3 rounded-full" style="background:{{ $color }}"></span>
                    {{ $estado }}
                </span>
            @endforeach
            <span class="flex items-center gap-1">
                <span class="inline-block w-3 h-3 rounded-full border border-gray-300" style="background:#d1d5db"></span>
                Sin hallazgos
            </span>
        </div>

        @if (! $odontogramaSeleccionadoId)
            <div class="bg-white rounded-xl shadow-sm p-8 text-center text-gray-400 text-sm mb-4">
                Este paciente no tiene odontogramas registrados todavía. Registra uno desde la lista de abajo para empezar a marcar piezas dentales.
            </div>
        @else
            {{-- DIAGRAMA ODONTOGRAMA (botones numerados por pieza, coloreados según el hallazgo más reciente) --}}
            <div class="bg-white rounded-xl shadow-sm p-4 mb-4 overflow-x-auto">

                {{-- Deciduos superiores --}}
                <div class="flex justify-center gap-1 mb-1 flex-wrap">
                    @foreach ($deciduosSuperior as $num)
                        @php $numStr = (string) $num; $color = $estadoDientes[$numStr]['color'] ?? '#d1d5db'; @endphp
                        <button wire:click="marcarDiente('{{ $numStr }}')" title="Diente {{ $num }}"
                                class="w-8 h-8 rounded-md text-[10px] font-semibold text-white flex items-center justify-center hover:opacity-75 transition-opacity"
                                style="background-color: {{ $color }}">{{ $num }}</button>
                    @endforeach
                </div>
                <div class="border-t border-dashed border-gray-200 mx-8 mb-2"></div>

                {{-- Permanentes superiores --}}
                <div class="flex justify-center gap-1 mb-2 flex-wrap">
                    @foreach ($arcadaSuperior as $num)
                        @php $numStr = (string) $num; $color = $estadoDientes[$numStr]['color'] ?? '#d1d5db'; @endphp
                        <button wire:click="marcarDiente('{{ $numStr }}')" title="Diente {{ $num }}"
                                class="w-9 h-10 rounded-md text-xs font-semibold text-white flex items-center justify-center hover:opacity-75 transition-opacity"
                                style="background-color: {{ $color }}">{{ $num }}</button>
                    @endforeach
                </div>

                <div class="flex justify-center my-1">
                    <div class="w-full max-w-2xl border-t-2 border-gray-300"></div>
                </div>

                {{-- Permanentes inferiores --}}
                <div class="flex justify-center gap-1 mt-2 mb-2 flex-wrap">
                    @foreach ($arcadaInferior as $num)
                        @php $numStr = (string) $num; $color = $estadoDientes[$numStr]['color'] ?? '#d1d5db'; @endphp
                        <button wire:click="marcarDiente('{{ $numStr }}')" title="Diente {{ $num }}"
                                class="w-9 h-10 rounded-md text-xs font-semibold text-white flex items-center justify-center hover:opacity-75 transition-opacity"
                                style="background-color: {{ $color }}">{{ $num }}</button>
                    @endforeach
                </div>
                <div class="border-t border-dashed border-gray-200 mx-8 mb-1 mt-1"></div>

                {{-- Deciduos inferiores --}}
                <div class="flex justify-center gap-1 flex-wrap">
                    @foreach ($deciduosInferior as $num)
                        @php $numStr = (string) $num; $color = $estadoDientes[$numStr]['color'] ?? '#d1d5db'; @endphp
                        <button wire:click="marcarDiente('{{ $numStr }}')" title="Diente {{ $num }}"
                                class="w-8 h-8 rounded-md text-[10px] font-semibold text-white flex items-center justify-center hover:opacity-75 transition-opacity"
                                style="background-color: {{ $color }}">{{ $num }}</button>
                    @endforeach
                </div>

                <p class="text-center text-xs text-gray-400 mt-3">Haz clic en una pieza dental para registrar un hallazgo.</p>
            </div>

            {{-- HALLAZGOS REGISTRADOS --}}
            <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
                <h3 class="text-orange-500 font-semibold mb-3">Hallazgos registrados:</h3>
                @forelse ($detallesOdontograma as $d)
                    <div class="flex items-center justify-between flex-wrap gap-2 border border-gray-100 bg-gray-50 rounded-lg p-3 mb-2">
                        <div class="text-xs">
                            <p class="font-semibold text-gray-700">Pieza {{ $d['pieza_dental'] }} — {{ $d['cara'] }}</p>
                            <p class="text-gray-500">{{ $d['diagnostico'] }}</p>
                            <p class="text-gray-400">Tratamiento: {{ $d['tratamiento']['nombre'] ?? 'Sin asignar' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-semibold px-2 py-1 rounded-full text-white"
                                  style="background-color: {{ $coloresEstado[$d['estado']] ?? '#9ca3af' }}">{{ $d['estado'] }}</span>
                            <button wire:click="abrirModalEditarDetalle({{ $d['id_detalle'] }})"
                                    class="bg-white text-blue-600 border border-blue-200 hover:bg-blue-50 text-xs font-semibold px-3 py-1.5 rounded-lg">Editar</button>
                            <button wire:click="eliminarDetalleDiente({{ $d['id_detalle'] }})" wire:confirm="¿Eliminar este hallazgo?"
                                    class="bg-white text-red-500 border border-red-200 hover:bg-red-50 text-xs font-semibold px-3 py-1.5 rounded-lg">Eliminar</button>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm py-4">Aún no hay hallazgos en este odontograma. Haz clic en una pieza dental del diagrama.</p>
                @endforelse
            </div>
        @endif

        {{-- LISTA DE ODONTOGRAMAS (visitas) --}}
        <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-orange-500 font-semibold">Lista de Odontogramas:</h3>
                <button wire:click="abrirModalNuevoOdontograma"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Registrar Nuevo
                </button>
            </div>

            @forelse ($paciente->odontogramas as $od)
                <div class="border {{ $odontogramaSeleccionadoId === $od->id_odontograma ? 'border-blue-300 bg-blue-50' : 'border-gray-100 bg-gray-50' }} rounded-lg p-3 flex items-center justify-between flex-wrap gap-2 mb-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Dr(a). {{ $od->doctor?->nombre_completo ?? 'Sin asignar' }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5 ml-6">{{ $od->fecha_evaluacion?->format('d/m/Y H:i') }}</p>
                        @if ($od->observaciones_generales)
                            <p class="text-xs text-gray-500 mt-0.5 ml-6">{{ $od->observaciones_generales }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="seleccionarOdontograma({{ $od->id_odontograma }})"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1
                                    {{ $odontogramaSeleccionadoId === $od->id_odontograma
                                        ? 'bg-green-100 text-green-700 border border-green-300'
                                        : 'bg-white text-blue-600 border border-blue-200 hover:bg-blue-50' }}">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $odontogramaSeleccionadoId === $od->id_odontograma ? 'Seleccionado' : 'Seleccionar' }}
                        </button>
                        <button wire:click="confirmarEliminarOdontograma({{ $od->id_odontograma }})"
                                class="bg-white text-red-500 border border-red-200 hover:bg-red-50 text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            @empty
                <div class="border border-dashed border-gray-200 rounded-lg p-6 text-center text-gray-400 text-sm">
                    No hay odontogramas registrados para este paciente. Haz clic en "Registrar Nuevo" para comenzar.
                </div>
            @endforelse
        </div>

        {{-- MODAL: NUEVO ODONTOGRAMA --}}
        @if ($modalNuevoOdontograma)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="$set('modalNuevoOdontograma', false)">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-700">Registrar Nuevo Odontograma</h3>
                        <button wire:click="$set('modalNuevoOdontograma', false)" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>
                    <form wire:submit.prevent="guardarNuevoOdontograma" class="px-6 py-5 space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Doctor(a) responsable *</label>
                            <select wire:model="od_id_doctor" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                <option value="">Seleccionar...</option>
                                @foreach ($doctores as $doc)
                                    <option value="{{ $doc->id_doctor }}">{{ $doc->nombre_completo }}</option>
                                @endforeach
                            </select>
                            @error('od_id_doctor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Observaciones generales</label>
                            <textarea wire:model="od_observaciones" rows="3" placeholder="Ej: Buena higiene oral, sin molestias."
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none"></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-2 border-t">
                            <button type="button" wire:click="$set('modalNuevoOdontograma', false)" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700">Cancelar</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- MODAL: CONFIRMAR ELIMINAR ODONTOGRAMA --}}
        @if ($modalEliminarOdontograma)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-8 text-center">
                    <div class="mx-auto h-14 w-14 rounded-full border-2 border-red-500 flex items-center justify-center text-2xl font-bold text-red-500 mb-4">!</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Eliminar Odontograma</h3>
                    <p class="text-sm text-gray-500 mb-6">¿Está seguro de eliminar este odontograma y todos sus hallazgos? Esta acción no se puede deshacer.</p>
                    <div class="flex justify-center gap-3">
                        <button wire:click="eliminarOdontograma" class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg">Sí, Eliminar</button>
                        <button wire:click="$set('modalEliminarOdontograma', false)" class="bg-slate-700 hover:bg-slate-800 text-white text-sm font-semibold px-6 py-2.5 rounded-lg">Cancelar</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- MODAL: HALLAZGO POR DIENTE (DETALLE_ODONTOGRAMA) --}}
        @if ($modalDetalleDiente)
            <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="cerrarModalDetalleDiente">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-700">{{ $detalleEditandoId ? 'Editar' : 'Registrar' }} Hallazgo — Pieza {{ $dd_pieza_dental }}</h3>
                        <button wire:click="cerrarModalDetalleDiente" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>
                    <form wire:submit.prevent="guardarDetalleDiente" class="px-6 py-5 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Cara *</label>
                                <select wire:model="dd_cara" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($opcionesCara as $cara)
                                        <option value="{{ $cara }}">{{ $cara }}</option>
                                    @endforeach
                                </select>
                                @error('dd_cara') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Estado *</label>
                                <select wire:model="dd_estado" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                    @foreach ($opcionesEstado as $est)
                                        <option value="{{ $est }}">{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Diagnóstico *</label>
                            <input type="text" wire:model="dd_diagnostico" placeholder="Ej: Caries oclusal profunda"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                            @error('dd_diagnostico') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Tratamiento asociado</label>
                            <select wire:model="dd_id_tratamiento" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                                <option value="">Sin asignar</option>
                                @foreach ($tratamientos as $t)
                                    <option value="{{ $t->id_tratamiento }}">{{ $t->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end gap-3 pt-2 border-t">
                            <button type="button" wire:click="cerrarModalDetalleDiente" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700">Cancelar</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    @endif
</div>