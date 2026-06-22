<div>
    {{-- ===================================================================== --}}
    {{-- MENSAJE FLASH --}}
    {{-- ===================================================================== --}}
    @if (session()->has('mensaje'))
        <div class="fixed top-4 right-4 z-[100] bg-green-500 text-white px-5 py-3 rounded-lg shadow-lg"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('mensaje') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-[100] bg-red-500 text-white px-5 py-3 rounded-lg shadow-lg"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===================================================================== --}}
    {{-- CONTENIDO PRINCIPAL --}}
    {{-- ===================================================================== --}}
    <div class="p-6">

        {{-- Título + botón agregar --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Citas agendadas</h1>
                <p class="text-xs text-gray-400">/ citas /</p>
            </div>
            <button wire:click="abrirAgregar"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow flex items-center gap-2 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar Cita
            </button>
        </div>

        {{-- Buscador --}}
        <div class="bg-white rounded-xl shadow p-5 mb-4">
            <p class="text-center font-semibold text-gray-700 mb-3">Buscar Cita del paciente:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" wire:model.live.debounce.400ms="buscarNombres"
                       placeholder="Nombres"
                       class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                <input type="text" wire:model.live.debounce.400ms="buscarApellidoPaterno"
                       placeholder="Apellido Paterno"
                       class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                <input type="text" wire:model.live.debounce.400ms="buscarApellidoMaterno"
                       placeholder="Apellido Materno"
                       class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
        </div>

        {{-- Orden y Filtro --}}
        <div class="flex flex-wrap items-center gap-4 mb-4">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-600">Orden:</span>
                <select wire:model.live="orden"
                        class="border border-gray-200 rounded-lg px-4 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <option value="recientes">Recien registrados</option>
                    <option value="antiguos">Más antiguos</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-600">Filtro:</span>
                <select wire:model.live="filtroEstado"
                        class="border border-gray-200 rounded-lg px-4 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <option value="todas">Todas las citas</option>
                    <option value="Pendiente">Pendientes</option>
                    <option value="Confirmada">Confirmadas</option>
                    <option value="Atendida">Atendidas</option>
                    <option value="Cancelada">Canceladas</option>
                    <option value="Reprogramada">Reprogramadas</option>
                </select>
            </div>
        </div>

        {{-- ===================================================================== --}}
        {{-- TABLA DE CITAS --}}
        {{-- ===================================================================== --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-orange-500 to-red-600 text-white">
                            <th class="py-3 px-4 text-left font-semibold">#</th>
                            <th class="py-3 px-4 text-left font-semibold">Paciente</th>
                            <th class="py-3 px-4 text-left font-semibold">Doctor</th>
                            <th class="py-3 px-4 text-left font-semibold">Fecha/Hora</th>
                            <th class="py-3 px-4 text-left font-semibold">Motivo</th>
                            <th class="py-3 px-4 text-left font-semibold">Estado</th>
                            <th class="py-3 px-4 text-left font-semibold">Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($citasFiltradas as $cita)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-semibold text-gray-700">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-gray-700">{{ $cita->paciente->nombres ?? 'N/A' }}</p>
                                    <p class="text-gray-500 text-xs">{{ $cita->paciente->apellidos ?? '' }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-semibold text-gray-700">{{ $cita->doctor->nombres ?? 'N/A' }}</p>
                                    <p class="text-gray-500 text-xs">{{ $cita->doctor->apellidos ?? '' }}</p>
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->locale('es')->isoFormat('dddd') }}<br>
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}<br>
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}
                                </td>
                                <td class="py-3 px-4 text-gray-600 max-w-[180px] truncate">{{ $cita->motivo }}</td>
                                <td class="py-3 px-4">
                                    @php $clase = $this->colorEstado($cita->estado); @endphp
                                    <button wire:click="abrirEstado({{ $cita->id_cita }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold uppercase shadow-sm
                                        @if($clase === 'pendiente') bg-orange-100 text-orange-600
                                        @elseif($clase === 'cancelada') bg-red-100 text-red-600
                                        @elseif($clase === 'confirmada') bg-blue-100 text-blue-600
                                        @elseif($clase === 'atendida') bg-green-100 text-green-600
                                        @elseif($clase === 'reprogramada') bg-purple-100 text-purple-600
                                        @else bg-gray-100 text-gray-600 @endif">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        {{ $cita->estado }}
                                    </button>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3 text-gray-400">
                                        <button wire:click="abrirVer({{ $cita->id_cita }})" title="Ver"
                                            class="hover:text-blue-500 transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="abrirModificar({{ $cita->id_cita }})" title="Editar"
                                            class="hover:text-orange-500 transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="abrirEliminar({{ $cita->id_cita }})" title="Eliminar"
                                            class="hover:text-red-500 transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-400">
                                    No se encontraron citas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $citasFiltradas->links() }}
        </div>
    </div>

    {{-- ===================================================================== --}}
    {{-- MODAL: AGREGAR CITA --}}
    {{-- ===================================================================== --}}
    @if ($modalAgregar)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden max-h-[90vh] overflow-y-auto">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 py-4 text-center relative">
                    <h2 class="text-white font-bold text-lg">Agregar Cita</h2>
                    <button wire:click="cerrarModales" class="absolute right-4 top-4 text-white/80 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="guardarNuevaCita" class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Paciente *</label>
                        <select wire:model="id_paciente"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Seleccione un paciente...</option>
                            @foreach ($pacientes as $p)
                                <option value="{{ $p->id_paciente }}">{{ $p->nombres }} {{ $p->apellidos }}</option>
                            @endforeach
                        </select>
                        @error('id_paciente') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500">Doctor *</label>
                        <select wire:model="id_doctor"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Seleccione un doctor...</option>
                            @foreach ($doctores as $d)
                                <option value="{{ $d->id_doctor }}">{{ $d->nombres }} {{ $d->apellidos }}</option>
                            @endforeach
                        </select>
                        @error('id_doctor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500">Asistente</label>
                        <select wire:model="id_asistente"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Sin asistente</option>
                            @foreach ($asistentes as $a)
                                <option value="{{ $a->id_asistente }}">{{ $a->nombres }} {{ $a->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500">Fecha y hora *</label>
                        <input type="datetime-local" wire:model="fecha_hora"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('fecha_hora') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500">Motivo *</label>
                        <textarea wire:model="motivo" rows="3"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                            placeholder="Describa el motivo de la cita..."></textarea>
                        @error('motivo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-lg flex items-center justify-center gap-2 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Cita
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- ===================================================================== --}}
    {{-- MODAL: MODIFICAR CITA --}}
    {{-- ===================================================================== --}}
    @if ($modalModificar)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden max-h-[90vh] overflow-y-auto">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 py-4 text-center relative">
                    <h2 class="text-white font-bold text-lg">Modificar Cita</h2>
                    <button wire:click="cerrarModales" class="absolute right-4 top-4 text-white/80 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="actualizarCita" class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Paciente *</label>
                        <select wire:model="id_paciente"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Seleccione un paciente...</option>
                            @foreach ($pacientes as $p)
                                <option value="{{ $p->id_paciente }}">{{ $p->nombres }} {{ $p->apellidos }}</option>
                            @endforeach
                        </select>
                        @error('id_paciente') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500">Doctor *</label>
                        <select wire:model="id_doctor"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Seleccione un doctor...</option>
                            @foreach ($doctores as $d)
                                <option value="{{ $d->id_doctor }}">{{ $d->nombres }} {{ $d->apellidos }}</option>
                            @endforeach
                        </select>
                        @error('id_doctor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500">Asistente</label>
                        <select wire:model="id_asistente"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Sin asistente</option>
                            @foreach ($asistentes as $a)
                                <option value="{{ $a->id_asistente }}">{{ $a->nombres }} {{ $a->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500">Fecha y hora *</label>
                        <input type="datetime-local" wire:model="fecha_hora"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('fecha_hora') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500">Motivo *</label>
                        <textarea wire:model="motivo" rows="3"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                            placeholder="Describa el motivo de la cita..."></textarea>
                        @error('motivo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-lg flex items-center justify-center gap-2 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Modificar Cita
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- ===================================================================== --}}
    {{-- MODAL: ESTADO DE LA CITA --}}
    {{-- ===================================================================== --}}
    @if ($modalEstado)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 py-4 text-center relative">
                    <h2 class="text-white font-bold text-lg">Estado de la Cita</h2>
                    <button wire:click="cerrarModales" class="absolute right-4 top-4 text-white/80 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    @if ($citaActual)
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <svg class="h-10 w-10 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-400">Paciente:</p>
                                    <p class="font-bold text-gray-700">{{ $citaActual->paciente->nombres ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $citaActual->paciente->apellidos ?? '' }}</p>
                                </div>
                            </div>
                            <div class="bg-orange-50 text-orange-600 text-xs font-bold px-3 py-1.5 rounded-full inline-flex items-center gap-1.5">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $citaActual->estado }}
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 text-center text-sm text-gray-600 mb-4">
                            La cita se agendó para el día<br>
                            <span class="font-bold text-gray-700">
                                {{ \Carbon\Carbon::parse($citaActual->fecha_hora)->locale('es')->isoFormat('dddd DD/MM/YYYY') }}
                                a las {{ \Carbon\Carbon::parse($citaActual->fecha_hora)->format('H:i') }}
                            </span>
                        </div>
                    @endif

                    <form wire:submit.prevent="actualizarEstado" class="space-y-3">
                        <label class="text-xs font-semibold text-gray-500">Cambiar estado de la cita</label>

                        <div class="border border-purple-300 rounded-lg overflow-hidden">
                            <select wire:model="estado_nuevo"
                                class="w-full px-4 py-2.5 text-sm focus:outline-none">
                                @foreach ($estados as $e)
                                    <option value="{{ $e }}">{{ $e }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1 pt-1">
                            @foreach ($estados as $e)
                                <button type="button" wire:click="$set('estado_nuevo', '{{ $e }}')"
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-left transition
                                    {{ $estado_nuevo === $e ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    {{ $e }}
                                </button>
                            @endforeach
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-lg flex items-center justify-center gap-2 transition mt-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Cambiar Estado
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ===================================================================== --}}
    {{-- MODAL: VER DETALLE DE LA CITA --}}
    {{-- ===================================================================== --}}
    @if ($modalVer)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 py-4 text-center relative">
                    <h2 class="text-white font-bold text-lg">Detalle de la Cita</h2>
                    <button wire:click="cerrarModales" class="absolute right-4 top-4 text-white/80 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if ($citaActual)
                    <div class="p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <svg class="h-12 w-12 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-400">Paciente:</p>
                                <p class="font-bold text-gray-700">{{ $citaActual->paciente->nombres ?? 'N/A' }} {{ $citaActual->paciente->apellidos ?? '' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                                <p class="text-xs text-gray-400">Doctor</p>
                                <p class="font-semibold text-gray-700">{{ $citaActual->doctor->nombres ?? 'N/A' }} {{ $citaActual->doctor->apellidos ?? '' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-400">Estado</p>
                                <p class="font-semibold text-gray-700">{{ $citaActual->estado }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-400">Asistente</p>
                                <p class="font-semibold text-gray-700">{{ $citaActual->asistente->nombres ?? 'Sin asignar' }} {{ $citaActual->asistente->apellidos ?? '' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                                <p class="text-xs text-gray-400">Fecha y hora</p>
                                <p class="font-semibold text-gray-700">
                                    {{ \Carbon\Carbon::parse($citaActual->fecha_hora)->locale('es')->isoFormat('dddd DD/MM/YYYY') }}
                                    - {{ \Carbon\Carbon::parse($citaActual->fecha_hora)->format('H:i') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                                <p class="text-xs text-gray-400">Motivo</p>
                                <p class="font-semibold text-gray-700">{{ $citaActual->motivo }}</p>
                            </div>
                        </div>

                        <button wire:click="cerrarModales"
                            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition">
                            Cerrar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ===================================================================== --}}
    {{-- MODAL: CONFIRMAR ELIMINAR --}}
    {{-- ===================================================================== --}}
    @if ($modalEliminar)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 py-4 text-center relative">
                    <h2 class="text-white font-bold text-lg">Eliminar Cita</h2>
                    <button wire:click="cerrarModales" class="absolute right-4 top-4 text-white/80 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 text-center">
                    <svg class="h-16 w-16 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-gray-600 mb-6">¿Está seguro que desea eliminar esta cita? Esta acción no se puede deshacer.</p>
                    <div class="flex gap-3">
                        <button wire:click="cerrarModales"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg transition">
                            Cancelar
                        </button>
                        <button wire:click="eliminarCita"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-lg transition">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>