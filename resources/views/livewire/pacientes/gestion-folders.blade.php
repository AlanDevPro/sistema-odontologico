<div class="min-h-screen bg-gradient-to-br from-slate-100 via-orange-50/30 to-slate-100 p-6 md:p-8" x-data>

    {{-- ════════════════════════════════════════════════════
         FLASH MESSAGE
    ════════════════════════════════════════════════════ --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-emerald-700 shadow-sm shadow-emerald-100">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif


    {{-- ════════════════════════════════════════════════════
         VISTA 1: BIBLIOTECA DE FOLDERES
    ════════════════════════════════════════════════════ --}}
    @if($vista === 'folders')

        {{-- Cabecera --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Biblioteca de Folderes</h1>
                <p class="mt-0.5 text-xs text-slate-400 font-medium tracking-wide">/ Pacientes / Folders</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button wire:click="abrirReasignar"
                        class="group inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition-all duration-200 hover:border-slate-300 hover:bg-slate-50 hover:shadow-md active:scale-95">
                    <svg class="h-4 w-4 text-slate-400 transition-transform duration-300 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    Reasignar pacientes
                </button>
                <button wire:click="abrirModalAgregar"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-orange-200 transition-all duration-200 hover:from-orange-600 hover:to-orange-700 hover:shadow-md hover:shadow-orange-200 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Nuevo Folder
                </button>
            </div>
        </div>

        {{-- Stats rápidas --}}
        <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Total Folders</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">{{ count($folders) }}</p>
            </div>
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Con Pacientes</p>
                <p class="mt-1 text-2xl font-bold text-orange-600">{{ collect($totalesPorFolder)->filter(fn($v) => $v > 0)->count() }}</p>
            </div>
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Total Pacientes</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">{{ array_sum($totalesPorFolder) }}</p>
            </div>
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Vacíos</p>
                <p class="mt-1 text-2xl font-bold text-slate-400">{{ collect($totalesPorFolder)->filter(fn($v) => $v === 0)->count() }}</p>
            </div>
        </div>

        @if(count($folders) > 0)
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($folders as $folder)
                @php $total = $totalesPorFolder[$folder->id_folder] ?? 0; @endphp
                <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-100/60 hover:border-orange-100">
                    {{-- Franja superior decorativa --}}
                    <div class="h-1.5 w-full bg-gradient-to-r from-orange-400 to-orange-500"></div>

                    <div class="flex flex-1 flex-col gap-3 p-5">
                        {{-- Icono + código --}}
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-500 transition-colors duration-200 group-hover:bg-orange-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-base leading-tight">{{ $folder->codigo_archivo }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">ID #{{ $folder->id_folder }}</p>
                            </div>
                        </div>

                        {{-- Meta --}}
                        <div class="space-y-1.5">
                            @if($folder->estante)
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <div class="flex h-5 w-5 items-center justify-center rounded-md bg-slate-100">
                                    <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z"/>
                                    </svg>
                                </div>
                                <span>Estante <strong class="text-slate-700">{{ $folder->estante }}</strong></span>
                            </div>
                            @endif
                            @if($folder->seccion)
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <div class="flex h-5 w-5 items-center justify-center rounded-md bg-slate-100">
                                    <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                    </svg>
                                </div>
                                <span>Sección <strong class="text-slate-700">{{ $folder->seccion }}</strong></span>
                            </div>
                            @endif
                            @if(!$folder->estante && !$folder->seccion)
                            <p class="text-xs italic text-slate-300">Sin ubicación asignada</p>
                            @endif
                        </div>

                        {{-- Badge pacientes --}}
                        <div>
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold
                                {{ $total > 0 ? 'bg-orange-50 text-orange-600 ring-1 ring-orange-200' : 'bg-slate-50 text-slate-400 ring-1 ring-slate-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $total > 0 ? 'bg-orange-400' : 'bg-slate-300' }}"></span>
                                {{ $total }} {{ $total === 1 ? 'paciente' : 'pacientes' }}
                            </span>
                        </div>

                        {{-- Acciones --}}
                        <div class="mt-auto flex items-center justify-between border-t border-slate-50 pt-3">
                            <button wire:click="verDetalle({{ $folder->id_folder }})"
                                    class="text-xs font-semibold text-orange-500 transition-colors hover:text-orange-700 flex items-center gap-1">
                                Ver pacientes
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </button>
                            <button wire:click="abrirModalEditar({{ $folder->id_folder }})"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition-all duration-200 hover:bg-orange-500 hover:text-white hover:shadow-sm active:scale-90"
                                    title="Editar folder">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-white/60 py-20 text-center">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                    <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-500">No hay folders registrados aún</p>
                <p class="mt-1 text-xs text-slate-400">Crea el primer folder con el botón <strong class="text-orange-500">Nuevo Folder</strong></p>
            </div>
        @endif

    @endif


    {{-- ════════════════════════════════════════════════════
         VISTA 2: DETALLE DE UN FOLDER
    ════════════════════════════════════════════════════ --}}
    @if($vista === 'detalle_folder' && $folderSeleccionado)

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <button wire:click="volverFolders"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-600 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Volver
                </button>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Folder: {{ $folderSeleccionado['codigo_archivo'] }}</h1>
                    <p class="text-xs text-slate-400">/ Pacientes / Folders / {{ $folderSeleccionado['codigo_archivo'] }}</p>
                </div>
            </div>
            <button wire:click="abrirModalEditar({{ $folderSeleccionado['id_folder'] }})"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-orange-200 transition-all hover:from-orange-600 hover:to-orange-700 hover:shadow-md active:scale-95">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                </svg>
                Modificar Folder
            </button>
        </div>

        {{-- Info card del folder --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-50 bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm text-orange-500">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">{{ $folderSeleccionado['codigo_archivo'] }}</h2>
                            @if($folderSeleccionado['estante'])
                                <p class="text-sm text-slate-500">Estante <strong class="text-slate-700">{{ $folderSeleccionado['estante'] }}</strong>
                                @if($folderSeleccionado['seccion']) · Sección <strong class="text-slate-700">{{ $folderSeleccionado['seccion'] }}</strong>@endif</p>
                            @endif
                            @if($folderSeleccionado['observaciones'])
                                <p class="mt-1 text-xs italic text-slate-400">{{ $folderSeleccionado['observaciones'] }}</p>
                            @endif
                        </div>
                    </div>
                    @php $pacs = $this->getPacientesFolder(); @endphp
                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-bold text-blue-600 shadow-sm ring-1 ring-blue-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                        </svg>
                        {{ count($pacs) }} {{ count($pacs) === 1 ? 'paciente' : 'pacientes' }}
                    </span>
                </div>
            </div>

            {{-- Tabla de pacientes --}}
            @if(count($pacs))
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80">
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">CI / DNI</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Nombre Completo</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Teléfono</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Registro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($pacs as $i => $pac)
                        <tr class="transition-colors hover:bg-orange-50/30">
                            <td class="px-5 py-3.5 text-xs text-slate-300 font-mono">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-5 py-3.5"><span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-600">{{ $pac->ci_dni }}</span></td>
                            <td class="px-5 py-3.5 font-medium text-slate-700">{{ $pac->nombre_completo }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $pac->telefono ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-xs text-slate-400">
                                {{ $pac->fecha_registro ? \Carbon\Carbon::parse($pac->fecha_registro)->format('d/m/Y') : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                    <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-400">Este folder aún no tiene pacientes asignados</p>
            </div>
            @endif
        </div>

    @endif


    {{-- ════════════════════════════════════════════════════
         MODAL: AGREGAR FOLDER
    ════════════════════════════════════════════════════ --}}
    @if($modalAgregar)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm" wire:click.self="$set('modalAgregar', false)">
        <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10"
             x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 px-6 py-4 text-center">
                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">Registrar Nuevo Folder</h3>
            </div>

            <div class="space-y-4 p-6">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Código de archivo <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="f_codigo_archivo" placeholder="Ej: FLD-01, ORT-01"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 transition focus:border-orange-400 focus:outline-none focus:ring-4 focus:ring-orange-100"
                           style="text-transform:uppercase;">
                    <p class="text-xs text-slate-400">Se guardará en mayúsculas. Debe ser único.</p>
                    @error('f_codigo_archivo') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Estante</label>
                        <input type="text" wire:model="f_estante" placeholder="Ej: A, B, 1"
                               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 transition focus:border-orange-400 focus:outline-none focus:ring-4 focus:ring-orange-100">
                        @error('f_estante') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Sección</label>
                        <input type="text" wire:model="f_seccion" placeholder="Ej: Superior"
                               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 transition focus:border-orange-400 focus:outline-none focus:ring-4 focus:ring-orange-100">
                        @error('f_seccion') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Observaciones</label>
                    <textarea wire:model="f_observaciones" rows="2" placeholder="Notas adicionales..."
                              class="w-full resize-none rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 transition focus:border-orange-400 focus:outline-none focus:ring-4 focus:ring-orange-100"></textarea>
                    @error('f_observaciones') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <button wire:click="$set('modalAgregar', false)"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-500 transition hover:bg-slate-100 active:scale-95">
                    Cancelar
                </button>
                <button wire:click="guardarFolder"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 px-5 py-2 text-sm font-semibold text-white shadow-sm shadow-orange-200 transition hover:from-orange-600 hover:to-orange-700 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Guardar Folder
                </button>
            </div>
        </div>
    </div>
    @endif


    {{-- ════════════════════════════════════════════════════
         MODAL: EDITAR FOLDER
    ════════════════════════════════════════════════════ --}}
    @if($modalEditar && $folderSeleccionado)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm" wire:click.self="$set('modalEditar', false)">
        <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10"
             x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div class="bg-gradient-to-br from-blue-600 to-blue-700 px-6 py-4 text-center">
                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">Modificar Folder</h3>
                <p class="mt-0.5 text-xs text-blue-100">{{ $folderSeleccionado['codigo_archivo'] }}</p>
            </div>

            <div class="space-y-4 p-6">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Código de archivo <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="f_codigo_archivo"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
                           style="text-transform:uppercase;">
                    @error('f_codigo_archivo') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Estante</label>
                        <input type="text" wire:model="f_estante"
                               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100">
                        @error('f_estante') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Sección</label>
                        <input type="text" wire:model="f_seccion"
                               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100">
                        @error('f_seccion') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Observaciones</label>
                    <textarea wire:model="f_observaciones" rows="2"
                              class="w-full resize-none rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"></textarea>
                    @error('f_observaciones') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- QR placeholder --}}
                <div class="flex flex-col items-center gap-2 rounded-xl bg-slate-50 p-4">
                    <div class="flex h-20 w-20 items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-white">
                        <svg class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <rect x="5" y="5" width="3" height="3" fill="currentColor" stroke="none"/>
                            <rect x="16" y="5" width="3" height="3" fill="currentColor" stroke="none"/>
                            <rect x="5" y="16" width="3" height="3" fill="currentColor" stroke="none"/>
                            <path d="M14 14h2v2h-2zm4 0h2v2h-2zm-4 4h2v2h-2zm4 0h2v2h-2z" fill="currentColor" stroke="none"/>
                        </svg>
                    </div>
                    <span class="text-xs text-slate-400">QR del folder</span>
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <button wire:click="confirmarEliminar({{ $folderSeleccionado['id_folder'] }})"
                        class="inline-flex items-center gap-2 rounded-xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Eliminar
                </button>
                <div class="flex items-center gap-3">
                    <button wire:click="$set('modalEditar', false)"
                            class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-500 transition hover:bg-slate-100 active:scale-95">
                        Cancelar
                    </button>
                    <button wire:click="actualizarFolder"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 px-5 py-2 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:from-blue-700 hover:to-blue-800 active:scale-95">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                        </svg>
                        Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- ════════════════════════════════════════════════════
         MODAL: REASIGNAR PACIENTES
    ════════════════════════════════════════════════════ --}}
    @if($modalReasignar)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm" wire:click.self="$set('modalReasignar', false)">
        <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10"
             x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div class="bg-gradient-to-br from-violet-600 to-violet-700 px-6 py-4 text-center">
                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">Reasignar Pacientes</h3>
            </div>

            <div class="p-6">
                <div class="rounded-xl bg-violet-50 p-4 text-sm leading-relaxed text-violet-700">
                    Al iniciar la reasignación, el sistema revisará todos los pacientes que actualmente no tienen un folder asignado o que se encuentran en un folder incorrecto, y los ubicará en el folder que corresponda según las reglas de negocio definidas. Esta operación no elimina ni modifica datos clínicos.
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <button wire:click="$set('modalReasignar', false)"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-500 transition hover:bg-slate-100 active:scale-95">
                    Cerrar
                </button>
                <button wire:click="iniciarReasignacion"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-violet-600 to-violet-700 px-5 py-2 text-sm font-semibold text-white shadow-sm shadow-violet-200 transition hover:from-violet-700 hover:to-violet-800 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"/>
                    </svg>
                    Iniciar Reasignación
                </button>
            </div>
        </div>
    </div>
    @endif


    {{-- ════════════════════════════════════════════════════
         MODAL: CONFIRMAR ELIMINACIÓN
    ════════════════════════════════════════════════════ --}}
    @if($modalEliminar && $folderSeleccionado)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm" wire:click.self="$set('modalEliminar', false)">
        <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10 text-center"
             x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div class="px-6 pt-8 pb-4">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-red-50">
                    <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">¿Eliminar este folder?</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">
                    Estás a punto de eliminar el folder <strong class="text-slate-700">{{ $folderSeleccionado['codigo_archivo'] }}</strong>.
                    Los pacientes vinculados quedarán <strong class="text-red-600">sin folder asignado</strong>.
                    Esta acción no se puede deshacer.
                </p>
            </div>

            <div class="flex items-center justify-center gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <button wire:click="$set('modalEliminar', false)"
                        class="rounded-xl px-5 py-2 text-sm font-semibold text-slate-500 transition hover:bg-slate-100 active:scale-95">
                    Cancelar
                </button>
                <button wire:click="eliminarFolder"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-red-500 to-red-600 px-5 py-2 text-sm font-semibold text-white shadow-sm shadow-red-200 transition hover:from-red-600 hover:to-red-700 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>