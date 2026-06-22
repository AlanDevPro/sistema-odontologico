<div>

    {{-- ============================================================ --}}
    {{-- ESTILOS DE ANIMACIÓN (una sola vez por vista) --}}
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
        .card-tratamiento {
            animation: fadeSlideUp .45s ease both;
        }
        @media (prefers-reduced-motion: reduce) {
            .card-tratamiento { animation: none; }
        }
        .modal-pop { animation: modalPop .25s ease-out both; }
    </style>

    {{-- ====== MENSAJE FLASH ====== --}}
    @if (session('mensaje'))
        <div class="mb-5 flex items-center gap-3 rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-teal-700 shadow-sm"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5m4.5 2.25a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('mensaje') }}</span>
        </div>
    @endif

    {{-- ====== ENCABEZADO ====== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-7">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Lista de Tratamientos</h1>
            <p class="text-xs font-medium text-slate-400">/ lista-de-tratamientos /</p>
        </div>
        <button wire:click="abrirModalCrear"
                class="group inline-flex w-fit items-center gap-2 rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-teal-600/30 transition-all duration-200 hover:-translate-y-0.5 hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-600/40 active:translate-y-0">
            <svg class="h-5 w-5 transition-transform duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar Tratamiento
        </button>
    </div>

    {{-- ====== BUSCADOR ====== --}}
    <div class="mb-5 rounded-2xl border border-slate-200/70 bg-gradient-to-br from-white to-teal-50/40 p-6 shadow-sm ring-1 ring-slate-100">
        <h2 class="mb-4 flex items-center justify-center gap-2 text-center text-sm font-semibold uppercase tracking-wide text-slate-500">
            <svg class="h-4 w-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            Buscar Tratamiento
        </h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="relative">
                
                <input type="text" wire:model.live.debounce.400ms="buscar" placeholder="Nombre o descripción..."
                       class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="relative">
                
                <select wire:model.live="filtroEstado"
                        class="w-full appearance-none rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-100">
                    <option value="">Todos los estados</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ====== FILTROS ====== --}}
    <div class="mb-7 flex flex-wrap items-center justify-around gap-3 rounded-2xl border border-slate-200/70 bg-white px-6 py-3.5 text-sm text-slate-500 shadow-sm">
        <div class="relative">
            <svg class="pointer-events-none absolute left-0 top-1/2 h-4 w-4 -translate-y-1/2 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
            </svg>
            <select wire:model.live="orden" class="cursor-pointer bg-transparent pl-6 font-medium text-slate-600 focus:outline-none">
                <option value="reciente">El más reciente</option>
                <option value="antiguo">El más antiguo</option>
                <option value="az">Nombre A–Z</option>
                <option value="za">Nombre Z–A</option>
                <option value="precio_asc">Precio ↑</option>
                <option value="precio_desc">Precio ↓</option>
            </select>
        </div>

        <div class="flex items-center gap-1.5 text-xs text-slate-400">
            Total encontrados:
            <span class="rounded-full bg-teal-50 px-2.5 py-0.5 text-xs font-bold text-teal-700">{{ $listaTratamientos->total() }}</span>
        </div>
    </div>

    {{-- ====== LISTADO DE TRATAMIENTOS ====== --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($listaTratamientos as $i => $t)
            <div wire:key="tratamiento-{{ $t->id_tratamiento }}"
                 class="card-tratamiento group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm ring-1 ring-transparent transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:ring-teal-100"
                 style="animation-delay: {{ min($i, 8) * 60 }}ms">

                {{-- Imagen --}}
                <div class="relative h-44 overflow-hidden bg-gradient-to-br from-teal-500 to-cyan-600">
                    @php $imagenDefault = 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?w=400&q=80'; @endphp
                    <img src="{{ $imagenDefault }}" alt="{{ $t->nombre }}"
                         class="h-full w-full object-cover opacity-90 mix-blend-overlay transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>

                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold shadow-sm backdrop-blur-sm
                            {{ $t->estado == 1 ? 'bg-emerald-500/90 text-white' : 'bg-rose-500/90 text-white' }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-white {{ $t->estado == 1 ? 'animate-pulse' : '' }}"></span>
                            {{ $t->estado == 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    <div class="absolute bottom-3 left-4 flex items-center gap-2 text-white">
                        <svg class="h-5 w-5 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.97-3.582-9-7.318-9-11.45C3 6.5 5.5 4 8.5 4c1.74 0 3.41.81 4.5 2.09C14.09 4.81 15.76 4 17.5 4 20.5 4 23 6.5 23 9.55 23 13.682 18.97 17.418 14 21z"/>
                        </svg>
                        <h3 class="truncate text-lg font-bold drop-shadow-sm">{{ $t->nombre }}</h3>
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="flex flex-1 flex-col p-5">
                    <p class="mb-4 line-clamp-2 text-sm text-slate-500">{{ $t->descripcion ?? 'Sin descripción' }}</p>

                    <div class="mb-4 flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 {{ $t->estado == 1 ? 'text-emerald-500' : 'text-rose-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25 4.5-4.5m4.5 2.25a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-semibold text-slate-700">{{ $t->estado == 1 ? 'Activo' : 'Inactivo' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-bold text-teal-700">Bs. {{ number_format($t->costo_referencial ?? 0, 2) }}</span>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-3">
                        <button wire:click="cambiarEstado({{ $t->id_tratamiento }})"
                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold transition-colors
                                    {{ $t->estado == 1
                                        ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100'
                                        : 'bg-rose-50 text-rose-600 hover:bg-rose-100' }}">
                            <span class="h-2 w-2 rounded-full {{ $t->estado == 1 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $t->estado == 1 ? 'Activo' : 'Inactivo' }}
                        </button>
                        <div class="flex gap-1">
                            <button wire:click="abrirModalEditar({{ $t->id_tratamiento }})" title="Editar tratamiento"
                                    class="rounded-lg p-2 text-slate-400 transition-all duration-200 hover:-translate-y-0.5 hover:bg-orange-50 hover:text-orange-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/>
                                </svg>
                            </button>
                            <button wire:click="eliminarTratamiento({{ $t->id_tratamiento }})"
                                    wire:confirm="¿Seguro que deseas eliminar {{ $t->nombre }}?"
                                    title="Eliminar tratamiento"
                                    class="rounded-lg p-2 text-slate-400 transition-all duration-200 hover:-translate-y-0.5 hover:bg-rose-50 hover:text-rose-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <svg class="mb-3 h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <p class="text-sm font-medium text-slate-500">No se encontraron tratamientos</p>
                <p class="mt-1 text-xs text-slate-400">Probá ajustar los filtros o el texto de búsqueda.</p>
            </div>
        @endforelse
    </div>

    {{-- ====== PAGINACIÓN ====== --}}
    <div class="mt-7">
        {{ $listaTratamientos->links() }}
    </div>

    {{-- ====== MODAL: CREAR / EDITAR TRATAMIENTO ====== --}}
    @if ($modalAbierto)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm" wire:click.self="cerrarModal">
            <div class="modal-pop w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl">

                <div class="flex items-center justify-between rounded-t-2xl bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.97-3.582-9-7.318-9-11.45C3 6.5 5.5 4 8.5 4c1.74 0 3.41.81 4.5 2.09C14.09 4.81 15.76 4 17.5 4 20.5 4 23 6.5 23 9.55 23 13.682 18.97 17.418 14 21z"/>
                        </svg>
                        {{ $modoEdicion ? 'Actualizar Tratamiento' : 'Agregar Tratamiento' }}
                    </h3>
                    <button wire:click="cerrarModal" class="rounded-full p-1 text-white/80 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="guardarTratamiento" class="space-y-4 px-6 py-5">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-500">Nombre *</label>
                        <input type="text" wire:model="nombre"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-100"
                               placeholder="Ej: Ortodoncia, Endodoncia...">
                        @error('nombre') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-500">Descripción</label>
                        <textarea wire:model="descripcion" rows="3"
                                  class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-100"
                                  placeholder="Descripción detallada del tratamiento..."></textarea>
                        @error('descripcion') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-500">Costo Referencial *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">Bs.</span>
                            <input type="number" wire:model="costo_referencial" step="0.01"
                                   class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-3 text-sm text-slate-700 transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-100"
                                   placeholder="0.00">
                        </div>
                        @error('costo_referencial') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-500">Estado</label>
                        <select wire:model="estado"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-100">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        @error('estado') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" wire:click="cerrarModal"
                                class="rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-100">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-teal-700 hover:shadow-md">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $modoEdicion ? 'Actualizar Tratamiento' : 'Agregar Tratamiento' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>