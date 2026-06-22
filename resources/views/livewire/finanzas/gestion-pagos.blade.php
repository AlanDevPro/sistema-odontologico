<div>

    {{-- ===================================================================== --}}
    {{-- FLASH: ÉXITO --}}
    {{-- ===================================================================== --}}
    @if (session()->has('mensaje'))
        <div class="fixed top-5 right-5 z-[100] max-w-sm"
             x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 3500)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-6 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-6 scale-95">
            <div class="flex items-start gap-3 bg-white border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl shadow-lg shadow-emerald-100/60">
                <span class="flex-shrink-0 mt-0.5 h-5 w-5 rounded-full bg-emerald-500 flex items-center justify-center">
                    <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                </span>
                <p class="text-sm font-medium leading-snug">{{ session('mensaje') }}</p>
            </div>
        </div>
    @endif

    {{-- FLASH: ERROR --}}
    @if (session()->has('error'))
        <div class="fixed top-5 right-5 z-[100] max-w-sm"
             x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 4500)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-6 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-6 scale-95">
            <div class="flex items-start gap-3 bg-white border border-rose-200 text-rose-800 px-4 py-3.5 rounded-2xl shadow-lg shadow-rose-100/60">
                <span class="flex-shrink-0 mt-0.5 h-5 w-5 rounded-full bg-rose-500 flex items-center justify-center">
                    <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
                <p class="text-sm font-medium leading-snug">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- ================================================================= --}}
    {{-- FONDO DE PÁGINA --}}
    {{-- ================================================================= --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 p-6">

        {{-- ============================================================= --}}
        {{-- VISTA 1: LISTA DE PLANES DE PAGO --}}
        {{-- ============================================================= --}}
        @if ($vista === 'lista')

            {{-- Cabecera --}}
            <div class="flex items-center justify-between mb-7">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-blue-600 shadow-md shadow-blue-200">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </span>
                        <h1 class="text-2xl font-bold text-slate-800">Pagos de pacientes</h1>
                    </div>
                    <p class="text-xs text-slate-400 ml-11">Finanzas &rsaquo; Pagos</p>
                </div>
                <button wire:click="abrirAgregarPlan"
                        class="group relative inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 transition-all duration-200">
                    <svg class="h-4 w-4 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Plan de Pago
                </button>
            </div>

            {{-- Buscador elevado --}}
            <div class="bg-white rounded-2xl shadow-sm shadow-slate-200 border border-slate-100 p-5 mb-4 ring-1 ring-slate-100/80">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">Buscar paciente</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input type="text"
                               wire:model.live.debounce.400ms="buscarNombre"
                               placeholder="Nombres del paciente"
                               class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all placeholder-slate-400">
                    </div>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input type="text"
                               wire:model.live.debounce.400ms="buscarApellidos"
                               placeholder="Apellidos del paciente"
                               class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all placeholder-slate-400">
                    </div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="flex flex-wrap items-center gap-3 mb-5">
                <div class="flex items-center gap-2 bg-white border border-slate-100 rounded-xl px-3 py-2 shadow-sm">
                    <svg class="h-4 w-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Orden:</span>
                    <select wire:model.live="orden"
                            class="border-0 text-sm font-medium text-slate-700 bg-transparent focus:outline-none focus:ring-0 pr-1">
                        <option value="recientes">Recién registrados</option>
                        <option value="antiguos">Más antiguos</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 bg-white border border-slate-100 rounded-xl px-3 py-2 shadow-sm">
                    <svg class="h-4 w-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Estado:</span>
                    <select wire:model.live="filtroPlan"
                            class="border-0 text-sm font-medium text-slate-700 bg-transparent focus:outline-none focus:ring-0 pr-1">
                        <option value="todos">Todos</option>
                        <option value="debe">Con deuda</option>
                        <option value="no_debe">Sin deuda</option>
                    </select>
                </div>
            </div>

            {{-- Tarjetas de planes --}}
            <div class="space-y-3">
                @forelse ($this->planesFiltrados as $plan)
                    <div class="group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-200 overflow-hidden">

                        {{-- Barra de color lateral --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl {{ $plan->saldo_pendiente > 0 ? 'bg-rose-400' : 'bg-emerald-400' }}"></div>

                        <div class="flex flex-wrap items-center gap-4 px-6 py-4 pl-7">

                            {{-- Paciente --}}
                            <div class="flex items-center gap-3 min-w-[175px]">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 text-sm leading-tight">{{ $plan->paciente_nombres }}</p>
                                    <p class="text-xs text-slate-500 leading-tight">{{ $plan->paciente_apellidos }}</p>
                                    <span class="inline-block mt-0.5 text-xs text-slate-400 font-mono bg-slate-50 px-1.5 py-0.5 rounded-md border border-slate-100">
                                        CI: {{ $plan->ci_dni }}
                                    </span>
                                </div>
                            </div>

                            {{-- Folder --}}
                            <div class="flex items-center gap-2 min-w-[105px]">
                                <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                </div>
                                <div class="text-xs">
                                    <p class="text-slate-400 font-medium">Folder</p>
                                    <p class="font-bold text-slate-700">{{ $plan->folder_codigo ?? 'S/N' }}</p>
                                </div>
                            </div>

                            {{-- Tratamiento / Precio --}}
                            <div class="flex items-center gap-2 min-w-[140px]">
                                <div class="h-8 w-8 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-4 w-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="text-xs">
                                    <p class="font-bold text-slate-700 leading-tight">{{ $plan->tratamiento ?? '—' }}</p>
                                    <p class="text-slate-400 font-medium mt-0.5">Costo total</p>
                                    <p class="font-bold text-slate-700">Bs. {{ number_format($plan->costo_total, 2) }}</p>
                                </div>
                            </div>

                            {{-- Cuotas pagadas --}}
                            <div class="flex items-center gap-2 min-w-[120px]">
                                <div class="h-8 w-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="text-xs">
                                    <p class="text-slate-400 font-medium">{{ $plan->nro_cuotas_pagadas }} cuota(s)</p>
                                    <p class="font-bold text-slate-700">
                                        Bs. {{ number_format($plan->total_pagado, 2) }}
                                    </p>
                                </div>
                            </div>

                            {{-- Estado de deuda --}}
                            <div class="flex items-center gap-2 min-w-[110px]">
                                @if ($plan->saldo_pendiente > 0)
                                    <div class="h-8 w-8 rounded-lg bg-rose-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-xs">
                                        <p class="text-rose-500 font-semibold">Saldo pendiente</p>
                                        <p class="font-bold text-rose-600 text-sm">
                                            Bs. {{ number_format($plan->saldo_pendiente, 2) }}
                                        </p>
                                    </div>
                                @else
                                    <div class="h-8 w-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                    </div>
                                    <div class="text-xs">
                                        <p class="text-emerald-600 font-bold">Completado</p>
                                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                                            {{ $plan->estado_plan }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Botón Revisar --}}
                            <div class="ml-auto">
                                <button wire:click="verDetalle({{ $plan->id_plan_pago }})"
                                        class="group/btn inline-flex items-center gap-2 bg-slate-50 hover:bg-blue-600 border border-slate-200 hover:border-blue-600 text-slate-500 hover:text-white font-semibold text-sm px-4 py-2 rounded-xl transition-all duration-200 active:scale-95">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Revisar
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-dashed border-slate-200 py-16 text-center">
                        <div class="h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">No se encontraron planes de pago.</p>
                        <p class="text-slate-400 text-sm mt-1">Ajusta los filtros o crea un nuevo plan.</p>
                    </div>
                @endforelse
            </div>

        @endif


        {{-- ============================================================= --}}
        {{-- VISTA 2: DETALLE DEL PLAN --}}
        {{-- ============================================================= --}}
        @if ($vista === 'detalle' && $this->planActivo)
            @php $plan = $this->planActivo; $pagos = $this->pagosDelPlan; @endphp

            {{-- Cabecera de detalle --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <button wire:click="volverALista"
                            class="inline-flex items-center gap-1.5 text-slate-400 hover:text-blue-600 text-xs font-medium mb-2 transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a pagos
                    </button>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-blue-600 shadow-md shadow-blue-200">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        <h1 class="text-xl font-bold text-slate-800">Plan de Pagos del paciente</h1>
                    </div>
                    <p class="text-xs text-slate-400 ml-11">Finanzas &rsaquo; Pagos &rsaquo; {{ $plan->paciente_nombres }} {{ $plan->paciente_apellidos }}</p>
                </div>
                <button wire:click="abrirAgregarPlan"
                        class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 hover:text-slate-800 font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 text-sm active:scale-95">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Nuevo Plan
                </button>
            </div>

            {{-- Tarjeta resumen del paciente --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-wrap items-center gap-6 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-100 to-rose-200 flex items-center justify-center text-xl font-bold text-rose-500 shadow-sm">
                        {{ strtoupper(substr($plan->paciente_nombres, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-base">{{ $plan->paciente_nombres }}</p>
                        <p class="text-sm text-slate-500">{{ $plan->paciente_apellidos }}</p>
                        <span class="inline-block mt-0.5 text-xs text-slate-400 font-mono bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100">CI: {{ $plan->ci_dni }}</span>
                    </div>
                </div>

                <div class="h-10 w-px bg-slate-100 hidden sm:block"></div>

                <div class="flex items-center gap-2">
                    <div class="h-9 w-9 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-slate-700 text-sm">{{ $plan->tratamiento ?? '—' }}</p>
                        <p class="text-xs text-slate-400">{{ $plan->detalle_tratamiento ?? '' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="h-9 w-9 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Precio acordado</p>
                        <p class="font-bold text-slate-800 text-base">Bs. {{ number_format($plan->costo_total, 2) }}</p>
                    </div>
                </div>

                <div class="ml-auto flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-xl">
                    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="text-xs font-bold text-blue-700">{{ $plan->folder_codigo ?? 'S/N' }}</span>
                </div>
            </div>

            {{-- Banner informativo --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-5 flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5 h-7 w-7 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-amber-800 leading-relaxed">
                    Plan acordado el
                    <strong>{{ \Carbon\Carbon::parse($plan->fecha_creacion)->locale('es')->isoFormat('dddd D [de] MMMM [del] YYYY') }}</strong>
                    — Precio: <strong>Bs. {{ number_format($plan->costo_total, 2) }}</strong>
                    por <strong>{{ $plan->tratamiento ?? 'tratamiento' }}</strong>.
                    Estado:
                    <span class="inline-block bg-amber-200 text-amber-800 text-xs px-2.5 py-0.5 rounded-full font-bold ml-1">
                        {{ $plan->estado_plan }}
                    </span>
                </p>
            </div>

            <h2 class="text-center font-bold text-slate-700 mb-3 flex items-center justify-center gap-2">
                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Pagos realizados
            </h2>

            {{-- Tabla de cuotas --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                                <th class="py-3.5 px-4 text-left font-semibold text-xs uppercase tracking-wide">#</th>
                                <th class="py-3.5 px-4 text-left font-semibold text-xs uppercase tracking-wide">Monto</th>
                                <th class="py-3.5 px-4 text-left font-semibold text-xs uppercase tracking-wide">Fecha / Hora</th>
                                <th class="py-3.5 px-4 text-left font-semibold text-xs uppercase tracking-wide">Método</th>
                                <th class="py-3.5 px-4 text-left font-semibold text-xs uppercase tracking-wide">Comprobante</th>
                                <th class="py-3.5 px-4 text-left font-semibold text-xs uppercase tracking-wide">Asistente</th>
                                <th class="py-3.5 px-4 text-left font-semibold text-xs uppercase tracking-wide">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($pagos as $i => $pago)
                                <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-lg bg-slate-100 text-slate-600 font-bold text-xs">{{ $i + 1 }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="font-bold text-slate-800">Bs. {{ number_format($pago->monto_abonado, 2) }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs">
                                        <p class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</p>
                                        <p class="text-slate-400 font-mono">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i:s') }}</p>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-lg font-semibold border border-blue-100">
                                            {{ $pago->metodo_pago }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="text-slate-600 text-xs font-mono bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                                            {{ $pago->nro_comprobante ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-slate-500">
                                        {{ $pago->asistente_nombre ?? 'Sistema' }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2">
                                            <button wire:click="abrirModificarCuota({{ $pago->id_pago }})"
                                                    title="Editar"
                                                    class="h-8 w-8 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-100 text-amber-500 hover:text-amber-700 flex items-center justify-center transition-all duration-150 active:scale-90">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button wire:click="abrirEliminarCuota({{ $pago->id_pago }})"
                                                    title="Eliminar"
                                                    class="h-8 w-8 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-400 hover:text-rose-600 flex items-center justify-center transition-all duration-150 active:scale-90">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-14 text-center">
                                        <div class="h-12 w-12 rounded-xl bg-slate-50 flex items-center justify-center mx-auto mb-3">
                                            <svg class="h-6 w-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">Aún no hay pagos registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Resumen inferior --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap gap-6 text-sm">
                    <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                        <p class="text-xs text-slate-400 font-medium mb-0.5">{{ $plan->nro_cuotas_pagadas }} cuota(s) pagada(s)</p>
                        <p class="font-bold text-slate-800 text-base">Bs. {{ number_format($plan->total_pagado, 2) }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                        <p class="text-xs text-slate-400 font-medium mb-0.5">Costo total del plan</p>
                        <p class="font-bold text-slate-800 text-base">Bs. {{ number_format($plan->costo_total, 2) }}</p>
                    </div>
                    <div class="{{ $plan->saldo_pendiente > 0 ? 'bg-rose-50 border-rose-100' : 'bg-emerald-50 border-emerald-100' }} rounded-xl px-4 py-3 border">
                        <p class="text-xs {{ $plan->saldo_pendiente > 0 ? 'text-rose-400' : 'text-emerald-500' }} font-medium mb-0.5">Saldo pendiente</p>
                        <p class="font-bold {{ $plan->saldo_pendiente > 0 ? 'text-rose-600' : 'text-emerald-600' }} text-base">
                            Bs. {{ number_format($plan->saldo_pendiente, 2) }}
                        </p>
                    </div>
                </div>
                @if ($plan->saldo_pendiente > 0)
                    <button wire:click="abrirAgregarCuota"
                            class="group inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 transition-all duration-200 active:scale-95">
                        <svg class="h-5 w-5 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Registrar pago
                    </button>
                @else
                    <span class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold px-5 py-2.5 rounded-xl text-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Plan completamente pagado
                    </span>
                @endif
            </div>

        @endif
    </div>


    {{-- ===================================================================== --}}
    {{-- MODAL: AGREGAR / MODIFICAR CUOTA --}}
    {{-- ===================================================================== --}}
    @if ($modalCuota)
        @php $plan = $this->planActivo; @endphp
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden"
                 x-data x-init="$el.classList.add('scale-100'); $el.classList.remove('scale-95')"
                 style="transform: scale(0.95); transition: transform 0.15s ease-out;">

                {{-- Header del modal --}}
                <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 py-5 text-center">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 h-9 w-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-white font-bold text-lg">
                        {{ $modoEdicion ? 'Modificar pago' : 'Registrar nuevo pago' }}
                    </h2>
                    <button wire:click="cerrarModalCuota"
                            class="absolute right-4 top-1/2 -translate-y-1/2 h-8 w-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">

                    {{-- Info del plan --}}
                    @if ($plan)
                        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                            <div>
                                <p class="font-bold text-slate-800 text-sm">{{ $plan->paciente_nombres }} {{ $plan->paciente_apellidos }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $plan->tratamiento ?? '—' }}</p>
                            </div>
                            <div class="text-right bg-rose-50 border border-rose-100 rounded-xl px-3 py-2">
                                <p class="text-xs text-rose-400 font-medium">Saldo pendiente</p>
                                <p class="font-bold text-rose-600 text-base">
                                    Bs. {{ number_format($plan->saldo_pendiente, 2) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Formulario --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Fecha y hora <span class="text-rose-500">*</span>
                            </label>
                            <input type="datetime-local" wire:model="cuota_fecha"
                                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all">
                            @error('cuota_fecha')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Monto (Bs.) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.01" wire:model="cuota_monto"
                                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all">
                            @error('cuota_monto')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Método de pago <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="cuota_metodo_pago"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all">
                                @foreach ($metodosPago as $metodo)
                                    <option value="{{ $metodo }}">{{ $metodo }}</option>
                                @endforeach
                            </select>
                            @error('cuota_metodo_pago')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">N° Comprobante</label>
                            <input type="text" wire:model="cuota_comprobante"
                                   placeholder="Ej: REC-00123"
                                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all placeholder-slate-300">
                            @error('cuota_comprobante')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if ($modoEdicion)
                        <div class="mb-4 bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <label class="text-xs font-bold text-amber-800">Contraseña de autorización requerida</label>
                            </div>
                            <input type="password" wire:model="cuota_password"
                                   class="w-full border border-amber-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition-all">
                            @error('cuota_password')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <button wire:click="guardarCuota"
                            class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-all duration-200 shadow-md shadow-blue-200 mt-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $modoEdicion ? 'Guardar cambios' : 'Registrar pago' }}
                    </button>

                </div>
            </div>
        </div>
    @endif


    {{-- ===================================================================== --}}
    {{-- MODAL: CREAR NUEVO PLAN DE PAGO --}}
    {{-- ===================================================================== --}}
    @if ($modalPlan)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

                <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 py-5 text-center">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 h-9 w-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-white font-bold text-lg">Nuevo Plan de Pago</h2>
                    <button wire:click="cerrarModalPlan"
                            class="absolute right-4 top-1/2 -translate-y-1/2 h-8 w-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Paciente <span class="text-rose-500">*</span>
                        </label>
                        <select wire:model.live="plan_id_paciente"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all">
                            <option value="">— Seleccione un paciente —</option>
                            @foreach ($this->pacientes as $pac)
                                <option value="{{ $pac->id_paciente }}">
                                    {{ $pac->nombre_completo }} ({{ $pac->ci_dni }})
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id_paciente')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Odontograma / Tratamiento <span class="text-rose-500">*</span>
                        </label>
                        <select wire:model="plan_id_odontograma"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ !$plan_id_paciente ? 'disabled' : '' }}>
                            <option value="">— Seleccione un odontograma —</option>
                            @foreach ($this->odontogramasDelPaciente as $odo)
                                <option value="{{ $odo->id_odontograma }}">
                                    {{ \Carbon\Carbon::parse($odo->fecha_evaluacion)->format('d/m/Y') }}
                                    — {{ $odo->tratamiento_principal ?? 'Sin tratamiento' }}
                                    (Dr. {{ $odo->doctor }})
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id_odontograma')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                        @if ($plan_id_paciente && count($this->odontogramasDelPaciente) === 0)
                            <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Este paciente no tiene odontogramas registrados.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Precio total acordado (Bs.) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0.01" wire:model="plan_costo_total"
                               placeholder="Ej: 350.00"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all placeholder-slate-300">
                        @error('plan_costo_total')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button wire:click="guardarPlan"
                            class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-all duration-200 shadow-md shadow-blue-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Plan de Pago
                    </button>

                </div>
            </div>
        </div>
    @endif


    {{-- ===================================================================== --}}
    {{-- MODAL: CONFIRMAR ELIMINACIÓN DE CUOTA --}}
    {{-- ===================================================================== --}}
    @if ($modalEliminarCuota)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">

                <div class="relative bg-gradient-to-r from-rose-500 to-red-600 py-5 text-center">
                    <h2 class="text-white font-bold text-lg">Eliminar pago</h2>
                    <button wire:click="$set('modalEliminarCuota', false)"
                            class="absolute right-4 top-1/2 -translate-y-1/2 h-8 w-8 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 text-center">
                    <div class="h-16 w-16 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-base mb-2">¿Eliminar este pago?</h3>
                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                        El monto abonado volverá al saldo pendiente del plan.<br>
                        Esta acción no se puede deshacer.
                    </p>
                    <div class="flex gap-3">
                        <button wire:click="$set('modalEliminarCuota', false)"
                                class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 rounded-xl transition-all active:scale-95">
                            Cancelar
                        </button>
                        <button wire:click="eliminarCuota"
                                class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-semibold py-2.5 rounded-xl transition-all shadow-md shadow-rose-200 active:scale-95">
                            Sí, eliminar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>