<div class="min-h-screen p-6 md:p-8"
     style="background: linear-gradient(135deg, #eff8ff 0%, #f8fafc 50%, #fff7ed 100%);">

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
    {{-- VISTA 1: LISTA DE CARDS DE COMPRAS --}}
    {{-- ============================================================ --}}
    @if ($vista === 'lista')

        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 shadow-sm">
                    <i class="ti ti-shopping-cart text-orange-500" style="font-size:22px"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-semibold text-slate-800">Registro de Compras</h1>
                    <p class="text-xs text-slate-400 mt-0.5">/ inventario / compras /</p>
                </div>
            </div>

            <button
                type="button"
                wire:click="abrirNuevaCompra"
                class="group flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-300/40 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-sky-300/50 active:translate-y-0"
            >
                <i class="ti ti-plus transition-transform duration-200 group-hover:rotate-90" style="font-size:18px"></i>
                Nueva Compra
            </button>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->compras as $compra)
                <div
                    wire:key="compra-{{ $compra->id_compra }}"
                    class="flex flex-col overflow-hidden rounded-2xl border border-white bg-white shadow-md shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-slate-300/50"
                >
                    {{-- Cabecera de la card --}}
                    <div class="flex items-start justify-between gap-2 bg-gradient-to-br from-orange-50 to-amber-50/60 px-5 py-4 border-b border-orange-100/60">
                        <div class="min-w-0 flex-1">
                            <p class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                                <i class="ti ti-calendar text-orange-400" style="font-size:13px"></i>
                                Compra realizada el:
                            </p>
                            <p class="mt-0.5 text-sm font-semibold text-slate-800">
                                {{ $compra->fecha_compra?->translatedFormat('d \d\e F \d\e Y') }}
                            </p>
                            <p class="mt-1 flex items-center gap-1 text-xs text-slate-400">
                                <i class="ti ti-receipt text-slate-400" style="font-size:12px"></i>
                                {{ $compra->nro_factura ?: 'Sin número de factura' }}
                            </p>
                        </div>
                        <button
                            type="button"
                            wire:click="confirmarEliminar({{ $compra->id_compra }})"
                            class="shrink-0 flex h-8 w-8 items-center justify-center rounded-lg text-slate-300 transition-all duration-150 hover:bg-rose-50 hover:text-rose-500"
                            title="Eliminar compra"
                        >
                            <i class="ti ti-x" style="font-size:16px"></i>
                        </button>
                    </div>

                    <div class="flex flex-1 flex-col px-5 py-4">
                        {{-- Avatar + suministros --}}
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-sky-400 to-blue-500 text-sm font-bold text-white shadow-sm shadow-sky-300/40">
                                {{ mb_strtoupper(mb_substr($compra->proveedor?->razon_social ?? '?', 0, 2)) }}
                            </div>

                            <div class="flex-1 min-w-0 rounded-xl border border-slate-100 bg-slate-50/70 px-3.5 py-3">
                                <p class="mb-2 flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <i class="ti ti-packages text-slate-400" style="font-size:13px"></i>
                                    Suministros comprados:
                                </p>
                                <ul class="space-y-1.5">
                                    @forelse ($compra->detalles->take(4) as $det)
                                        <li class="flex items-center gap-1.5 border-b border-slate-100 pb-1.5 text-xs text-slate-500 last:border-b-0 last:pb-0">
                                            <i class="ti ti-point-filled text-sky-400 shrink-0" style="font-size:10px"></i>
                                            {{ $det->suministro?->nombre ?? 'Suministro eliminado' }}
                                        </li>
                                    @empty
                                        <li class="text-xs text-slate-400 italic">Sin suministros aún</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        {{-- Nombre proveedor --}}
                        <div class="mt-3 flex items-center justify-center gap-2">
                            <i class="ti ti-building-store text-slate-400" style="font-size:14px"></i>
                            <p class="text-sm font-medium text-slate-600">
                                {{ $compra->proveedor?->razon_social ?? 'Proveedor no disponible' }}
                            </p>
                        </div>

                        {{-- Total --}}
                        <div class="mt-3 rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50 to-orange-50 py-3 text-center">
                            <p class="text-xs font-medium text-amber-600">Costo Total de la Compra</p>
                            <p class="mt-0.5 text-lg font-bold text-slate-800">
                                Bs {{ number_format($compra->total_compra, 2) }}
                            </p>
                        </div>

                        {{-- Acción --}}
                        <button
                            type="button"
                            wire:click="verDetalle({{ $compra->id_compra }})"
                            class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-orange-400 to-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-orange-200/60 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:shadow-orange-300/50"
                        >
                            <i class="ti ti-eye" style="font-size:16px"></i>
                            Revisar compra
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-white/60 px-6 py-16 text-center">
                    <span class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                        <i class="ti ti-shopping-cart-off text-slate-400" style="font-size:32px"></i>
                    </span>
                    <p class="text-sm font-semibold text-slate-600">Aún no hay compras registradas</p>
                    <p class="mt-1.5 text-xs text-slate-400">Crea una nueva compra usando el botón de arriba.</p>
                </div>
            @endforelse
        </div>

    @endif


    {{-- ============================================================ --}}
    {{-- VISTA 2: DETALLE / TICKET DE LA COMPRA --}}
    {{-- ============================================================ --}}
    @if ($vista === 'detalle' && $this->compraActual)
        @php $compra = $this->compraActual; @endphp

        {{-- Encabezado detalle --}}
        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 shadow-sm">
                    <i class="ti ti-file-invoice text-orange-500" style="font-size:22px"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-semibold text-slate-800">Detalle de Compra</h1>
                    <p class="text-xs text-slate-400 mt-0.5">/ compras / {{ $compra->nro_factura ?: '#'.$compra->id_compra }}</p>
                </div>
            </div>

            <button
                type="button"
                wire:click="volverALista"
                class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:shadow-md"
            >
                <i class="ti ti-arrow-left" style="font-size:16px"></i>
                Volver a la lista
            </button>
        </div>

        {{-- Cabecera info de la compra --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-white bg-white shadow-md shadow-slate-200/50">
            <div class="bg-gradient-to-br from-sky-50 to-blue-50/60 border-b border-sky-100/60 px-5 py-3">
                <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-sky-600">
                    <i class="ti ti-info-circle" style="font-size:13px"></i>
                    Información de la compra
                </p>
            </div>
            <div class="flex items-center gap-4 px-5 py-5">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-sky-400 to-blue-500 text-base font-bold text-white shadow-sm shadow-sky-300/40">
                    {{ mb_strtoupper(mb_substr($compra->proveedor?->razon_social ?? '?', 0, 2)) }}
                </div>
                <div class="flex-1 grid grid-cols-1 gap-2 sm:grid-cols-3">
                    <div>
                        <p class="text-xs text-slate-400">Fecha de compra</p>
                        <p class="text-sm font-semibold text-slate-700">
                            {{ $compra->fecha_compra?->translatedFormat('d \d\e F \d\e Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Proveedor</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $compra->proveedor?->razon_social ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Realizada por</p>
                        <p class="text-sm font-semibold text-slate-700">
                            {{ $compra->asistente ? $compra->asistente->nombres . ' ' . $compra->asistente->apellidos : 'No especificado' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Título tabla --}}
        <div class="mb-4 flex items-center gap-2">
            <i class="ti ti-list-details text-slate-400" style="font-size:18px"></i>
            <h2 class="text-lg font-semibold text-slate-700">Suministros Comprados</h2>
        </div>

        {{-- Tabla de detalle --}}
        <div class="overflow-hidden rounded-2xl border border-white bg-white shadow-md shadow-slate-200/50">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-orange-500 to-amber-500 text-xs font-semibold uppercase tracking-wide text-white">
                            <th class="px-5 py-3.5 w-12">#</th>
                            <th class="px-5 py-3.5">
                                <span class="flex items-center gap-1.5">
                                    <i class="ti ti-package" style="font-size:13px"></i>
                                    Suministro
                                </span>
                            </th>
                            <th class="px-5 py-3.5">
                                <span class="flex items-center gap-1.5">
                                    <i class="ti ti-coin" style="font-size:13px"></i>
                                    Precio Unitario
                                </span>
                            </th>
                            <th class="px-5 py-3.5">
                                <span class="flex items-center gap-1.5">
                                    <i class="ti ti-stack" style="font-size:13px"></i>
                                    Cantidad
                                </span>
                            </th>
                            <th class="px-5 py-3.5">
                                <span class="flex items-center gap-1.5">
                                    <i class="ti ti-calculator" style="font-size:13px"></i>
                                    Subtotal
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($compra->detalles as $i => $det)
                            <tr wire:key="det-{{ $det->id_detalle_compra }}"
                                class="border-t border-slate-100 transition-colors duration-150 hover:bg-sky-50/40 {{ $i % 2 === 1 ? 'bg-slate-50/50' : '' }}">
                                <td class="px-5 py-3.5">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-500">
                                        {{ $i + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <i class="ti ti-package text-sky-400 shrink-0" style="font-size:15px"></i>
                                        <span class="text-sm font-medium text-slate-700">
                                            {{ $det->suministro?->nombre ?? 'Suministro eliminado' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-sm text-slate-600">Bs {{ number_format($det->precio_unitario, 2) }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                        {{ rtrim(rtrim(number_format($det->cantidad, 2), '0'), '.') }}
                                        {{ $det->suministro?->unidad_medida }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-sm font-bold text-slate-800">Bs {{ number_format($det->subtotal, 2) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">
                                    <i class="ti ti-package-off block mb-2" style="font-size:28px; opacity:.4"></i>
                                    No hay suministros registrados en esta compra.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-3 flex items-center justify-center gap-1.5 text-xs text-slate-400">
            <i class="ti ti-lock" style="font-size:13px"></i>
            Los suministros registrados no pueden editarse, ya que cada uno actualiza el inventario automáticamente al guardarse.
        </p>

        {{-- Total + botón agregar --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-white bg-white shadow-md shadow-slate-200/50">
            <div class="flex flex-col items-center gap-4 p-6 sm:flex-row sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100">
                        <i class="ti ti-currency-dollar text-amber-600" style="font-size:22px"></i>
                    </span>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Costo Total de la Compra</p>
                        <p class="text-xl font-bold text-slate-800">Bs {{ number_format($compra->total_compra, 2) }}</p>
                    </div>
                </div>

                <button
                    type="button"
                    wire:click="abrirAgregarSuministro"
                    class="group flex shrink-0 items-center gap-2 rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-300/40 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-sky-300/50"
                >
                    <i class="ti ti-plus transition-transform duration-200 group-hover:rotate-90" style="font-size:17px"></i>
                    Agregar nuevo suministro
                </button>
            </div>
        </div>

    @endif


    {{-- ============================================================ --}}
    {{-- MODAL: NUEVA COMPRA --}}
    {{-- ============================================================ --}}
    @if ($modalNuevaCompraVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cerrarNuevaCompra">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200/60">

                <div class="relative bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2.5">
                        <i class="ti ti-shopping-cart text-white/90" style="font-size:20px"></i>
                        <h2 class="text-lg font-semibold text-white">Nueva Compra</h2>
                    </div>
                    <button type="button" wire:click="cerrarNuevaCompra"
                        class="absolute right-4 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg text-white/70 transition hover:bg-white/20 hover:text-white">
                        <i class="ti ti-x" style="font-size:18px"></i>
                    </button>
                </div>

                <div class="px-6 py-6">
                    <form wire:submit.prevent="guardarNuevaCompra" class="space-y-5">

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-building-store" style="font-size:13px"></i> Proveedor
                            </label>
                            <div class="relative">
                                <select wire:model="form_id_proveedor"
                                    class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 pr-10 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                    <option value="">Seleccionar proveedor...</option>
                                    @foreach ($this->proveedores as $p)
                                        <option value="{{ $p->id_proveedor }}">{{ $p->razon_social }}</option>
                                    @endforeach
                                </select>
                                <i class="ti ti-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:16px"></i>
                            </div>
                            @error('form_id_proveedor') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-user" style="font-size:13px"></i> Compra realizada por (Asistente)
                            </label>
                            <div class="relative">
                                <select wire:model="form_id_asistente"
                                    class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 pr-10 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                    <option value="">Sin especificar</option>
                                    @foreach ($this->asistentes as $a)
                                        <option value="{{ $a->id_asistente }}">{{ $a->nombres }} {{ $a->apellidos }}</option>
                                    @endforeach
                                </select>
                                <i class="ti ti-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:16px"></i>
                            </div>
                            @error('form_id_asistente') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-receipt" style="font-size:13px"></i> N° de factura
                                </label>
                                <input type="text" wire:model="form_nro_factura" placeholder="Ej. ticket-0010"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_nro_factura') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-calendar" style="font-size:13px"></i> Fecha de compra
                                </label>
                                <input type="date" wire:model="form_fecha_compra"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_fecha_compra') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 rounded-xl border border-sky-100 bg-sky-50 px-4 py-3">
                            <i class="ti ti-info-circle mt-0.5 shrink-0 text-sky-500" style="font-size:16px"></i>
                            <p class="text-xs leading-relaxed text-sky-700">
                                Después de crear la compra podrás agregar los suministros adquiridos y sus cantidades.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="button" wire:click="cerrarNuevaCompra"
                                class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="flex items-center gap-2 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-sky-300/40 transition hover:shadow-lg hover:shadow-sky-300/50">
                                <i class="ti ti-check" style="font-size:16px"></i>
                                Crear compra
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


    {{-- ============================================================ --}}
    {{-- MODAL: AGREGAR SUMINISTRO A LA COMPRA --}}
    {{-- ============================================================ --}}
    @if ($modalAgregarSuministroVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cerrarAgregarSuministro">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200/60">

                <div class="relative bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2.5">
                        <i class="ti ti-package text-white/90" style="font-size:20px"></i>
                        <h2 class="text-lg font-semibold text-white">Agregar suministro a la compra</h2>
                    </div>
                    <button type="button" wire:click="cerrarAgregarSuministro"
                        class="absolute right-4 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg text-white/70 transition hover:bg-white/20 hover:text-white">
                        <i class="ti ti-x" style="font-size:18px"></i>
                    </button>
                </div>

                <div class="px-6 py-6">
                    <form wire:submit.prevent="guardarNuevoDetalle" class="space-y-5">

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-package" style="font-size:13px"></i> Suministro
                            </label>
                            <div class="relative">
                                <select wire:model="nuevo_id_suministro"
                                    class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 pr-10 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                    <option value="">Seleccionar suministro...</option>
                                    @foreach ($this->suministrosDisponibles as $s)
                                        <option value="{{ $s->id_suministro }}">
                                            {{ $s->nombre }} @if($s->codigo_barras) ({{ $s->codigo_barras }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <i class="ti ti-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:16px"></i>
                            </div>
                            @error('nuevo_id_suministro') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-coin" style="font-size:13px"></i> Precio unitario (Bs)
                                </label>
                                <input type="number" step="0.01" min="0.01" wire:model="nuevo_det_precio_unitario"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('nuevo_det_precio_unitario') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-stack" style="font-size:13px"></i> Cantidad
                                </label>
                                <input type="number" step="0.01" min="0.01" wire:model="nuevo_det_cantidad"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('nuevo_det_cantidad') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 rounded-xl border border-amber-100 bg-amber-50 px-4 py-3">
                            <i class="ti ti-alert-circle mt-0.5 shrink-0 text-amber-500" style="font-size:16px"></i>
                            <p class="text-xs leading-relaxed text-amber-700">
                                Al guardar, la cantidad indicada se sumará automáticamente al stock del suministro. Esta acción no puede editarse ni revertirse.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="button" wire:click="cerrarAgregarSuministro"
                                class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="flex items-center gap-2 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-sky-300/40 transition hover:shadow-lg hover:shadow-sky-300/50">
                                <i class="ti ti-plus" style="font-size:16px"></i>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cancelarEliminar">
            <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white p-7 text-center shadow-2xl ring-1 ring-slate-200/60">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100">
                    <i class="ti ti-trash text-rose-500" style="font-size:28px"></i>
                </div>
                <h3 class="text-base font-semibold text-slate-800">¿Eliminar esta compra?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                    Se eliminará la compra y todos sus suministros registrados.
                    El stock que ya se sumó al inventario no se revertirá automáticamente.
                </p>

                <div class="mt-6 flex justify-center gap-3">
                    <button type="button" wire:click="cancelarEliminar"
                        class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="eliminarCompra"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-300/40 transition hover:shadow-lg hover:shadow-rose-300/50">
                        <i class="ti ti-trash" style="font-size:15px"></i>
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>