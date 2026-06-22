<div class="min-h-screen bg-slate-50 p-6 md:p-8">

    {{-- ============================================================ --}}
    {{-- ESTILOS DE ANIMACIÓN --}}
    {{-- ============================================================ --}}
    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(.95) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .card-asistente { animation: fadeSlideUp .45s ease both; }
        @media (prefers-reduced-motion: reduce) {
            .card-asistente { animation: none; }
        }
        .modal-pop { animation: modalPop .25s ease-out both; }
    </style>

    {{-- ============================================================ --}}
    {{-- MENSAJES FLASH --}}
    {{-- ============================================================ --}}
    @if (session('mensaje'))
        <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 shadow-sm">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5m4.5 2.25a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 shadow-sm">
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
        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Mis Asistentes</h1>
                <p class="text-xs font-medium text-slate-400">/ asistentes /</p>
            </div>

            <button
                type="button"
                wire:click="abrirAgregar"
                class="group inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-sky-600/30 transition-all duration-200 hover:-translate-y-0.5 hover:bg-sky-700 hover:shadow-lg hover:shadow-sky-600/40 active:translate-y-0"
            >
                <svg class="h-5 w-5 transition-transform duration-200 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Asistente
            </button>
        </div>

        {{-- Resumen rápido --}}
        <div class="mb-6 flex flex-wrap items-center justify-around gap-3 rounded-2xl border border-slate-200/70 bg-white px-6 py-3.5 text-sm text-slate-500 shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                Total de asistentes:
                <span class="rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-bold text-sky-700">{{ $this->asistentes->count() }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->asistentes as $i => $a)
                <div
                    wire:key="asistente-{{ $a->id_asistente }}"
                    class="card-asistente group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm ring-1 ring-transparent transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:ring-sky-100"
                    style="animation-delay: {{ min($i, 8) * 60 }}ms"
                >
                    <div class="mb-3 flex items-center justify-between text-xs text-slate-400">
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Asistente
                        </span>
                        <span class="rounded-full bg-slate-50 px-2 py-0.5 font-mono font-semibold text-slate-500">{{ $a->codigo }}</span>
                    </div>

                    <div class="mb-4 flex items-center gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full border-2 border-sky-100 bg-gradient-to-br from-sky-50 to-sky-100 text-lg font-bold text-sky-600 shadow-sm transition-transform duration-300 group-hover:scale-105">
                            {{ mb_substr($a->nombres, 0, 1) }}{{ mb_substr($a->apellidos, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-base font-semibold leading-snug text-slate-800">{{ $a->nombres }}</p>
                            <p class="truncate text-sm leading-snug text-slate-500">{{ $a->apellidos }}</p>
                        </div>
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-sm">
                        <div>
                            <p class="flex items-center gap-1 text-xs text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                Inicio laboral
                            </p>
                            <p class="font-semibold text-slate-700">
                                {{ $a->fecha_contratacion ? $a->fecha_contratacion->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="flex items-center gap-1 text-xs text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Turno
                            </p>
                            <p class="font-semibold text-slate-700">{{ $a->turno ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="mt-auto grid grid-cols-3 gap-2">
                        <button
                            type="button"
                            wire:click="verDetalle({{ $a->id_asistente }})"
                            class="flex items-center justify-center rounded-lg bg-sky-50 py-2.5 text-sky-600 transition-all duration-200 hover:-translate-y-0.5 hover:bg-sky-600 hover:text-white hover:shadow-md"
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
                            class="flex items-center justify-center rounded-lg bg-orange-50 py-2.5 text-orange-500 transition-all duration-200 hover:-translate-y-0.5 hover:bg-orange-500 hover:text-white hover:shadow-md"
                            title="Modificar asistente"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            wire:click="confirmarEliminar({{ $a->id_asistente }})"
                            class="flex items-center justify-center rounded-lg bg-rose-50 py-2.5 text-rose-500 transition-all duration-200 hover:-translate-y-0.5 hover:bg-rose-600 hover:text-white hover:shadow-md"
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
                    <svg class="mb-3 h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <p class="text-sm font-medium text-slate-600">Aún no hay asistentes registrados</p>
                    <p class="mt-1 text-xs text-slate-400">Agregá el primero con el botón de arriba.</p>
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
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Ficha del Asistente</h1>
                <p class="text-xs font-medium text-slate-400">/ asistentes / {{ $a->codigo }}</p>
            </div>

            <button
                type="button"
                wire:click="volverALista"
                class="flex items-center gap-2 rounded-xl bg-slate-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-slate-700 hover:shadow-md"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                Atrás
            </button>
        </div>

        <div class="modal-pop mx-auto max-w-2xl overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm">

            {{-- Cabecera --}}
            <div class="flex items-center gap-5 border-b border-slate-100 bg-gradient-to-r from-sky-50 to-cyan-50 px-6 py-6">
                <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full border-4 border-white bg-gradient-to-br from-sky-100 to-sky-200 text-2xl font-bold text-sky-600 shadow-md">
                    {{ mb_substr($a->nombres, 0, 1) }}{{ mb_substr($a->apellidos, 0, 1) }}
                </div>
                <div>
                    <p class="inline-flex items-center gap-1 rounded-full bg-white/70 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide text-sky-600 shadow-sm">
                        {{ $a->codigo }}
                    </p>
                    <p class="mt-1.5 text-xl font-bold text-slate-800">{{ $a->nombres }} {{ $a->apellidos }}</p>
                    <p class="flex items-center gap-1 text-sm text-slate-500">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 7.5h-9v9h9v-9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v4.5M15.75 3v4.5M3 8.25h18" />
                        </svg>
                        CI/DNI: {{ $a->ci_dni }}
                    </p>
                </div>
            </div>

            {{-- Datos --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 px-6 py-6 sm:grid-cols-2">
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 7.5h-9v9h9v-9z" />
                        </svg>
                        DNI
                    </p>
                    <p class="mt-1 text-sm font-medium text-slate-700">{{ $a->ci_dni }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a1.5 1.5 0 001.5-1.5v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.948-1.418-5.349-3.82-6.767-6.768l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.852A1.125 1.125 0 005.872 3H4.5a1.5 1.5 0 00-1.5 1.5v2.25z" />
                        </svg>
                        Celular
                    </p>
                    <p class="mt-1 text-sm font-medium text-slate-700">{{ $a->telefono ?: '—' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        Inicio laboral
                    </p>
                    <p class="mt-1 text-sm font-medium text-slate-700">
                        {{ $a->fecha_contratacion ? $a->fecha_contratacion->format('d/m/Y') : '—' }}
                    </p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Turno
                    </p>
                    <p class="mt-1 text-sm font-medium text-slate-700">{{ $a->turno ?: '—' }}</p>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <button
                    type="button"
                    wire:click="confirmarEliminar({{ $a->id_asistente }})"
                    class="flex items-center gap-2 rounded-lg bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-600 transition-all duration-200 hover:-translate-y-0.5 hover:bg-rose-100 hover:shadow-sm"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Eliminar
                </button>
                <button
                    type="button"
                    wire:click="abrirEditar({{ $a->id_asistente }})"
                    class="flex items-center gap-2 rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-orange-600 hover:shadow-md"
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm" wire:click.self="cerrarEditar">
            <div class="modal-pop w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">

                <div class="relative bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4 text-center">
                    <h2 class="flex items-center justify-center gap-2 text-lg font-bold text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                        </svg>
                        Modificar Asistente
                    </h2>
                    <button
                        type="button"
                        wire:click="cerrarEditar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full p-1 text-white/80 transition hover:bg-white/10 hover:text-white"
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
                                <input type="text" wire:model="form_nombres" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_nombres') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Apellidos</label>
                                <input type="text" wire:model="form_apellidos" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_apellidos') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">CI/DNI:</label>
                                <input type="text" wire:model="form_ci_dni" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_ci_dni') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Celular:</label>
                                <input type="text" wire:model="form_telefono" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_telefono') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Inicio laboral:</label>
                                <input type="date" wire:model="form_fecha_contratacion" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_fecha_contratacion') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Turno:</label>
                                <input type="text" wire:model="form_turno" placeholder="Ej. Lunes a Viernes" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_turno') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="button" wire:click="cerrarEditar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit" class="flex items-center gap-2 rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-sky-700 hover:shadow-md">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm" wire:click.self="cerrarAgregar">
            <div class="modal-pop w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">

                <div class="relative bg-gradient-to-r from-sky-600 to-cyan-600 px-6 py-4 text-center">
                    <h2 class="flex items-center justify-center gap-2 text-lg font-bold text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.625-3.75a3 3 0 11-6 0 3 3 0 016 0zm-2.25 7.5h-3.75a3.375 3.375 0 00-3.375 3.375V21h10.5v-2.625a3.375 3.375 0 00-3.375-3.375z" />
                        </svg>
                        Agregar Asistente
                    </h2>
                    <button
                        type="button"
                        wire:click="cerrarAgregar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full p-1 text-white/80 transition hover:bg-white/10 hover:text-white"
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
                                <input type="text" wire:model="form_nombres" placeholder="Ej. Carla" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_nombres') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Apellidos</label>
                                <input type="text" wire:model="form_apellidos" placeholder="Ej. Pérez Gómez" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_apellidos') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">CI/DNI:</label>
                                <input type="text" wire:model="form_ci_dni" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_ci_dni') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Celular:</label>
                                <input type="text" wire:model="form_telefono" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_telefono') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Inicio laboral:</label>
                                <input type="date" wire:model="form_fecha_contratacion" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_fecha_contratacion') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Turno:</label>
                                <input type="text" wire:model="form_turno" placeholder="Ej. Lunes a Viernes" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 transition focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('form_turno') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="button" wire:click="cerrarAgregar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit" class="flex items-center gap-2 rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-sky-700 hover:shadow-md">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Guardar Asistente
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm" wire:click.self="cancelarEliminar">
            <div class="modal-pop w-full max-w-sm overflow-hidden rounded-2xl bg-white p-6 text-center shadow-2xl">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-500 ring-8 ring-rose-50/50">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">¿Eliminar este asistente?</h3>
                <p class="mt-1 text-sm text-slate-500">Esta acción quitará al asistente de la lista de personal activo.</p>

                <div class="mt-5 flex justify-center gap-3">
                    <button type="button" wire:click="cancelarEliminar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="eliminarAsistente" class="rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-rose-700 hover:shadow-md">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>