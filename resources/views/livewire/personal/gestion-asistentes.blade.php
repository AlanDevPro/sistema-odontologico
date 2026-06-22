<div class="p-6 md:p-8 bg-slate-50 min-h-screen">

    {{-- ============================================================ --}}
    {{-- MENSAJES FLASH --}}
    {{-- ============================================================ --}}
    @if (session('mensaje'))
        <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5m4.5 2.25a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- VISTA 1: LISTA DE CARDS DE ASISTENTES --}}
    {{-- ============================================================ --}}
    @if ($vista === 'lista')
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Mis Asistentes</h1>
                <p class="text-sm text-slate-400">/ asistentes /</p>
            </div>

            <button
                type="button"
                wire:click="abrirAgregar"
                class="flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.625-3.75a3 3 0 11-6 0 3 3 0 016 0zm-2.25 7.5h-3.75a3.375 3.375 0 00-3.375 3.375V21h10.5v-2.625a3.375 3.375 0 00-3.375-3.375z" />
                </svg>
                Agregar Asistente
            </button>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->asistentes as $a)
                <div
                    wire:key="asistente-{{ $a->id_asistente }}"
                    class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="mb-3 flex items-center justify-between text-xs text-slate-400">
                        <span>Asistente:</span>
                        <span>{{ $a->codigo }}</span>
                    </div>

                    <div class="mb-4 flex items-center gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full border border-slate-100 bg-sky-50 text-lg font-semibold text-sky-600">
                            {{ mb_substr($a->nombres, 0, 1) }}{{ mb_substr($a->apellidos, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-base font-semibold leading-snug text-slate-800">{{ $a->nombres }}</p>
                            @foreach (explode(' ', $a->apellidos) as $apellido)
                                <p class="text-sm leading-snug text-slate-500">{{ $apellido }}</p>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-400">Inicio de labores:</p>
                            <p class="font-medium text-slate-700">
                                {{ $a->fecha_contratacion ? $a->fecha_contratacion->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Turno:</p>
                            <p class="font-medium text-slate-700">{{ $a->turno ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="mt-auto grid grid-cols-3 gap-2 rounded-xl bg-slate-50 p-2">
                        <button
                            type="button"
                            wire:click="verDetalle({{ $a->id_asistente }})"
                            class="flex items-center justify-center rounded-lg bg-sky-600 py-2.5 text-white transition hover:bg-sky-700"
                            title="Ver asistente"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            wire:click="abrirEditar({{ $a->id_asistente }})"
                            class="flex items-center justify-center rounded-lg bg-orange-500 py-2.5 text-white transition hover:bg-orange-600"
                            title="Modificar asistente"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            wire:click="confirmarEliminar({{ $a->id_asistente }})"
                            class="flex items-center justify-center rounded-lg bg-rose-600 py-2.5 text-white transition hover:bg-rose-700"
                            title="Eliminar asistente"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <p class="text-sm font-medium text-slate-600">Aún no hay asistentes registrados</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- VISTA 2: DETALLE DEL ASISTENTE --}}
    {{-- ============================================================ --}}
    @if ($vista === 'detalle' && $this->asistenteActual)
        @php $a = $this->asistenteActual; @endphp

        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Ficha del Asistente</h1>
                <p class="text-sm text-slate-400">/ asistentes / {{ $a->codigo }}</p>
            </div>

            <button
                type="button"
                wire:click="volverALista"
                class="flex items-center gap-2 rounded-xl bg-slate-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700"
            >
                Atrás
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
            </button>
        </div>

        <div class="mx-auto max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Cabecera --}}
            <div class="flex items-center gap-5 border-b border-slate-100 bg-sky-50/60 px-6 py-6">
                <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full border-4 border-white bg-sky-100 text-2xl font-semibold text-sky-600 shadow-sm">
                    {{ mb_substr($a->nombres, 0, 1) }}{{ mb_substr($a->apellidos, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-sky-600">{{ $a->codigo }}</p>
                    <p class="text-xl font-semibold text-slate-800">{{ $a->nombres }} {{ $a->apellidos }}</p>
                    <p class="text-sm text-slate-500">CI/DNI: {{ $a->ci_dni }}</p>
                </div>
            </div>

            {{-- Datos --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 px-6 py-6 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">DNI</p>
                    <p class="mt-1 text-sm text-slate-700">{{ $a->ci_dni }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Celular</p>
                    <p class="mt-1 text-sm text-slate-700">{{ $a->telefono ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Inicio laboral</p>
                    <p class="mt-1 text-sm text-slate-700">
                        {{ $a->fecha_contratacion ? $a->fecha_contratacion->format('d/m/Y') : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Turno</p>
                    <p class="mt-1 text-sm text-slate-700">{{ $a->turno ?: '—' }}</p>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <button
                    type="button"
                    wire:click="confirmarEliminar({{ $a->id_asistente }})"
                    class="flex items-center gap-2 rounded-lg bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-100"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Eliminar
                </button>
                <button
                    type="button"
                    wire:click="abrirEditar({{ $a->id_asistente }})"
                    class="flex items-center gap-2 rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                    </svg>
                    Modificar
                </button>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- MODAL: MODIFICAR ASISTENTE --}}
    {{-- ============================================================ --}}
    @if ($modalEditarVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cerrarEditar">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">

                <div class="relative bg-orange-500 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">Modificar Asistente</h2>
                    <button
                        type="button"
                        wire:click="cerrarEditar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 transition hover:text-white"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="max-h-[80vh] overflow-y-auto px-6 py-5">
                    <form wire:submit.prevent="guardarEditar" class="space-y-5">

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nombres</label>
                                <input type="text" wire:model="form_nombres" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_nombres') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Apellidos</label>
                                <input type="text" wire:model="form_apellidos" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_apellidos') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">CI/DNI:</label>
                                <input type="text" wire:model="form_ci_dni" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_ci_dni') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Celular:</label>
                                <input type="text" wire:model="form_telefono" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_telefono') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Inicio laboral:</label>
                                <input type="date" wire:model="form_fecha_contratacion" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_fecha_contratacion') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Turno:</label>
                                <input type="text" wire:model="form_turno" placeholder="Ej. Lunes a Viernes" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_turno') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="button" wire:click="cerrarEditar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit" class="flex items-center gap-2 rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Modificar Asistente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- MODAL: AGREGAR ASISTENTE --}}
    {{-- ============================================================ --}}
    @if ($modalAgregarVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cerrarAgregar">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">

                <div class="relative bg-sky-600 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">Agregar Asistente</h2>
                    <button
                        type="button"
                        wire:click="cerrarAgregar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 transition hover:text-white"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="max-h-[80vh] overflow-y-auto px-6 py-5">
                    <form wire:submit.prevent="guardarNuevoAsistente" class="space-y-5">

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nombres</label>
                                <input type="text" wire:model="form_nombres" placeholder="Ej. Carla" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_nombres') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Apellidos</label>
                                <input type="text" wire:model="form_apellidos" placeholder="Ej. Pérez Gómez" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_apellidos') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">CI/DNI:</label>
                                <input type="text" wire:model="form_ci_dni" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_ci_dni') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Celular:</label>
                                <input type="text" wire:model="form_telefono" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_telefono') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Inicio laboral:</label>
                                <input type="date" wire:model="form_fecha_contratacion" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_fecha_contratacion') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Turno:</label>
                                <input type="text" wire:model="form_turno" placeholder="Ej. Lunes a Viernes" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_turno') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" wire:click="cerrarAgregar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit" class="rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                                Guardar asistente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- MODAL: CONFIRMAR ELIMINACIÓN --}}
    {{-- ============================================================ --}}
    @if ($modalConfirmarEliminarVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cancelarEliminar">
            <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white p-6 text-center shadow-xl">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">¿Eliminar este asistente?</h3>
                <p class="mt-1 text-sm text-slate-500">Esta acción quitará al asistente de la lista de personal activo.</p>

                <div class="mt-5 flex justify-center gap-3">
                    <button type="button" wire:click="cancelarEliminar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="eliminarAsistente" class="rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>