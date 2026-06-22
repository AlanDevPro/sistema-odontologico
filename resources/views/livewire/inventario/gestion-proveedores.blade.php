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
    {{-- ENCABEZADO DE SECCIÓN --}}
    {{-- ============================================================ --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Gestión de proveedores</h1>
            <p class="text-sm text-slate-500">Consulta, edita y administra a los proveedores registrados en el sistema.</p>
        </div>

        <button
            type="button"
            wire:click="abrirAgregar"
            class="flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Agregar Proveedor
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- GRID DE CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($this->proveedores as $proveedor)
            <div
                wire:key="proveedor-{{ $proveedor->id_proveedor }}"
                class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
            >
                {{-- Cabecera de la card --}}
                <div class="mb-3 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-base font-semibold leading-snug text-slate-800">{{ $proveedor->razon_social }}</p>
                        <p class="text-xs text-slate-400">NIT/RUC: {{ $proveedor->nit_ruc }}</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sky-50 text-sm font-bold text-sky-600">
                        {{ mb_substr($proveedor->razon_social, 0, 2) }}
                    </div>
                </div>

                {{-- Datos de contacto --}}
                <div class="mt-2 space-y-1.5 border-t border-slate-100 pt-4 text-sm">
                    <p class="text-slate-600">
                        <span class="font-semibold text-slate-700">Contacto:</span>
                        {{ $proveedor->nombre_contacto ?: '—' }}
                    </p>
                    <p class="text-slate-600">
                        <span class="font-semibold text-slate-700">Celular:</span>
                        {{ $proveedor->telefono ?: '—' }}
                    </p>
                    <p class="text-slate-600">
                        <span class="font-semibold text-slate-700">Correo:</span>
                        {{ $proveedor->correo ?: '—' }}
                    </p>
                    <p class="text-slate-600">
                        <span class="font-semibold text-slate-700">Dirección:</span>
                        {{ $proveedor->direccion ?: '—' }}
                    </p>
                </div>

                {{-- Acciones --}}
                <div class="mt-4 grid grid-cols-2 gap-2 rounded-xl bg-slate-50 p-2">
                    <button
                        type="button"
                        wire:click="abrirEditar({{ $proveedor->id_proveedor }})"
                        class="flex items-center justify-center gap-2 rounded-lg bg-orange-500 py-2.5 text-sm font-medium text-white transition hover:bg-orange-600"
                        title="Modificar proveedor"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                        </svg>
                        Editar
                    </button>
                    <button
                        type="button"
                        wire:click="confirmarEliminar({{ $proveedor->id_proveedor }})"
                        class="flex items-center justify-center gap-2 rounded-lg bg-rose-600 py-2.5 text-sm font-medium text-white transition hover:bg-rose-700"
                        title="Eliminar proveedor"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Eliminar
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <p class="text-sm font-medium text-slate-600">Aún no hay proveedores registrados</p>
            </div>
        @endforelse
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: MODIFICAR PROVEEDOR --}}
    {{-- ============================================================ --}}
    @if ($modalEditarVisible)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
            wire:click.self="cerrarEditar"
        >
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">

                <div class="relative bg-orange-500 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">
                        Modificar proveedor
                    </h2>
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

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Razón social
                            </label>
                            <input
                                type="text"
                                wire:model="form_razon_social"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('form_razon_social') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">NIT/RUC</label>
                                <input
                                    type="text"
                                    wire:model="form_nit_ruc"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_nit_ruc') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nombre de contacto</label>
                                <input
                                    type="text"
                                    wire:model="form_nombre_contacto"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_nombre_contacto') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Celular</label>
                                <input
                                    type="text"
                                    wire:model="form_telefono"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_telefono') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Correo</label>
                                <input
                                    type="email"
                                    wire:model="form_correo"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_correo') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Dirección
                            </label>
                            <input
                                type="text"
                                wire:model="form_direccion"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('form_direccion') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button
                                type="button"
                                wire:click="cerrarEditar"
                                class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700"
                            >
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- MODAL: AGREGAR PROVEEDOR --}}
    {{-- ============================================================ --}}
    @if ($modalAgregarVisible)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
            wire:click.self="cerrarAgregar"
        >
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">

                <div class="relative bg-sky-600 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">Agregar proveedor</h2>
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
                    <form wire:submit.prevent="guardarNuevoProveedor" class="space-y-5">

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Razón social
                            </label>
                            <input
                                type="text"
                                wire:model="form_razon_social"
                                placeholder="Ej. Mundo Dental"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('form_razon_social') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">NIT/RUC</label>
                                <input
                                    type="text"
                                    wire:model="form_nit_ruc"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_nit_ruc') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nombre de contacto</label>
                                <input
                                    type="text"
                                    wire:model="form_nombre_contacto"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_nombre_contacto') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Celular</label>
                                <input
                                    type="text"
                                    wire:model="form_telefono"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_telefono') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Correo</label>
                                <input
                                    type="email"
                                    wire:model="form_correo"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_correo') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Dirección
                            </label>
                            <input
                                type="text"
                                wire:model="form_direccion"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('form_direccion') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button
                                type="button"
                                wire:click="cerrarAgregar"
                                class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700"
                            >
                                Guardar proveedor
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
                <h3 class="text-base font-semibold text-slate-800">¿Eliminar este proveedor?</h3>
                <p class="mt-1 text-sm text-slate-500">Esta acción eliminará al proveedor de forma permanente. Si tiene compras registradas, no podrá eliminarse.</p>

                <div class="mt-5 flex justify-center gap-3">
                    <button type="button" wire:click="cancelarEliminar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="eliminarProveedor" class="rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>