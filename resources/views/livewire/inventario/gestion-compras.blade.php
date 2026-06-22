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
    {{-- VISTA 1: LISTA DE CARDS DE COMPRAS --}}
    {{-- ============================================================ --}}
    @if ($vista === 'lista')
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Registro de Compras</h1>
                <p class="text-sm text-slate-400">/ compra /</p>
            </div>

            <button
                type="button"
                wire:click="abrirNuevaCompra"
                class="flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nueva Compra
            </button>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->compras as $compra)
                <div
                    wire:key="compra-{{ $compra->id_compra }}"
                    class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm text-slate-500">
                            Compra realizada el:
                            <span class="font-semibold text-slate-700">
                                {{ $compra->fecha_compra?->translatedFormat('d \d\e F \d\e Y') }}
                            </span>
                        </p>
                        <button
                            type="button"
                            wire:click="confirmarEliminar({{ $compra->id_compra }})"
                            class="shrink-0 rounded-md p-1 text-slate-300 transition hover:bg-rose-50 hover:text-rose-500"
                            title="Eliminar compra"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <p class="mt-0.5 text-xs text-slate-400">
                        {{ $compra->nro_factura ?: 'Sin número de factura' }}
                    </p>

                    <div class="mt-4 flex items-start gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full border border-slate-100 bg-sky-50 text-sm font-bold text-sky-600">
                            {{ mb_substr($compra->proveedor?->razon_social ?? '?', 0, 2) }}
                        </div>

                        <div class="flex-1 rounded-xl bg-slate-50 px-4 py-3">
                            <p class="mb-1.5 text-xs font-semibold text-slate-600">Suministros comprados:</p>
                            <ul class="space-y-1">
                                @forelse ($compra->detalles->take(4) as $det)
                                    <li class="border-b border-slate-200 pb-1 text-xs text-slate-500 last:border-b-0">
                                        {{ $det->suministro?->nombre ?? 'Suministro eliminado' }}
                                    </li>
                                @empty
                                    <li class="text-xs text-slate-400">Sin suministros aún</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <p class="mt-3 text-center text-sm font-medium text-slate-600">
                        {{ $compra->proveedor?->razon_social ?? 'Proveedor no disponible' }}
                    </p>

                    <div class="mt-3 rounded-lg bg-slate-50 py-2 text-center">
                        <p class="text-xs text-slate-500">Costo de Compra Total:</p>
                        <p class="text-base font-semibold text-slate-800">Bs {{ number_format($compra->total_compra, 2) }}</p>
                    </div>

                    <button
                        type="button"
                        wire:click="verDetalle({{ $compra->id_compra }})"
                        class="mt-4 flex items-center justify-center gap-2 rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Revisar
                    </button>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <p class="text-sm font-medium text-slate-600">Aún no hay compras registradas</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- VISTA 2: DETALLE / TICKET DE LA COMPRA --}}
    {{-- ============================================================ --}}
    @if ($vista === 'detalle' && $this->compraActual)
        @php $compra = $this->compraActual; @endphp

        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Registro de Compra de suministros</h1>
                <p class="text-sm text-slate-400">/ compra / {{ $compra->nro_factura ?: $compra->id_compra }}</p>
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

        {{-- Cabecera de la compra (solo lectura: proveedor/asistente se fijan al crear) --}}
        <div class="mb-6 flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full border border-slate-100 bg-sky-50 text-base font-bold text-sky-600">
                {{ mb_substr($compra->proveedor?->razon_social ?? '?', 0, 2) }}
            </div>

            <div class="flex-1">
                <p class="text-sm text-slate-500">
                    El {{ $compra->fecha_compra?->translatedFormat('d \d\e F \d\e Y') }}
                </p>
                <p class="text-sm text-slate-600">
                    Se realizó la compra en:
                    <span class="font-semibold text-slate-800">{{ $compra->proveedor?->razon_social ?? '—' }}</span>
                </p>
                <p class="text-sm text-slate-500">
                    Compra realizada por:
                    {{ $compra->asistente ? $compra->asistente->nombres . ' ' . $compra->asistente->apellidos : 'No especificado' }}
                </p>
            </div>
        </div>

        <h2 class="mb-4 text-center text-xl font-semibold text-slate-700">Suministros Comprados</h2>

        {{-- Tabla de detalle (solo lectura: agregar es la única acción permitida) --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left">
                    <thead>
                        <tr class="bg-orange-500 text-xs font-semibold uppercase tracking-wide text-white">
                            <th class="px-4 py-3 w-12">#</th>
                            <th class="px-4 py-3">Suministro</th>
                            <th class="px-4 py-3">Precio Unitario</th>
                            <th class="px-4 py-3">Cantidad</th>
                            <th class="px-4 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($compra->detalles as $i => $det)
                            <tr wire:key="det-{{ $det->id_detalle_compra }}" class="border-t border-slate-100 {{ $i % 2 === 1 ? 'bg-slate-50/60' : '' }}">
                                <td class="px-4 py-3 text-sm text-slate-500">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700">
                                    {{ $det->suministro?->nombre ?? 'Suministro eliminado' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">Bs {{ number_format($det->precio_unitario, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ rtrim(rtrim(number_format($det->cantidad, 2), '0'), '.') }}
                                    {{ $det->suministro?->unidad_medida }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-700">Bs {{ number_format($det->subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-400">
                                    No hay suministros registrados en esta compra.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-3 text-center text-xs text-slate-400">
            Los suministros registrados no pueden editarse ni eliminarse, ya que cada uno actualiza
            automáticamente el inventario al momento de guardarse.
        </p>

        {{-- Total + agregar --}}
        <div class="mt-6 flex flex-col items-center gap-5 rounded-2xl border border-slate-200 bg-white p-6 sm:flex-row sm:justify-between">
            <div class="text-center sm:text-left">
                <p class="text-sm text-slate-500">Costo Total de la Compra:</p>
                <p class="text-lg font-semibold text-slate-800">Bs {{ number_format($compra->total_compra, 2) }}</p>
            </div>

            <button
                type="button"
                wire:click="abrirAgregarSuministro"
                class="flex shrink-0 items-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Agregar nuevo suministro
            </button>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- MODAL: NUEVA COMPRA (cabecera) --}}
    {{-- ============================================================ --}}
    @if ($modalNuevaCompraVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cerrarNuevaCompra">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl">
                <div class="relative bg-sky-600 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">Nueva Compra</h2>
                    <button
                        type="button"
                        wire:click="cerrarNuevaCompra"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 transition hover:text-white"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5">
                    <form wire:submit.prevent="guardarNuevaCompra" class="space-y-5">

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Proveedor</label>
                            <div class="relative">
                                <select
                                    wire:model="form_id_proveedor"
                                    class="w-full appearance-none rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                    <option value="">Seleccionar proveedor...</option>
                                    @foreach ($this->proveedores as $p)
                                        <option value="{{ $p->id_proveedor }}">{{ $p->razon_social }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('form_id_proveedor') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Compra realizada por (Asistente)</label>
                            <div class="relative">
                                <select
                                    wire:model="form_id_asistente"
                                    class="w-full appearance-none rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                    <option value="">Sin especificar</option>
                                    @foreach ($this->asistentes as $a)
                                        <option value="{{ $a->id_asistente }}">{{ $a->nombres }} {{ $a->apellidos }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('form_id_asistente') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">N° de factura</label>
                                <input
                                    type="text"
                                    wire:model="form_nro_factura"
                                    placeholder="Ej. ticket-0010"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_nro_factura') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Fecha de compra</label>
                                <input
                                    type="date"
                                    wire:model="form_fecha_compra"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_fecha_compra') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <p class="text-xs leading-relaxed text-slate-400">
                            Después de crear la compra podrás agregar los suministros adquiridos.
                        </p>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" wire:click="cerrarNuevaCompra" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit" class="rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                                Crear compra
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- MODAL: AGREGAR NUEVO SUMINISTRO A LA COMPRA --}}
    {{-- ============================================================ --}}
    @if ($modalAgregarSuministroVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cerrarAgregarSuministro">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl">
                <div class="relative bg-sky-600 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">Agregar suministro a la compra</h2>
                    <button
                        type="button"
                        wire:click="cerrarAgregarSuministro"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 transition hover:text-white"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5">
                    <form wire:submit.prevent="guardarNuevoDetalle" class="space-y-5">

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Suministro</label>
                            <div class="relative">
                                <select
                                    wire:model="nuevo_id_suministro"
                                    class="w-full appearance-none rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                    <option value="">Seleccionar suministro...</option>
                                    @foreach ($this->suministrosDisponibles as $s)
                                        <option value="{{ $s->id_suministro }}">
                                            {{ $s->nombre }} @if($s->codigo_barras) ({{ $s->codigo_barras }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('nuevo_id_suministro') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Precio unitario (Bs)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    wire:model="nuevo_det_precio_unitario"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('nuevo_det_precio_unitario') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Cantidad</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    wire:model="nuevo_det_cantidad"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('nuevo_det_cantidad') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <p class="text-xs leading-relaxed text-slate-400">
                            Al guardar, la cantidad indicada se sumará automáticamente al stock del suministro
                            en el almacén. Esta acción no puede editarse ni revertirse después.
                        </p>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" wire:click="cerrarAgregarSuministro" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit" class="rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                                Agregar a la compra
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- MODAL: CONFIRMAR ELIMINACIÓN DE COMPRA --}}
    {{-- ============================================================ --}}
    @if ($modalConfirmarEliminarVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cancelarEliminar">
            <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white p-6 text-center shadow-xl">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">¿Eliminar esta compra?</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Se eliminará la compra y todos sus suministros registrados. El stock que ya se sumó
                    al inventario no se revertirá automáticamente.
                </p>

                <div class="mt-5 flex justify-center gap-3">
                    <button type="button" wire:click="cancelarEliminar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="eliminarCompra" class="rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>