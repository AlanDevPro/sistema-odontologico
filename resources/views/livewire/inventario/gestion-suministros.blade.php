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
    {{-- ENCABEZADO --}}
    {{-- ============================================================ --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Lista Suministros</h1>
            <p class="text-sm text-slate-400">/ suministros /</p>
        </div>

        <button
            type="button"
            wire:click="abrirAgregar"
            class="flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Agregar Suministro
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- BUSCADOR --}}
    {{-- ============================================================ --}}
    <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-5">
        <p class="mb-3 text-sm font-semibold text-slate-700">Buscar:</p>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <input
                type="text"
                wire:model.live.debounce.400ms="buscarNombre"
                placeholder="Nombre"
                class="w-full rounded-lg border border-amber-300/70 px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
            >
            <input
                type="text"
                wire:model.live.debounce.400ms="buscarCodigoBarras"
                placeholder="Código de barras"
                class="w-full rounded-lg border border-amber-300/70 px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
            >
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FILTROS --}}
    {{-- ============================================================ --}}
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5">
        <p class="mb-3 text-sm font-semibold text-slate-700">Filtro:</p>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <button
                type="button"
                wire:click="aplicarFiltro('todos')"
                class="flex items-center justify-center gap-2 rounded-lg border px-4 py-2.5 text-sm font-medium transition
                    {{ $filtroStock === 'todos' ? 'border-sky-400 bg-sky-50 text-sky-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
                Todos los suministros
            </button>
            <div class="grid grid-cols-2 gap-2">
                <button
                    type="button"
                    wire:click="aplicarFiltro('con_stock')"
                    class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2.5 text-xs font-medium transition
                        {{ $filtroStock === 'con_stock' ? 'border-emerald-400 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}"
                >
                    Con stock
                </button>
                <button
                    type="button"
                    wire:click="aplicarFiltro('sin_stock')"
                    class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2.5 text-xs font-medium transition
                        {{ $filtroStock === 'sin_stock' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}"
                >
                    Sin stock
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- GRID DE CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($this->suministrosFiltrados as $s)
            <div
                wire:key="suministro-{{ $s->id_suministro }}"
                class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
            >
                {{-- Cabecera --}}
                <div class="flex items-start justify-between gap-3 bg-sky-50/70 px-5 py-4">
                    <div>
                        <h3 class="text-sm font-semibold leading-snug text-slate-800">{{ $s->nombre }}</h3>
                        <p class="mt-0.5 text-xs text-slate-400">{{ $s->categoria ?: 'Sin categoría' }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="abrirModificar({{ $s->id_suministro }})"
                        class="shrink-0 rounded-md p-1 text-slate-400 transition hover:bg-white hover:text-slate-600"
                        title="Modificar suministro"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-1 flex-col px-5 py-4">
                    {{-- Código de barras --}}
                    <p class="text-xs text-slate-500">
                        <span class="font-semibold text-slate-600">Código de barras:</span>
                        {{ $s->codigo_barras ?: '—' }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">
                        <span class="font-semibold text-slate-600">Unidad de medida:</span>
                        {{ $s->unidad_medida ?: '—' }}
                    </p>

                    {{-- Botón de stock (solo lectura) --}}
                    <button
                        type="button"
                        wire:click="abrirStock({{ $s->id_suministro }})"
                        class="mt-3 flex items-center justify-between gap-2 rounded-lg border px-3 py-2 text-xs font-semibold transition
                            {{ $s->stock_bajo ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' : 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' }}"
                        title="Ver stock"
                    >
                        <span>
                            Stock: {{ rtrim(rtrim(number_format($s->stock_actual, 2), '0'), '.') }}
                            {{ $s->unidad_medida ?: 'unidad' }}{{ $s->stock_actual == 1 ? '' : 's' }}
                        </span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    @if ($s->stock_bajo)
                        <p class="mt-2 text-xs font-medium text-rose-500">
                            ⚠ Por debajo del stock mínimo ({{ $s->stock_minimo }})
                        </p>
                    @endif

                    {{-- Acciones --}}
                    <div class="mt-auto pt-4">
                        <button
                            type="button"
                            wire:click="confirmarEliminar({{ $s->id_suministro }})"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-100"
                            title="Eliminar suministro"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <svg class="mb-3 h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
                <p class="text-sm font-medium text-slate-600">No se encontraron suministros</p>
                <p class="mt-1 text-xs text-slate-400">Ajusta la búsqueda o el filtro aplicado.</p>
            </div>
        @endforelse
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: MODIFICAR SUMINISTRO --}}
    {{-- ============================================================ --}}
    @if ($modalModificarVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cerrarModificar">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">

                <div class="relative bg-orange-500 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">
                        Modificar Suministro
                    </h2>
                    <button
                        type="button"
                        wire:click="cerrarModificar"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 transition hover:text-white"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="max-h-[80vh] overflow-y-auto px-6 py-5">
                    <form wire:submit.prevent="guardarModificar" class="space-y-5">

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Nombre</label>
                            <input
                                type="text"
                                wire:model="form_nombre"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('form_nombre') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Categoría</label>
                            <input
                                type="text"
                                wire:model="form_categoria"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('form_categoria') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Código de barras</label>
                            <input
                                type="text"
                                wire:model="form_codigo_barras"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('form_codigo_barras') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Unidad de medida</label>
                                <input
                                    type="text"
                                    wire:model="form_unidad_medida"
                                    placeholder="unidad"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_unidad_medida') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Stock mínimo</label>
                                <input
                                    type="number"
                                    min="0"
                                    wire:model="form_stock_minimo"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('form_stock_minimo') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button
                                type="button"
                                wire:click="cerrarModificar"
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
    {{-- MODAL: STOCK DE SUMINISTRO (solo lectura) --}}
    {{-- ============================================================ --}}
    @if ($modalStockVisible && $this->suministroActual)
        @php $s = $this->suministroActual; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cerrarStock">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl">

                <div class="relative bg-orange-500 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">
                        Stock de Suministro
                    </h2>
                    <button
                        type="button"
                        wire:click="cerrarStock"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 transition hover:text-white"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-6">
                    <p class="text-sm font-semibold text-slate-800">{{ $s->nombre }}</p>
                    <p class="text-xs text-slate-400">{{ $s->categoria ?: 'Sin categoría' }}</p>

                    <div class="mt-5 grid grid-cols-2 gap-4">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-medium text-slate-500">Stock actual</p>
                            <p class="mt-1 text-2xl font-bold {{ $s->stock_bajo ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ rtrim(rtrim(number_format($s->stock_actual, 2), '0'), '.') }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $s->unidad_medida ?: 'unidad' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-medium text-slate-500">Stock mínimo</p>
                            <p class="mt-1 text-2xl font-bold text-slate-800">{{ $s->stock_minimo }}</p>
                            <p class="text-xs text-slate-400">{{ $s->unidad_medida ?: 'unidad' }}</p>
                        </div>
                    </div>

                    @if ($s->stock_bajo)
                        <div class="mt-4 flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2.5 text-xs font-medium text-rose-700">
                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            Este suministro está por debajo del stock mínimo. Se recomienda generar una compra.
                        </div>
                    @endif

                    @if ($s->inventario?->ultima_actualizacion)
                        <p class="mt-4 text-xs text-slate-400">
                            Última actualización: {{ $s->inventario->ultima_actualizacion->format('d/m/Y H:i') }}
                        </p>
                    @endif

                    <p class="mt-4 text-xs leading-relaxed text-slate-400">
                        El stock se actualiza automáticamente al registrar una compra del proveedor.
                        Este valor no se edita manualmente desde aquí.
                    </p>

                    <div class="mt-5 flex justify-end">
                        <button
                            type="button"
                            wire:click="cerrarStock"
                            class="rounded-lg bg-slate-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
                        >
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cerrarAgregar">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">

                <div class="relative bg-sky-600 px-6 py-4 text-center">
                    <h2 class="text-lg font-semibold text-white">Agregar Suministro</h2>
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
                    <form wire:submit.prevent="guardarNuevoSuministro" class="space-y-5">

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Nombre</label>
                            <input
                                type="text"
                                wire:model="nuevo_nombre"
                                placeholder="Ej. Espejo bucal N°5"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('nuevo_nombre') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Categoría</label>
                            <input
                                type="text"
                                wire:model="nuevo_categoria"
                                placeholder="Ej. Instrumento para: Endodoncia"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('nuevo_categoria') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Código de barras</label>
                            <input
                                type="text"
                                wire:model="nuevo_codigo_barras"
                                placeholder="Ej. 7501234560011"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            >
                            @error('nuevo_codigo_barras') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Unidad de medida</label>
                                <input
                                    type="text"
                                    wire:model="nuevo_unidad_medida"
                                    placeholder="unidad"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('nuevo_unidad_medida') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Stock mínimo</label>
                                <input
                                    type="number"
                                    min="0"
                                    wire:model="nuevo_stock_minimo"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                >
                                @error('nuevo_stock_minimo') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <p class="text-xs leading-relaxed text-slate-400">
                            El stock inicial se registrará automáticamente cuando se realice la primera compra de este suministro.
                        </p>

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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="cancelarEliminar">
            <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white p-6 text-center shadow-xl">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">¿Eliminar este suministro?</h3>
                <p class="mt-1 text-sm text-slate-500">Esta acción quitará al suministro de la lista de inventario activo.</p>

                <div class="mt-5 flex justify-center gap-3">
                    <button type="button" wire:click="cancelarEliminar" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="eliminarSuministro" class="rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>