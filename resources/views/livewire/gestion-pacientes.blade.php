<div class="min-h-screen bg-gradient-to-br from-slate-50 via-orange-50/30 to-sky-50/20 p-6">

    {{-- ====== MENSAJES FLASH ====== --}}
    @if (session('mensaje'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-5 py-3.5 rounded-2xl shadow-sm shadow-emerald-100"
             x-data="{ show: true }" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             x-init="setTimeout(() => show = false, 3500)">
            <svg class="h-4 w-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('mensaje') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-5 py-3.5 rounded-2xl shadow-sm shadow-red-100"
             x-data="{ show: true }" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             x-init="setTimeout(() => show = false, 5000)">
            <svg class="h-4 w-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif


    {{-- ======================================================= --}}
    {{-- ====================  VISTA: LISTA  ===================== --}}
    {{-- ======================================================= --}}
    @if ($vista === 'lista')

        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1 h-7 rounded-full bg-gradient-to-b from-orange-400 to-orange-600"></div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Lista de Pacientes</h1>
                </div>
                <p class="text-xs text-slate-400 ml-3 font-mono">/ lista-de-pacientes /</p>
            </div>
            <button wire:click="abrirModalCrear"
                    class="group bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg shadow-orange-200 hover:shadow-orange-300 transition-all duration-200 hover:-translate-y-0.5 w-fit">
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Agregar Paciente
            </button>
        </div>

        {{-- BUSCADOR --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white p-6 mb-4 ring-1 ring-slate-100">
            <div class="flex items-center gap-2 mb-5">
                <svg class="h-4 w-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <h2 class="text-sm font-semibold text-slate-600 uppercase tracking-widest">Buscar Paciente</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.400ms="buscarNombres" placeholder="Nombres"
                           class="pl-10 w-full border border-slate-200 hover:border-orange-300 focus:border-orange-400 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all duration-150 placeholder:text-slate-400">
                </div>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.400ms="buscarApellidoPaterno" placeholder="Apellido Paterno"
                           class="pl-10 w-full border border-slate-200 hover:border-orange-300 focus:border-orange-400 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all duration-150 placeholder:text-slate-400">
                </div>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.400ms="buscarApellidoMaterno" placeholder="Apellido Materno"
                           class="pl-10 w-full border border-slate-200 hover:border-orange-300 focus:border-orange-400 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all duration-150 placeholder:text-slate-400">
                </div>
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white px-6 py-3.5 mb-6 flex flex-wrap items-center justify-around gap-3 ring-1 ring-slate-100">
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-3.5 w-3.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 8h10M11 12h2"/>
                </svg>
                <select wire:model.live="orden" class="bg-transparent focus:outline-none cursor-pointer text-slate-600 font-medium hover:text-orange-500 transition-colors">
                    <option value="reciente">Más reciente</option>
                    <option value="antiguo">Más antiguo</option>
                    <option value="az">Nombre A–Z</option>
                    <option value="za">Nombre Z–A</option>
                </select>
            </div>
            <div class="w-px h-5 bg-slate-200 hidden md:block"></div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-3.5 w-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V9l-6-6z"/>
                </svg>
                <select wire:model.live="filtroTratamiento" class="bg-transparent focus:outline-none cursor-pointer text-slate-600 font-medium hover:text-sky-500 transition-colors">
                    <option value="">Todos los tratamientos</option>
                    @foreach ($tratamientos as $t)
                        <option value="{{ $t->id_tratamiento }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-px h-5 bg-slate-200 hidden md:block"></div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-3.5 w-3.5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                </svg>
                <select wire:model.live="filtroFolder" class="bg-transparent focus:outline-none cursor-pointer text-slate-600 font-medium hover:text-violet-500 transition-colors">
                    <option value="">Todos los folders</option>
                    @foreach ($folders as $f)
                        <option value="{{ $f->id_folder }}">{{ $f->codigo_archivo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-px h-5 bg-slate-200 hidden md:block"></div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-3.5 w-3.5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <select wire:model.live="filtroSexo" class="bg-transparent focus:outline-none cursor-pointer text-slate-600 font-medium hover:text-pink-500 transition-colors">
                    <option value="">Todos los géneros</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="O">Otro</option>
                </select>
            </div>
        </div>

        {{-- LISTADO --}}
        <div class="space-y-3">
            @forelse ($listaPacientes as $p)
                <div class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm shadow-slate-200/60 border border-white hover:border-orange-200 hover:shadow-md hover:shadow-orange-100/50 p-4 flex items-center justify-between flex-wrap gap-4 transition-all duration-200 ring-1 ring-slate-100/80 hover:ring-orange-100">
                    <div class="flex items-center gap-4 min-w-[220px]">
                        <div class="relative h-13 w-13 flex-shrink-0">
                            <div class="h-13 w-13 h-12 w-12 rounded-full flex items-center justify-center text-white font-bold text-base shadow-md"
                                 style="background-color: {{ $p->color_avatar }}">
                                {{ $p->iniciales }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-emerald-400 border-2 border-white shadow-sm"></span>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm leading-tight">{{ $p->nombres }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $p->apellidos }}</p>
                        </div>
                    </div>

                    <div class="text-xs space-y-1">
                        <div class="flex items-center gap-1.5">
                            <svg class="h-3 w-3 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sky-600 font-medium">{{ $p->folder?->codigo_archivo ?? 'Sin asignar' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-400">#</span>
                            <span class="font-bold text-orange-500">{{ $p->id_paciente }}</span>
                        </div>
                    </div>

                    <div class="text-xs text-center min-w-[160px] px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-slate-400 mb-1 flex items-center justify-center gap-1">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Último tratamiento
                        </p>
                        <p class="font-semibold text-slate-700">{{ $p->ultimoTratamiento() ?? 'Sin registros' }}</p>
                    </div>

                    <button wire:click="verPaciente({{ $p->id_paciente }})"
                            class="group/btn bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 text-white text-xs font-semibold px-4 py-2.5 rounded-xl flex items-center gap-1.5 shadow-sm shadow-orange-200 hover:shadow-orange-300 transition-all duration-150 hover:-translate-y-0.5">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Revisar
                    </button>
                </div>
            @empty
                <div class="bg-white/80 rounded-2xl shadow-sm border border-dashed border-slate-200 p-14 text-center">
                    <svg class="h-10 w-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-slate-400 text-sm font-medium">No se encontraron pacientes</p>
                    <p class="text-slate-300 text-xs mt-1">Prueba ajustando los filtros de búsqueda</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5">
            {{ $listaPacientes->links() }}
        </div>

        {{-- MODAL: REGISTRO DE PACIENTE --}}
        @if ($modalAbierto)
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                 wire:click.self="cerrarModal"
                 x-data x-init="$el.style.opacity=0; setTimeout(()=>$el.style.cssText='opacity:1;transition:opacity 0.2s', 10)">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto border border-slate-100"
                     x-data x-init="$el.style.cssText='transform:scale(0.95);opacity:0;transition:all 0.2s'; setTimeout(()=>$el.style.cssText='transform:scale(1);opacity:1;transition:all 0.2s', 10)">
                    <div class="flex items-center justify-between px-7 py-5 border-b border-slate-100 bg-gradient-to-r from-orange-50 to-sky-50 rounded-t-3xl">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Registrar Nuevo Paciente</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Complete los campos obligatorios (*)</p>
                        </div>
                        <button wire:click="cerrarModal" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="guardarPaciente" class="px-7 py-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">CI / DNI *</label>
                                <input type="text" wire:model="ci_dni"
                                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                @error('ci_dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Fecha de nacimiento *</label>
                                <input type="date" wire:model="fecha_nacimiento"
                                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                @error('fecha_nacimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Nombres *</label>
                                <input type="text" wire:model="nombres"
                                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                @error('nombres') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Ap. Paterno *</label>
                                <input type="text" wire:model="apellido_paterno"
                                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                @error('apellido_paterno') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Ap. Materno</label>
                                <input type="text" wire:model="apellido_materno"
                                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Sexo *</label>
                                <select wire:model="sexo"
                                        class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                    <option value="">Seleccionar...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="O">Otro</option>
                                </select>
                                @error('sexo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Teléfono</label>
                                <input type="text" wire:model="telefono"
                                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Folder</label>
                                <select wire:model="id_folder"
                                        class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                    <option value="">Sin asignar</option>
                                    @foreach ($folders as $f)
                                        <option value="{{ $f->id_folder }}">{{ $f->codigo_archivo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Dirección</label>
                            <input type="text" wire:model="direccion"
                                   class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Antecedentes médicos</label>
                            <textarea wire:model="antecedentes_medicos" rows="3"
                                      class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all resize-none"></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="cerrarModal"
                                    class="px-5 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-semibold px-7 py-2.5 rounded-xl shadow-md shadow-orange-200 hover:shadow-orange-300 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Registrar Paciente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    @endif


    {{-- ======================================================= --}}
    {{-- ===========  VISTA: FICHA / HISTORIA CLÍNICA  =========== --}}
    {{-- ======================================================= --}}
    @if ($vista === 'ficha' && $paciente)

        {{-- ENCABEZADO --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1 h-7 rounded-full bg-gradient-to-b from-sky-400 to-sky-600"></div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Historia Clínica Odontológica</h1>
                </div>
                <p class="text-xs text-slate-400 ml-3 font-mono">/ paciente / {{ \Illuminate\Support\Str::slug($paciente->nombres . '-' . $paciente->apellido_paterno) }}-{{ $paciente->id_paciente }}</p>
            </div>
            <button wire:click="volverALista"
                    class="group bg-white hover:bg-slate-50 text-slate-600 text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 border border-slate-200 hover:border-slate-300 shadow-sm hover:shadow transition-all duration-150">
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </button>
        </div>

        {{-- TARJETA DE IDENTIFICACIÓN --}}
        <div class="bg-gradient-to-r from-white to-orange-50/50 rounded-2xl shadow-md shadow-slate-200/60 border border-orange-100/50 p-6 flex items-center justify-between flex-wrap gap-4 mb-4 ring-1 ring-orange-50">
            <div>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-1">Paciente</p>
                <p class="font-bold text-slate-800 text-xl leading-tight">{{ $paciente->nombres }}</p>
                <p class="text-sm text-slate-500 mt-0.5">{{ $paciente->apellidos }}</p>
            </div>
            <div class="h-16 w-16 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg"
                 style="background-color: {{ $paciente->color_avatar }}">
                {{ $paciente->iniciales }}
            </div>
            <div class="bg-white border border-orange-200 rounded-xl px-5 py-3 text-right shadow-sm">
                <p class="text-xs text-slate-500 mb-1">Folder: <span class="font-semibold text-slate-700">{{ $paciente->folder?->codigo_archivo ?? 'Sin asignar' }}</span></p>
                <p class="text-orange-500 font-bold text-lg">#{{ $paciente->id_paciente }}</p>
            </div>
        </div>

        {{-- ÚLTIMO TRATAMIENTO + ODONTOGRAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white ring-1 ring-slate-100 p-6 text-center">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-2 flex items-center justify-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Último tratamiento
                </p>
                <p class="font-bold text-slate-800 text-lg">{{ $paciente->ultimoTratamiento() ?? 'Sin registros' }}</p>
            </div>
            <button wire:click="irAOdontograma"
                    class="group bg-gradient-to-br from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-2xl shadow-lg shadow-emerald-200 hover:shadow-emerald-300 p-6 flex flex-col items-center justify-center gap-2 font-semibold transition-all duration-200 hover:-translate-y-1">
                <svg class="h-7 w-7 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Ver Odontograma</span>
            </button>
        </div>

        {{-- DATOS BÁSICOS / ANTECEDENTES MÉDICOS --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white ring-1 ring-slate-100 p-6 flex items-start justify-between gap-4 mb-6">
            <div class="flex-1">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mb-3">Información personal</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-slate-400 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1"/></svg>
                            CI/DNI:
                        </span>
                        <span class="text-slate-700 font-medium">{{ $paciente->ci_dni }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-slate-400 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Teléfono:
                        </span>
                        <span class="text-slate-700 font-medium">{{ $paciente->telefono ?? '—' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs sm:col-span-2">
                        <span class="text-slate-400 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Dirección:
                        </span>
                        <span class="text-slate-700 font-medium">{{ $paciente->direccion ?? '—' }}</span>
                    </div>
                </div>
                <div class="bg-amber-50/70 border border-amber-100 rounded-xl p-3">
                    <p class="text-[10px] text-amber-600 uppercase tracking-wider font-semibold mb-1 flex items-center gap-1">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Antecedentes médicos
                    </p>
                    <p class="text-slate-700 text-xs leading-relaxed">{{ $paciente->antecedentes_medicos ?: 'Sin antecedentes registrados.' }}</p>
                </div>
            </div>
            <button wire:click="abrirModalDatosBasicos"
                    class="flex-shrink-0 bg-sky-50 hover:bg-sky-100 text-sky-600 text-xs font-semibold px-4 py-2.5 rounded-xl border border-sky-200 hover:border-sky-300 flex items-center gap-1.5 transition-all duration-150">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </button>
        </div>

        {{-- ELIMINAR --}}
        <div class="flex justify-end">
            <button wire:click="abrirModalEliminar"
                    class="group bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-5 py-2.5 rounded-xl border border-red-200 hover:border-red-300 flex items-center gap-2 transition-all duration-150">
                <svg class="h-4 w-4 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar Paciente
            </button>
        </div>

        {{-- MODAL: EDITAR DATOS BÁSICOS --}}
        @if ($modalDatosBasicos)
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                 wire:click.self="$set('modalDatosBasicos', false)">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md border border-slate-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-center font-bold py-4 text-base">Actualizar Datos del Paciente</div>
                    <form wire:submit.prevent="guardarDatosBasicos" class="px-6 py-5 space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Nombres</label>
                            <input type="text" wire:model="f_nombres" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                            @error('f_nombres') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Ap. Paterno</label>
                                <input type="text" wire:model="f_apellido_paterno" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                @error('f_apellido_paterno') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Ap. Materno</label>
                                <input type="text" wire:model="f_apellido_materno" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Sexo</label>
                                <select wire:model="f_sexo" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                    <option value="M">Varón</option>
                                    <option value="F">Mujer</option>
                                    <option value="O">Otro</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Teléfono</label>
                                <input type="text" wire:model="f_telefono" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Dirección</label>
                            <input type="text" wire:model="f_direccion" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Folder</label>
                            <select wire:model="f_id_folder" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                <option value="">Sin asignar</option>
                                @foreach ($folders as $f)
                                    <option value="{{ $f->id_folder }}">{{ $f->codigo_archivo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Antecedentes médicos</label>
                            <textarea wire:model="f_antecedentes_medicos" rows="2" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all resize-none"></textarea>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                            <button type="button" wire:click="$set('modalDatosBasicos', false)" class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2 rounded-xl hover:bg-slate-100 transition-all">Cancelar</button>
                            <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-md shadow-orange-200 hover:shadow-orange-300 transition-all flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- MODAL: CONFIRMAR ELIMINACIÓN --}}
        @if ($modalEliminar)
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center border border-slate-100">
                    <div class="mx-auto h-14 w-14 rounded-2xl bg-red-50 border border-red-200 flex items-center justify-center mb-5">
                        <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">¿Eliminar paciente?</h3>
                    <p class="text-sm text-slate-500 mb-7 leading-relaxed">Esta acción eliminará todos los datos del paciente incluyendo su historia clínica. No se puede deshacer.</p>
                    <div class="flex gap-3">
                        <button wire:click="$set('modalEliminar', false)" class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancelar</button>
                        <button wire:click="confirmarEliminarPaciente" class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-red-200 transition-all">Eliminar</button>
                    </div>
                </div>
            </div>
        @endif

    @endif


    {{-- ======================================================= --}}
    {{-- ================  VISTA: ODONTOGRAMA  ================== --}}
    {{-- ======================================================= --}}
    @if ($vista === 'odontograma' && $paciente)

        @php
            $arcadaSuperior   = [18,17,16,15,14,13,12,11,  21,22,23,24,25,26,27,28];
            $arcadaInferior   = [48,47,46,45,44,43,42,41,  31,32,33,34,35,36,37,38];
            $deciduosSuperior = [55,54,53,52,51,  61,62,63,64,65];
            $deciduosInferior = [85,84,83,82,81,  71,72,73,74,75];
        @endphp

        {{-- ENCABEZADO --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1 h-7 rounded-full bg-gradient-to-b from-emerald-400 to-teal-600"></div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Odontograma del Paciente</h1>
                </div>
                <p class="text-xs text-slate-400 ml-3 font-mono">/ odontograma / {{ \Illuminate\Support\Str::slug($paciente->nombres . '-' . $paciente->apellido_paterno) }}-{{ $paciente->id_paciente }}</p>
            </div>
            <button wire:click="volverAFicha"
                    class="group bg-white hover:bg-slate-50 text-slate-600 text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center gap-2 border border-slate-200 hover:border-slate-300 shadow-sm hover:shadow transition-all duration-150">
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </button>
        </div>

        {{-- TARJETA PACIENTE --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white ring-1 ring-slate-100 p-4 flex items-center justify-between flex-wrap gap-4 mb-4">
            <div class="flex items-center gap-4">
                <div class="text-xs space-y-1">
                    <div class="flex items-center gap-1.5">
                        <svg class="h-3 w-3 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                        <span class="text-sky-600 font-medium">{{ $paciente->folder?->codigo_archivo ?? 'Sin asignar' }}</span>
                    </div>
                    <p class="text-orange-500 font-bold">#{{ $paciente->id_paciente }}</p>
                </div>
                <div>
                    <p class="font-bold text-slate-800">{{ $paciente->nombres }}</p>
                    <p class="text-sm text-slate-500">{{ $paciente->apellidos }}</p>
                </div>
            </div>
            <div class="h-14 w-14 rounded-xl flex items-center justify-center text-white font-bold shadow-md"
                 style="background-color: {{ $paciente->color_avatar }}">
                {{ $paciente->iniciales }}
            </div>
        </div>

        {{-- LEYENDA --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white ring-1 ring-slate-100 p-4 mb-4 flex flex-wrap gap-3 justify-center">
            @foreach ($coloresEstado as $estado => $color)
                <span class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-100 text-xs text-slate-600 font-medium">
                    <span class="inline-block w-3 h-3 rounded-full shadow-sm ring-1 ring-black/10" style="background:{{ $color }}"></span>
                    {{ $estado }}
                </span>
            @endforeach
            <span class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-100 text-xs text-slate-600 font-medium">
                <span class="inline-block w-3 h-3 rounded-full shadow-sm ring-1 ring-black/10 bg-slate-200"></span>
                Sin hallazgos
            </span>
        </div>

        @if (! $odontogramaSeleccionadoId)
            <div class="bg-white/80 rounded-2xl shadow-md border border-dashed border-slate-200 p-12 text-center text-slate-400 text-sm mb-4">
                <svg class="h-10 w-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium text-slate-500 mb-1">Sin odontogramas registrados</p>
                <p class="text-slate-400 text-xs">Registra uno desde la lista de abajo para comenzar.</p>
            </div>
        @else
            {{-- DIAGRAMA ODONTOGRAMA --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white ring-1 ring-slate-100 p-5 mb-4 overflow-x-auto">

                {{-- Deciduos superiores --}}
                <div class="flex items-center justify-center gap-1 mb-2 flex-wrap">
                    <span class="text-[9px] text-slate-400 font-mono uppercase mr-1 hidden sm:block">deciduos</span>
                    @foreach ($deciduosSuperior as $num)
                        @php $numStr = (string) $num; $color = $estadoDientes[$numStr]['color'] ?? '#d1d5db'; @endphp
                        <button wire:click="marcarDiente('{{ $numStr }}')" title="Diente {{ $num }}"
                                class="w-8 h-9 rounded-lg text-[10px] font-bold text-white flex items-center justify-center hover:opacity-80 hover:scale-110 transition-all duration-150 shadow-sm ring-1 ring-black/10"
                                style="background-color: {{ $color }}">{{ $num }}</button>
                    @endforeach
                </div>

                <div class="border-t border-dashed border-slate-200 mx-8 mb-3 relative">
                    <span class="absolute left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-2 text-[9px] text-slate-400 uppercase tracking-widest">deciduos / permanentes</span>
                </div>

                {{-- Permanentes superiores --}}
                <div class="flex justify-center gap-1 mb-2 flex-wrap">
                    @foreach ($arcadaSuperior as $num)
                        @php $numStr = (string) $num; $color = $estadoDientes[$numStr]['color'] ?? '#d1d5db'; @endphp
                        <button wire:click="marcarDiente('{{ $numStr }}')" title="Diente {{ $num }}"
                                class="w-9 h-11 rounded-lg text-xs font-bold text-white flex items-center justify-center hover:opacity-80 hover:scale-110 transition-all duration-150 shadow-sm ring-1 ring-black/10"
                                style="background-color: {{ $color }}">{{ $num }}</button>
                    @endforeach
                </div>

                <div class="flex justify-center my-3">
                    <div class="w-full max-w-2xl border-t-2 border-slate-300 relative">
                        <span class="absolute left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-3 text-[10px] text-slate-500 font-semibold uppercase tracking-widest">Superior / Inferior</span>
                    </div>
                </div>

                {{-- Permanentes inferiores --}}
                <div class="flex justify-center gap-1 mt-2 mb-2 flex-wrap">
                    @foreach ($arcadaInferior as $num)
                        @php $numStr = (string) $num; $color = $estadoDientes[$numStr]['color'] ?? '#d1d5db'; @endphp
                        <button wire:click="marcarDiente('{{ $numStr }}')" title="Diente {{ $num }}"
                                class="w-9 h-11 rounded-lg text-xs font-bold text-white flex items-center justify-center hover:opacity-80 hover:scale-110 transition-all duration-150 shadow-sm ring-1 ring-black/10"
                                style="background-color: {{ $color }}">{{ $num }}</button>
                    @endforeach
                </div>

                <div class="border-t border-dashed border-slate-200 mx-8 mb-2 mt-3 relative">
                    <span class="absolute left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-2 text-[9px] text-slate-400 uppercase tracking-widest">permanentes / deciduos</span>
                </div>

                {{-- Deciduos inferiores --}}
                <div class="flex justify-center gap-1 flex-wrap mt-2">
                    @foreach ($deciduosInferior as $num)
                        @php $numStr = (string) $num; $color = $estadoDientes[$numStr]['color'] ?? '#d1d5db'; @endphp
                        <button wire:click="marcarDiente('{{ $numStr }}')" title="Diente {{ $num }}"
                                class="w-8 h-9 rounded-lg text-[10px] font-bold text-white flex items-center justify-center hover:opacity-80 hover:scale-110 transition-all duration-150 shadow-sm ring-1 ring-black/10"
                                style="background-color: {{ $color }}">{{ $num }}</button>
                    @endforeach
                </div>

                <p class="text-center text-xs text-slate-400 mt-4 flex items-center justify-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
                    Haz clic en una pieza dental para registrar un hallazgo
                </p>
            </div>

            {{-- HALLAZGOS REGISTRADOS --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white ring-1 ring-slate-100 p-5 mb-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-4 rounded-full bg-orange-400 inline-block"></span>
                    Hallazgos registrados
                </h3>
                @forelse ($detallesOdontograma as $d)
                    <div class="group flex items-center justify-between flex-wrap gap-2 bg-slate-50/80 hover:bg-slate-50 border border-slate-100 hover:border-slate-200 rounded-xl p-3.5 mb-2 transition-all duration-150">
                        <div class="text-xs space-y-0.5">
                            <p class="font-semibold text-slate-700">Pieza {{ $d['pieza_dental'] }} — {{ $d['cara'] }}</p>
                            <p class="text-slate-500">{{ $d['diagnostico'] }}</p>
                            <p class="text-slate-400">Trat.: {{ $d['tratamiento']['nombre'] ?? 'Sin asignar' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full text-white shadow-sm"
                                  style="background-color: {{ $coloresEstado[$d['estado']] ?? '#9ca3af' }}">{{ $d['estado'] }}</span>
                            <button wire:click="abrirModalEditarDetalle({{ $d['id_detalle'] }})"
                                    class="bg-white text-sky-600 border border-sky-200 hover:bg-sky-50 hover:border-sky-300 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all">Editar</button>
                            <button wire:click="eliminarDetalleDiente({{ $d['id_detalle'] }})" wire:confirm="¿Eliminar este hallazgo?"
                                    class="bg-white text-red-500 border border-red-200 hover:bg-red-50 hover:border-red-300 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all">Eliminar</button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        <svg class="h-8 w-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Aún no hay hallazgos en este odontograma
                    </div>
                @endforelse
            </div>
        @endif

        {{-- LISTA DE ODONTOGRAMAS --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md shadow-slate-200/60 border border-white ring-1 ring-slate-100 p-5 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <span class="w-1.5 h-4 rounded-full bg-emerald-400 inline-block"></span>
                    Lista de Odontogramas
                </h3>
                <button wire:click="abrirModalNuevoOdontograma"
                        class="group bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white text-xs font-semibold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow-sm shadow-sky-200 hover:shadow-sky-300 transition-all duration-150 hover:-translate-y-0.5">
                    <svg class="h-3.5 w-3.5 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Registrar Nuevo
                </button>
            </div>

            @forelse ($paciente->odontogramas as $od)
                <div class="border {{ $odontogramaSeleccionadoId === $od->id_odontograma ? 'border-sky-200 bg-sky-50/60 ring-1 ring-sky-100' : 'border-slate-100 bg-slate-50/50 hover:border-slate-200 hover:bg-slate-50' }} rounded-xl p-3.5 flex items-center justify-between flex-wrap gap-2 mb-2 transition-all duration-150">
                    <div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm font-medium text-slate-700">Dr(a). {{ $od->doctor?->nombre_completo ?? 'Sin asignar' }}</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5 ml-6">{{ $od->fecha_evaluacion?->format('d/m/Y H:i') }}</p>
                        @if ($od->observaciones_generales)
                            <p class="text-xs text-slate-500 mt-0.5 ml-6 italic">{{ $od->observaciones_generales }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="seleccionarOdontograma({{ $od->id_odontograma }})"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1 transition-all {{ $odontogramaSeleccionadoId === $od->id_odontograma ? 'bg-emerald-100 text-emerald-700 border border-emerald-300' : 'bg-white text-sky-600 border border-sky-200 hover:bg-sky-50 hover:border-sky-300' }}">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $odontogramaSeleccionadoId === $od->id_odontograma ? 'Seleccionado' : 'Seleccionar' }}
                        </button>
                        <button wire:click="confirmarEliminarOdontograma({{ $od->id_odontograma }})"
                                class="bg-white text-red-500 border border-red-200 hover:bg-red-50 hover:border-red-300 text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1 transition-all">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            @empty
                <div class="border border-dashed border-slate-200 rounded-xl p-8 text-center text-slate-400 text-sm">
                    <svg class="h-8 w-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    No hay odontogramas. Haz clic en «Registrar Nuevo» para comenzar.
                </div>
            @endforelse
        </div>

        {{-- MODAL: NUEVO ODONTOGRAMA --}}
        @if ($modalNuevoOdontograma)
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                 wire:click.self="$set('modalNuevoOdontograma', false)">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg border border-slate-100 overflow-hidden">
                    <div class="flex items-center justify-between px-7 py-5 border-b border-slate-100 bg-gradient-to-r from-sky-50 to-emerald-50">
                        <div>
                            <h3 class="font-bold text-slate-800">Registrar Nuevo Odontograma</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Selecciona el doctor responsable</p>
                        </div>
                        <button wire:click="$set('modalNuevoOdontograma', false)" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="guardarNuevoOdontograma" class="px-7 py-5 space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Doctor(a) responsable *</label>
                            <select wire:model="od_id_doctor" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-sky-400 focus:ring-2 focus:ring-sky-100 focus:outline-none transition-all">
                                <option value="">Seleccionar...</option>
                                @foreach ($doctores as $doc)
                                    <option value="{{ $doc->id_doctor }}">{{ $doc->nombre_completo }}</option>
                                @endforeach
                            </select>
                            @error('od_id_doctor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Observaciones generales</label>
                            <textarea wire:model="od_observaciones" rows="3" placeholder="Ej: Buena higiene oral, sin molestias."
                                      class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-sky-400 focus:ring-2 focus:ring-sky-100 focus:outline-none transition-all resize-none"></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="$set('modalNuevoOdontograma', false)" class="px-5 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                            <button type="submit" class="bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white text-sm font-semibold px-7 py-2.5 rounded-xl shadow-md shadow-sky-200 hover:shadow-sky-300 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- MODAL: CONFIRMAR ELIMINAR ODONTOGRAMA --}}
        @if ($modalEliminarOdontograma)
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center border border-slate-100">
                    <div class="mx-auto h-14 w-14 rounded-2xl bg-red-50 border border-red-200 flex items-center justify-center mb-5">
                        <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Eliminar Odontograma</h3>
                    <p class="text-sm text-slate-500 mb-7 leading-relaxed">¿Seguro? Se eliminarán todos los hallazgos vinculados. Esta acción no se puede deshacer.</p>
                    <div class="flex gap-3">
                        <button wire:click="$set('modalEliminarOdontograma', false)" class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancelar</button>
                        <button wire:click="eliminarOdontograma" class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-red-200 transition-all">Sí, Eliminar</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- MODAL: HALLAZGO POR DIENTE --}}
        @if ($modalDetalleDiente)
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                 wire:click.self="cerrarModalDetalleDiente">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg border border-slate-100 overflow-hidden">
                    <div class="flex items-center justify-between px-7 py-5 border-b border-slate-100 bg-gradient-to-r from-orange-50 to-amber-50">
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $detalleEditandoId ? 'Editar' : 'Registrar' }} Hallazgo</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Pieza dental: <strong class="text-orange-600">{{ $dd_pieza_dental }}</strong></p>
                        </div>
                        <button wire:click="cerrarModalDetalleDiente" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="guardarDetalleDiente" class="px-7 py-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Cara *</label>
                                <select wire:model="dd_cara" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($opcionesCara as $cara)
                                        <option value="{{ $cara }}">{{ $cara }}</option>
                                    @endforeach
                                </select>
                                @error('dd_cara') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado *</label>
                                <select wire:model="dd_estado" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                    @foreach ($opcionesEstado as $est)
                                        <option value="{{ $est }}">{{ $est }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Diagnóstico *</label>
                            <input type="text" wire:model="dd_diagnostico" placeholder="Ej: Caries oclusal profunda"
                                   class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                            @error('dd_diagnostico') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Tratamiento asociado</label>
                            <select wire:model="dd_id_tratamiento" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 focus:bg-white focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all">
                                <option value="">Sin asignar</option>
                                @foreach ($tratamientos as $t)
                                    <option value="{{ $t->id_tratamiento }}">{{ $t->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="cerrarModalDetalleDiente" class="px-5 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                            <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-semibold px-7 py-2.5 rounded-xl shadow-md shadow-orange-200 hover:shadow-orange-300 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    @endif
</div>