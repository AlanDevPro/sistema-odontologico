<div>
    {{-- ====== MENSAJE FLASH ====== --}}
    @if (session('mensaje'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- ====== ENCABEZADO ====== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lista de Tratamientos</h1>
            <p class="text-xs text-gray-400">/ lista-de-tratamientos /</p>
        </div>
        <button wire:click="abrirModalCrear"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-2 w-fit">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar Tratamiento
        </button>
    </div>

    {{-- ====== BUSCADOR ====== --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
        <h2 class="text-center font-semibold text-gray-700 mb-4">Buscar Tratamiento:</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" wire:model.live.debounce.400ms="buscar" placeholder="Nombre o descripción..."
                   class="border border-orange-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
            <select wire:model.live="filtroEstado" class="border border-orange-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                <option value="">Todos los estados</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>
    </div>

    {{-- ====== FILTROS ====== --}}
    <div class="bg-white rounded-xl shadow-sm px-6 py-3 mb-6 flex flex-wrap items-center justify-around gap-3 text-sm text-gray-500">
        <select wire:model.live="orden" class="bg-transparent focus:outline-none cursor-pointer">
            <option value="reciente">El más reciente ⇅</option>
            <option value="antiguo">El más antiguo ⇅</option>
            <option value="az">Nombre A–Z</option>
            <option value="za">Nombre Z–A</option>
            <option value="precio_asc">Precio ↑</option>
            <option value="precio_desc">Precio ↓</option>
        </select>

        <div class="text-xs text-gray-400">
            Total encontrados: <span class="font-bold text-gray-600">{{ $listaTratamientos->total() }}</span>
        </div>
    </div>

    {{-- ====== LISTADO DE TRATAMIENTOS ====== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($listaTratamientos as $t)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition duration-300">
                {{-- Imagen --}}
                <div class="h-48 bg-gradient-to-r from-orange-400 to-red-400 relative">
                    @php
                        $imagenDefault = 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?w=400&q=80';
                    @endphp
                    <img src="{{ $imagenDefault }}" 
                         alt="{{ $t->nombre }}"
                         class="w-full h-full object-cover">
                    <div class="absolute top-3 right-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $t->estado == 1 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $t->estado == 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-800">{{ $t->nombre }}</h3>
                    <p class="text-xs text-gray-400 mb-3 line-clamp-2">{{ $t->descripcion ?? 'Sin descripción' }}</p>

                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="text-gray-400 text-xs">Estado</p>
                            <p class="font-semibold text-gray-700">{{ $t->estado == 1 ? 'Activo' : 'Inactivo' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-xs">Precio</p>
                            <p class="font-bold text-blue-600">Bs. {{ number_format($t->costo_referencial ?? 0, 2) }}</p>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                        <button wire:click="cambiarEstado({{ $t->id_tratamiento }})"
                                class="text-xs {{ $t->estado == 1 ? 'text-green-600 hover:text-green-800' : 'text-red-600 hover:text-red-800' }} font-medium">
                            {{ $t->estado == 1 ? '● Activo' : '○ Inactivo' }}
                        </button>
                        <div class="flex gap-2">
                            <button wire:click="abrirModalEditar({{ $t->id_tratamiento }})"
                                    class="text-gray-400 hover:text-blue-600 transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="eliminarTratamiento({{ $t->id_tratamiento }})"
                                    wire:confirm="¿Seguro que deseas eliminar {{ $t->nombre }}?"
                                    class="text-gray-400 hover:text-red-600 transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-10 text-center text-gray-400 text-sm">
                No se encontraron tratamientos con los filtros seleccionados.
            </div>
        @endforelse
    </div>

    {{-- ====== PAGINACIÓN ====== --}}
    <div class="mt-6">
        {{ $listaTratamientos->links() }}
    </div>

    {{-- ====== MODAL: CREAR / EDITAR TRATAMIENTO ====== --}}
    @if ($modalAbierto)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="cerrarModal">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="font-bold text-gray-700 text-lg">
                        {{ $modoEdicion ? 'Actualizar Tratamiento' : 'Agregar Tratamiento' }}
                    </h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                </div>

                <form wire:submit.prevent="guardarTratamiento" class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nombre *</label>
                        <input type="text" wire:model="nombre"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none"
                               placeholder="Ej: Ortodoncia, Endodoncia...">
                        @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Descripción</label>
                        <textarea wire:model="descripcion" rows="3"
                                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none"
                                  placeholder="Descripción detallada del tratamiento..."></textarea>
                        @error('descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Costo Referencial *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Bs.</span>
                            <input type="number" wire:model="costo_referencial" step="0.01"
                                   class="w-full border border-gray-200 rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none"
                                   placeholder="0.00">
                        </div>
                        @error('costo_referencial') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Estado</label>
                        <select wire:model="estado" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-300 focus:outline-none">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t">
                        <button type="button" wire:click="cerrarModal"
                                class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg">
                            {{ $modoEdicion ? 'Actualizar Tratamiento' : 'Agregar Tratamiento' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>