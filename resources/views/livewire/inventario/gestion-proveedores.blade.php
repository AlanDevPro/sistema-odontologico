<div class="min-h-screen p-6 md:p-8"
     style="background: linear-gradient(135deg, #eff8ff 0%, #f8fafc 50%, #fdf4ff 100%);">

    {{-- ============================================================ --}}
    {{-- MENSAJES FLASH --}}
    {{-- ============================================================ --}}
    @if (session('mensaje'))
        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-emerald-800 shadow-sm shadow-emerald-100/60">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <i class="ti ti-circle-check text-emerald-600" style="font-size:18px"></i>
            </span>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-rose-800 shadow-sm shadow-rose-100/60">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100">
                <i class="ti ti-alert-triangle text-rose-500" style="font-size:18px"></i>
            </span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ENCABEZADO --}}
    {{-- ============================================================ --}}
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 shadow-sm">
                <i class="ti ti-truck-delivery text-violet-600" style="font-size:22px"></i>
            </span>
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Gestión de Proveedores</h1>
                <p class="text-xs text-slate-400 mt-0.5">Consulta, edita y administra los proveedores registrados.</p>
            </div>
        </div>

        <button
            type="button"
            wire:click="abrirAgregar"
            class="group flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-300/40 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-sky-300/50 active:translate-y-0"
        >
            <i class="ti ti-plus transition-transform duration-200 group-hover:rotate-90" style="font-size:18px"></i>
            Agregar Proveedor
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- GRID DE CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($this->proveedores as $proveedor)
            <div
                wire:key="proveedor-{{ $proveedor->id_proveedor }}"
                class="flex flex-col overflow-hidden rounded-2xl border border-white bg-white shadow-md shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-slate-300/50"
            >
                {{-- Cabecera de la card --}}
                <div class="flex items-start justify-between gap-3 bg-gradient-to-br from-violet-50 to-blue-50/60 px-5 py-4 border-b border-violet-100/60">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold leading-snug text-slate-800">{{ $proveedor->razon_social }}</p>
                        <p class="mt-1 flex items-center gap-1.5 text-xs text-slate-400">
                            <i class="ti ti-id-badge" style="font-size:12px"></i>
                            NIT/RUC: <span class="font-mono font-medium text-slate-500">{{ $proveedor->nit_ruc }}</span>
                        </p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-blue-500 text-sm font-bold text-white shadow-sm shadow-violet-300/40">
                        {{ mb_strtoupper(mb_substr($proveedor->razon_social, 0, 2)) }}
                    </div>
                </div>

                {{-- Datos de contacto --}}
                <div class="flex-1 space-y-2.5 px-5 py-4">
                    <div class="flex items-center gap-2.5 text-sm">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                            <i class="ti ti-user text-slate-500" style="font-size:14px"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs text-slate-400">Contacto</p>
                            <p class="truncate text-xs font-medium text-slate-700">{{ $proveedor->nombre_contacto ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 text-sm">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-50">
                            <i class="ti ti-device-mobile text-emerald-500" style="font-size:14px"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs text-slate-400">Celular</p>
                            <p class="truncate text-xs font-medium text-slate-700">{{ $proveedor->telefono ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 text-sm">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-sky-50">
                            <i class="ti ti-mail text-sky-500" style="font-size:14px"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs text-slate-400">Correo</p>
                            <p class="truncate text-xs font-medium text-slate-700">{{ $proveedor->correo ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 text-sm">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-50">
                            <i class="ti ti-map-pin text-amber-500" style="font-size:14px"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs text-slate-400">Dirección</p>
                            <p class="truncate text-xs font-medium text-slate-700">{{ $proveedor->direccion ?: '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="grid grid-cols-2 gap-2 border-t border-slate-100 bg-slate-50/70 px-4 py-3">
                    <button
                        type="button"
                        wire:click="abrirEditar({{ $proveedor->id_proveedor }})"
                        class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-orange-400 to-orange-500 py-2.5 text-xs font-semibold text-white shadow-sm shadow-orange-200/60 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:shadow-orange-200/70"
                        title="Modificar proveedor"
                    >
                        <i class="ti ti-edit" style="font-size:15px"></i>
                        Editar
                    </button>
                    <button
                        type="button"
                        wire:click="confirmarEliminar({{ $proveedor->id_proveedor }})"
                        class="flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 py-2.5 text-xs font-semibold text-rose-600 transition-all duration-200 hover:bg-rose-100 hover:border-rose-300 hover:-translate-y-0.5"
                        title="Eliminar proveedor"
                    >
                        <i class="ti ti-trash" style="font-size:15px"></i>
                        Eliminar
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-white/60 px-6 py-16 text-center">
                <span class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                    <i class="ti ti-building-store text-slate-400" style="font-size:32px"></i>
                </span>
                <p class="text-sm font-semibold text-slate-600">Aún no hay proveedores registrados</p>
                <p class="mt-1.5 text-xs text-slate-400">Agrega un proveedor usando el botón de arriba.</p>
            </div>
        @endforelse
    </div>


    {{-- ============================================================ --}}
    {{-- MODAL: MODIFICAR PROVEEDOR --}}
    {{-- ============================================================ --}}
    @if ($modalEditarVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cerrarEditar">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200/60">

                <div class="relative bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2.5">
                        <i class="ti ti-edit text-white/90" style="font-size:20px"></i>
                        <h2 class="text-lg font-semibold text-white">Modificar Proveedor</h2>
                    </div>
                    <button type="button" wire:click="cerrarEditar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg text-white/70 transition hover:bg-white/20 hover:text-white">
                        <i class="ti ti-x" style="font-size:18px"></i>
                    </button>
                </div>

                <div class="max-h-[80vh] overflow-y-auto px-6 py-6">
                    <form wire:submit.prevent="guardarEditar" class="space-y-5">

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-building" style="font-size:13px"></i> Razón social
                            </label>
                            <input type="text" wire:model="form_razon_social"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('form_razon_social') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-id-badge" style="font-size:13px"></i> NIT/RUC
                                </label>
                                <input type="text" wire:model="form_nit_ruc"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-mono text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_nit_ruc') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-user" style="font-size:13px"></i> Nombre de contacto
                                </label>
                                <input type="text" wire:model="form_nombre_contacto"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_nombre_contacto') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-device-mobile" style="font-size:13px"></i> Celular
                                </label>
                                <input type="text" wire:model="form_telefono"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_telefono') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-mail" style="font-size:13px"></i> Correo
                                </label>
                                <input type="email" wire:model="form_correo"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_correo') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-map-pin" style="font-size:13px"></i> Dirección
                            </label>
                            <input type="text" wire:model="form_direccion"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('form_direccion') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="button" wire:click="cerrarEditar"
                                class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="flex items-center gap-2 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-sky-300/40 transition hover:shadow-lg hover:shadow-sky-300/50">
                                <i class="ti ti-device-floppy" style="font-size:16px"></i>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cerrarAgregar">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200/60">

                <div class="relative bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2.5">
                        <i class="ti ti-building-store text-white/90" style="font-size:20px"></i>
                        <h2 class="text-lg font-semibold text-white">Agregar Proveedor</h2>
                    </div>
                    <button type="button" wire:click="cerrarAgregar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg text-white/70 transition hover:bg-white/20 hover:text-white">
                        <i class="ti ti-x" style="font-size:18px"></i>
                    </button>
                </div>

                <div class="max-h-[80vh] overflow-y-auto px-6 py-6">
                    <form wire:submit.prevent="guardarNuevoProveedor" class="space-y-5">

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-building" style="font-size:13px"></i> Razón social
                            </label>
                            <input type="text" wire:model="form_razon_social" placeholder="Ej. Mundo Dental S.A."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('form_razon_social') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-id-badge" style="font-size:13px"></i> NIT/RUC
                                </label>
                                <input type="text" wire:model="form_nit_ruc" placeholder="Ej. 1234567890"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-mono text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_nit_ruc') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-user" style="font-size:13px"></i> Nombre de contacto
                                </label>
                                <input type="text" wire:model="form_nombre_contacto" placeholder="Ej. Juan Pérez"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_nombre_contacto') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-device-mobile" style="font-size:13px"></i> Celular
                                </label>
                                <input type="text" wire:model="form_telefono" placeholder="Ej. +591 70000000"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_telefono') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-mail" style="font-size:13px"></i> Correo
                                </label>
                                <input type="email" wire:model="form_correo" placeholder="Ej. contacto@proveedor.com"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_correo') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-map-pin" style="font-size:13px"></i> Dirección
                            </label>
                            <input type="text" wire:model="form_direccion" placeholder="Ej. Av. Principal #123, Sucre"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('form_direccion') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-start gap-2.5 rounded-xl border border-sky-100 bg-sky-50 px-4 py-3">
                            <i class="ti ti-info-circle mt-0.5 shrink-0 text-sky-500" style="font-size:16px"></i>
                            <p class="text-xs leading-relaxed text-sky-700">
                                Una vez registrado, el proveedor estará disponible al crear órdenes de compra de suministros.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="button" wire:click="cerrarAgregar"
                                class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="flex items-center gap-2 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-sky-300/40 transition hover:shadow-lg hover:shadow-sky-300/50">
                                <i class="ti ti-device-floppy" style="font-size:16px"></i>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cancelarEliminar">
            <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white p-7 text-center shadow-2xl ring-1 ring-slate-200/60">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100">
                    <i class="ti ti-trash text-rose-500" style="font-size:28px"></i>
                </div>
                <h3 class="text-base font-semibold text-slate-800">¿Eliminar este proveedor?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                    Esta acción eliminará al proveedor de forma permanente.
                    Si tiene compras registradas, no podrá eliminarse.
                </p>

                <div class="mt-6 flex justify-center gap-3">
                    <button type="button" wire:click="cancelarEliminar"
                        class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="eliminarProveedor"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-300/40 transition hover:shadow-lg hover:shadow-rose-300/50">
                        <i class="ti ti-trash" style="font-size:15px"></i>
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>