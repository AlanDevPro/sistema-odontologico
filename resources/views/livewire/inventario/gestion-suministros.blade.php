<div class="min-h-screen p-6 md:p-8"
     style="background: linear-gradient(135deg, #eff8ff 0%, #f8fafc 50%, #f0fdf4 100%);">

    {{-- ============================================================ --}}
    {{-- MENSAJES FLASH --}}
    {{-- ============================================================ --}}
    @if (session('mensaje'))
        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-emerald-800 shadow-sm shadow-emerald-100/60 animate-fade-down">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <i class="ti ti-circle-check text-emerald-600" style="font-size:18px"></i>
            </span>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-rose-800 shadow-sm shadow-rose-100/60 animate-fade-down">
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
        <div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100">
                    <i class="ti ti-package text-sky-600" style="font-size:20px"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-semibold text-slate-800">Gestión de Suministros</h1>
                    <p class="text-xs text-slate-400 mt-0.5">/ inventario / suministros /</p>
                </div>
            </div>
        </div>

        <button
            type="button"
            wire:click="abrirAgregar"
            class="group flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-300/40 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-sky-300/50 active:translate-y-0"
        >
            <i class="ti ti-plus transition-transform duration-200 group-hover:rotate-90" style="font-size:18px"></i>
            Agregar Suministro
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- BUSCADOR --}}
    {{-- ============================================================ --}}
    <div class="mb-4 rounded-2xl border border-white/80 bg-white p-5 shadow-lg shadow-slate-200/60 ring-1 ring-slate-100">
        <p class="mb-3.5 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
            <i class="ti ti-search text-sky-400" style="font-size:15px"></i>
            Buscar suministro
        </p>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div class="relative">
                <i class="ti ti-tag absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:16px"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="buscarNombre"
                    placeholder="Nombre del suministro"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder:text-slate-400 transition-all duration-200 focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100"
                >
            </div>
            <div class="relative">
                <i class="ti ti-barcode absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:16px"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="buscarCodigoBarras"
                    placeholder="Código de barras"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder:text-slate-400 transition-all duration-200 focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100"
                >
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FILTROS --}}
    {{-- ============================================================ --}}
    <div class="mb-7 rounded-2xl border border-white/80 bg-white p-5 shadow-lg shadow-slate-200/60 ring-1 ring-slate-100">
        <p class="mb-3.5 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
            <i class="ti ti-filter text-sky-400" style="font-size:15px"></i>
            Filtrar por stock
        </p>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <button
                type="button"
                wire:click="aplicarFiltro('todos')"
                class="flex items-center justify-center gap-2.5 rounded-xl border px-4 py-3 text-sm font-medium transition-all duration-200
                    {{ $filtroStock === 'todos'
                        ? 'border-sky-300 bg-gradient-to-br from-sky-50 to-blue-50 text-sky-700 shadow-sm shadow-sky-100'
                        : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-white hover:border-slate-300 hover:shadow-sm' }}"
            >
                <i class="ti ti-layout-list" style="font-size:16px"></i>
                Todos los suministros
            </button>
            <div class="grid grid-cols-2 gap-2.5">
                <button
                    type="button"
                    wire:click="aplicarFiltro('con_stock')"
                    class="flex items-center justify-center gap-2 rounded-xl border px-3 py-3 text-xs font-medium transition-all duration-200
                        {{ $filtroStock === 'con_stock'
                            ? 'border-emerald-300 bg-gradient-to-br from-emerald-50 to-green-50 text-emerald-700 shadow-sm shadow-emerald-100'
                            : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-white hover:border-emerald-200 hover:text-emerald-600 hover:shadow-sm' }}"
                >
                    <i class="ti ti-circle-check" style="font-size:15px"></i>
                    Con stock
                </button>
                <button
                    type="button"
                    wire:click="aplicarFiltro('sin_stock')"
                    class="flex items-center justify-center gap-2 rounded-xl border px-3 py-3 text-xs font-medium transition-all duration-200
                        {{ $filtroStock === 'sin_stock'
                            ? 'border-rose-300 bg-gradient-to-br from-rose-50 to-pink-50 text-rose-700 shadow-sm shadow-rose-100'
                            : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-white hover:border-rose-200 hover:text-rose-600 hover:shadow-sm' }}"
                >
                    <i class="ti ti-circle-x" style="font-size:15px"></i>
                    Sin stock
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- GRID DE CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($this->suministrosFiltrados as $s)
            <div
                wire:key="suministro-{{ $s->id_suministro }}"
                class="group flex flex-col overflow-hidden rounded-2xl border bg-white shadow-md shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-slate-300/50
                    {{ $s->stock_bajo ? 'border-rose-200' : 'border-white' }}"
            >
                {{-- Cabecera de la card --}}
                <div class="flex items-start justify-between gap-3 px-5 py-4
                    {{ $s->stock_bajo
                        ? 'bg-gradient-to-br from-rose-50 to-orange-50 border-b border-rose-100'
                        : 'bg-gradient-to-br from-sky-50 to-blue-50/50 border-b border-sky-100/60' }}">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                                {{ $s->stock_bajo ? 'bg-rose-100' : 'bg-sky-100' }}">
                                <i class="ti ti-package {{ $s->stock_bajo ? 'text-rose-500' : 'text-sky-500' }}" style="font-size:16px"></i>
                            </span>
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-semibold text-slate-800">{{ $s->nombre }}</h3>
                                <p class="flex items-center gap-1 text-xs text-slate-400 mt-0.5">
                                    <i class="ti ti-folder" style="font-size:11px"></i>
                                    {{ $s->categoria ?: 'Sin categoría' }}
                                </p>
                            </div>
                        </div>
                        @if ($s->stock_bajo)
                            <span class="mt-2 inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-100 px-2.5 py-0.5 text-[10px] font-semibold text-rose-700">
                                <i class="ti ti-alert-triangle" style="font-size:10px"></i>
                                Stock bajo
                            </span>
                        @endif
                    </div>
                    <button
                        type="button"
                        wire:click="abrirModificar({{ $s->id_suministro }})"
                        class="shrink-0 rounded-xl p-2 text-slate-400 transition-all duration-150 hover:bg-white hover:text-sky-600 hover:shadow-sm"
                        title="Modificar suministro"
                    >
                        <i class="ti ti-edit" style="font-size:17px"></i>
                    </button>
                </div>

                <div class="flex flex-1 flex-col px-5 py-4">
                    {{-- Meta info --}}
                    <div class="space-y-2">
                        <p class="flex items-center gap-2 text-xs text-slate-500">
                            <i class="ti ti-barcode text-slate-400" style="font-size:14px"></i>
                            <span class="font-medium text-slate-600">Código:</span>
                            <span class="font-mono">{{ $s->codigo_barras ?: '—' }}</span>
                        </p>
                        <p class="flex items-center gap-2 text-xs text-slate-500">
                            <i class="ti ti-ruler text-slate-400" style="font-size:14px"></i>
                            <span class="font-medium text-slate-600">Unidad:</span>
                            {{ $s->unidad_medida ?: '—' }}
                        </p>
                    </div>

                    {{-- Botón stock --}}
                    <button
                        type="button"
                        wire:click="abrirStock({{ $s->id_suministro }})"
                        class="mt-4 flex items-center justify-between gap-2 rounded-xl border px-3.5 py-2.5 text-xs font-semibold transition-all duration-200
                            {{ $s->stock_bajo
                                ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:border-rose-300 hover:shadow-sm hover:shadow-rose-100'
                                : 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 hover:border-amber-300 hover:shadow-sm hover:shadow-amber-100' }}"
                        title="Ver detalle de stock"
                    >
                        <span class="flex items-center gap-1.5">
                            <i class="ti ti-packages" style="font-size:14px"></i>
                            Stock: {{ rtrim(rtrim(number_format($s->stock_actual, 2), '0'), '.') }}
                            {{ $s->unidad_medida ?: 'unidad' }}{{ $s->stock_actual == 1 ? '' : 's' }}
                        </span>
                        <i class="ti ti-eye opacity-70" style="font-size:14px"></i>
                    </button>

                    @if ($s->stock_bajo)
                        <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-rose-500">
                            <i class="ti ti-alert-triangle" style="font-size:13px"></i>
                            Mínimo requerido: {{ $s->stock_minimo }} {{ $s->unidad_medida ?: 'unidad' }}s
                        </p>
                    @endif

                    {{-- Acción eliminar --}}
                    <div class="mt-auto pt-4">
                        <button
                            type="button"
                            wire:click="confirmarEliminar({{ $s->id_suministro }})"
                            class="flex w-full items-center justify-center gap-2 rounded-xl border border-transparent bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-500 transition-all duration-200 hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"
                            title="Eliminar suministro"
                        >
                            <i class="ti ti-trash" style="font-size:14px"></i>
                            Eliminar suministro
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-white/60 px-6 py-16 text-center backdrop-blur-sm">
                <span class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                    <i class="ti ti-package-off text-slate-400" style="font-size:32px"></i>
                </span>
                <p class="text-sm font-semibold text-slate-600">No se encontraron suministros</p>
                <p class="mt-1.5 text-xs text-slate-400">Ajusta la búsqueda o el filtro aplicado.</p>
            </div>
        @endforelse
    </div>


    {{-- ============================================================ --}}
    {{-- MODAL: MODIFICAR SUMINISTRO --}}
    {{-- ============================================================ --}}
    @if ($modalModificarVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cerrarModificar">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200/60 animate-fade-up">

                <div class="relative bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2.5">
                        <i class="ti ti-edit text-white/90" style="font-size:20px"></i>
                        <h2 class="text-lg font-semibold text-white">Modificar Suministro</h2>
                    </div>
                    <button
                        type="button"
                        wire:click="cerrarModificar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg text-white/70 transition hover:bg-white/20 hover:text-white"
                    >
                        <i class="ti ti-x" style="font-size:18px"></i>
                    </button>
                </div>

                <div class="max-h-[80vh] overflow-y-auto px-6 py-6">
                    <form wire:submit.prevent="guardarModificar" class="space-y-5">

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-tag" style="font-size:13px"></i> Nombre
                            </label>
                            <input type="text" wire:model="form_nombre"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('form_nombre') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-folder" style="font-size:13px"></i> Categoría
                            </label>
                            <input type="text" wire:model="form_categoria"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('form_categoria') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-barcode" style="font-size:13px"></i> Código de barras
                            </label>
                            <input type="text" wire:model="form_codigo_barras"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-mono text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('form_codigo_barras') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-ruler" style="font-size:13px"></i> Unidad de medida
                                </label>
                                <input type="text" wire:model="form_unidad_medida" placeholder="unidad"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_unidad_medida') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-trending-down" style="font-size:13px"></i> Stock mínimo
                                </label>
                                <input type="number" min="0" wire:model="form_stock_minimo"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('form_stock_minimo') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="button" wire:click="cerrarModificar"
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
    {{-- MODAL: STOCK DE SUMINISTRO (solo lectura) --}}
    {{-- ============================================================ --}}
    @if ($modalStockVisible && $this->suministroActual)
        @php $s = $this->suministroActual; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cerrarStock">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200/60 animate-fade-up">

                <div class="relative bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2.5">
                        <i class="ti ti-packages text-white/90" style="font-size:20px"></i>
                        <h2 class="text-lg font-semibold text-white">Stock de Suministro</h2>
                    </div>
                    <button type="button" wire:click="cerrarStock"
                        class="absolute right-4 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg text-white/70 transition hover:bg-white/20 hover:text-white">
                        <i class="ti ti-x" style="font-size:18px"></i>
                    </button>
                </div>

                <div class="px-6 py-6">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100">
                            <i class="ti ti-package text-sky-500" style="font-size:20px"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $s->nombre }}</p>
                            <p class="text-xs text-slate-400">{{ $s->categoria ?: 'Sin categoría' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-2xl border {{ $s->stock_bajo ? 'border-rose-200 bg-rose-50' : 'border-sky-100 bg-sky-50' }} p-4 text-center">
                            <p class="text-xs font-medium text-slate-500 mb-1">Stock actual</p>
                            <p class="text-3xl font-bold {{ $s->stock_bajo ? 'text-rose-600' : 'text-sky-600' }}">
                                {{ rtrim(rtrim(number_format($s->stock_actual, 2), '0'), '.') }}
                            </p>
                            <p class="text-xs text-slate-400 mt-1">{{ $s->unidad_medida ?: 'unidad' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-medium text-slate-500 mb-1">Stock mínimo</p>
                            <p class="text-3xl font-bold text-slate-700">{{ $s->stock_minimo }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $s->unidad_medida ?: 'unidad' }}</p>
                        </div>
                    </div>

                    @if ($s->stock_bajo)
                        <div class="mt-4 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-medium text-rose-700">
                            <i class="ti ti-alert-triangle mt-0.5 shrink-0" style="font-size:16px"></i>
                            <span>Este suministro está por debajo del stock mínimo. Se recomienda generar una orden de compra.</span>
                        </div>
                    @endif

                    @if ($s->inventario?->ultima_actualizacion)
                        <p class="mt-4 flex items-center gap-1.5 text-xs text-slate-400">
                            <i class="ti ti-clock" style="font-size:13px"></i>
                            Última actualización: {{ $s->inventario->ultima_actualizacion->format('d/m/Y H:i') }}
                        </p>
                    @endif

                    <p class="mt-3 flex items-start gap-1.5 text-xs leading-relaxed text-slate-400">
                        <i class="ti ti-info-circle mt-0.5 shrink-0" style="font-size:13px"></i>
                        El stock se actualiza automáticamente al registrar una compra del proveedor.
                    </p>

                    <div class="mt-5 flex justify-end">
                        <button type="button" wire:click="cerrarStock"
                            class="rounded-xl bg-slate-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 shadow-md shadow-slate-300/40">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- ============================================================ --}}
    {{-- MODAL: AGREGAR SUMINISTRO --}}
    {{-- ============================================================ --}}
    @if ($modalAgregarVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
             wire:click.self="cerrarAgregar">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200/60 animate-fade-up">

                <div class="relative bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2.5">
                        <i class="ti ti-plus text-white/90" style="font-size:20px"></i>
                        <h2 class="text-lg font-semibold text-white">Agregar Suministro</h2>
                    </div>
                    <button type="button" wire:click="cerrarAgregar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg text-white/70 transition hover:bg-white/20 hover:text-white">
                        <i class="ti ti-x" style="font-size:18px"></i>
                    </button>
                </div>

                <div class="max-h-[80vh] overflow-y-auto px-6 py-6">
                    <form wire:submit.prevent="guardarNuevoSuministro" class="space-y-5">

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-tag" style="font-size:13px"></i> Nombre
                            </label>
                            <input type="text" wire:model="nuevo_nombre" placeholder="Ej. Espejo bucal N°5"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('nuevo_nombre') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-folder" style="font-size:13px"></i> Categoría
                            </label>
                            <input type="text" wire:model="nuevo_categoria" placeholder="Ej. Instrumento · Endodoncia"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('nuevo_categoria') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <i class="ti ti-barcode" style="font-size:13px"></i> Código de barras
                            </label>
                            <input type="text" wire:model="nuevo_codigo_barras" placeholder="Ej. 7501234560011"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-mono text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                            @error('nuevo_codigo_barras') <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-500"><i class="ti ti-alert-circle" style="font-size:12px"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-ruler" style="font-size:13px"></i> Unidad de medida
                                </label>
                                <input type="text" wire:model="nuevo_unidad_medida" placeholder="unidad"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('nuevo_unidad_medida') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <i class="ti ti-trending-down" style="font-size:13px"></i> Stock mínimo
                                </label>
                                <input type="number" min="0" wire:model="nuevo_stock_minimo"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition-all focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-sky-100">
                                @error('nuevo_stock_minimo') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 rounded-xl border border-sky-100 bg-sky-50 px-4 py-3">
                            <i class="ti ti-info-circle mt-0.5 shrink-0 text-sky-500" style="font-size:16px"></i>
                            <p class="text-xs leading-relaxed text-sky-700">
                                El stock inicial se registrará automáticamente al realizar la primera compra de este suministro.
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
                                Guardar suministro
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
            <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white p-7 text-center shadow-2xl ring-1 ring-slate-200/60 animate-fade-up">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100">
                    <i class="ti ti-trash text-rose-500" style="font-size:28px"></i>
                </div>
                <h3 class="text-base font-semibold text-slate-800">¿Eliminar este suministro?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Esta acción quitará al suministro de la lista de inventario activo. Esta operación no se puede deshacer.</p>

                <div class="mt-6 flex justify-center gap-3">
                    <button type="button" wire:click="cancelarEliminar"
                        class="rounded-xl px-5 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100 border border-slate-200">
                        Cancelar
                    </button>
                    <button type="button" wire:click="eliminarSuministro"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-300/40 transition hover:shadow-lg hover:shadow-rose-300/50">
                        <i class="ti ti-trash" style="font-size:15px"></i>
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>