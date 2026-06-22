<div>
    {{-- ===================================================================== --}}
    {{-- FLASH: ÉXITO --}}
    {{-- ===================================================================== --}}
    @if (session()->has('mensaje'))
        <div class="fixed top-4 right-4 z-[100] bg-green-500 text-white px-5 py-3 rounded-lg shadow-lg flex items-center gap-2"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
             x-transition>
            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- FLASH: ERROR --}}
    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-[100] bg-red-500 text-white px-5 py-3 rounded-lg shadow-lg flex items-center gap-2"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4500)"
             x-transition>
            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="p-6">

        {{-- ================================================================= --}}
        {{-- VISTA 1: LISTA DE PLANES DE PAGO --}}
        {{-- ================================================================= --}}
        @if ($vista === 'lista')

            {{-- Cabecera --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Pagos de pacientes</h1>
                    <p class="text-xs text-gray-400">/ Finanzas / Pagos</p>
                </div>
                <button wire:click="abrirAgregarPlan"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow flex items-center gap-2 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Plan de Pago
                </button>
            </div>

            {{-- Buscador --}}
            <div class="bg-white rounded-xl shadow p-5 mb-4">
                <p class="text-center font-semibold text-gray-700 mb-3 text-sm">
                    Buscar por paciente:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input type="text"
                           wire:model.live.debounce.400ms="buscarNombre"
                           placeholder="Nombres del paciente"
                           class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <input type="text"
                           wire:model.live.debounce.400ms="buscarApellidos"
                           placeholder="Apellidos del paciente"
                           class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
            </div>

            {{-- Orden y Filtro --}}
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-600">Orden:</span>
                    <select wire:model.live="orden"
                            class="border border-gray-200 rounded-lg px-4 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <option value="recientes">Recién registrados</option>
                        <option value="antiguos">Más antiguos</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-600">Estado:</span>
                    <select wire:model.live="filtroPlan"
                            class="border border-gray-200 rounded-lg px-4 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <option value="todos">Todos los planes</option>
                        <option value="debe">Con saldo pendiente</option>
                        <option value="no_debe">Sin deuda</option>
                    </select>
                </div>
            </div>

            {{-- Tarjetas de planes --}}
            <div class="bg-white rounded-xl shadow divide-y divide-gray-100 overflow-hidden">
                @forelse ($this->planesFiltrados as $plan)
                    <div class="flex flex-wrap items-center gap-4 px-5 py-4 hover:bg-gray-50 transition
                                border-l-4 {{ $plan->saldo_pendiente > 0 ? 'border-red-400' : 'border-green-400' }}">

                        {{-- Paciente --}}
                        <div class="flex items-center gap-2 min-w-[170px]">
                            <div class="h-9 w-9 rounded-full bg-pink-100 flex items-center justify-center flex-shrink-0">
                                <svg class="h-5 w-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-700 text-sm leading-tight">{{ $plan->paciente_nombres }}</p>
                                <p class="text-xs text-gray-500 leading-tight">{{ $plan->paciente_apellidos }}</p>
                                <p class="text-xs text-gray-400">CI: {{ $plan->ci_dni }}</p>
                            </div>
                        </div>

                        {{-- Folder --}}
                        <div class="flex items-center gap-2 min-w-[100px]">
                            <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <div class="text-xs">
                                <p class="text-gray-400">Folder:</p>
                                <p class="font-semibold text-gray-700">{{ $plan->folder_codigo ?? 'S/N' }}</p>
                            </div>
                        </div>

                        {{-- Tratamiento / Precio --}}
                        <div class="flex items-center gap-2 min-w-[130px]">
                            <svg class="h-5 w-5 text-pink-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <div class="text-xs">
                                <p class="font-semibold text-gray-700">{{ $plan->tratamiento ?? '—' }}</p>
                                <p class="text-gray-400">Costo:</p>
                                <p class="font-semibold text-gray-700">Bs. {{ number_format($plan->costo_total, 2) }}</p>
                            </div>
                        </div>

                        {{-- Cuotas pagadas --}}
                        <div class="flex items-center gap-2 min-w-[110px]">
                            <svg class="h-5 w-5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div class="text-xs">
                                <p class="text-gray-400">{{ $plan->nro_cuotas_pagadas }} cuota(s)</p>
                                <p class="font-semibold text-gray-700">
                                    Bs. {{ number_format($plan->total_pagado, 2) }}
                                </p>
                            </div>
                        </div>

                        {{-- Estado de deuda --}}
                        <div class="flex items-center gap-2 min-w-[100px]">
                            <svg class="h-5 w-5 flex-shrink-0 {{ $plan->saldo_pendiente > 0 ? 'text-red-400' : 'text-green-500' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0
                                         2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21
                                         12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs">
                                @if ($plan->saldo_pendiente > 0)
                                    <p class="text-red-500 font-bold">Debe aún:</p>
                                    <p class="font-semibold text-red-500">
                                        Bs. {{ number_format($plan->saldo_pendiente, 2) }}
                                    </p>
                                @else
                                    <p class="text-green-600 font-bold">✓ Pagado</p>
                                    <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                                        {{ $plan->estado_plan }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Botón Revisar --}}
                        <div class="ml-auto">
                            <button wire:click="verDetalle({{ $plan->id_plan_pago }})"
                                    class="flex items-center gap-2 text-gray-500 hover:text-blue-500 font-semibold text-sm transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Revisar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-400">
                        <svg class="h-12 w-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414
                                     5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>No se encontraron planes de pago.</p>
                    </div>
                @endforelse
            </div>

        @endif


        {{-- ================================================================= --}}
        {{-- VISTA 2: DETALLE DEL PLAN DE PAGOS --}}
        {{-- ================================================================= --}}
        @if ($vista === 'detalle' && $this->planActivo)
            @php $plan = $this->planActivo; $pagos = $this->pagosDelPlan; @endphp

            {{-- Cabecera de detalle --}}
            <div class="flex items-center justify-between mb-4">
                <div>
                    <button wire:click="volverALista"
                            class="text-gray-400 hover:text-gray-600 text-xs mb-1 flex items-center gap-1">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a pagos
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Plan de Pagos del paciente</h1>
                    <p class="text-xs text-gray-400">
                        / Finanzas / Pagos / {{ $plan->paciente_nombres }} {{ $plan->paciente_apellidos }}
                    </p>
                </div>
                <button wire:click="abrirAgregarPlan"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition text-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2
                                 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Nuevo Plan
                </button>
            </div>

            {{-- Tarjeta resumen del paciente --}}
            <div class="bg-white rounded-xl shadow p-4 flex flex-wrap items-center gap-6 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-pink-100 flex items-center justify-center text-2xl font-bold text-pink-400">
                        {{ strtoupper(substr($plan->paciente_nombres, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-700">{{ $plan->paciente_nombres }}</p>
                        <p class="text-xs text-gray-500">{{ $plan->paciente_apellidos }}</p>
                        <p class="text-xs text-gray-400">CI: {{ $plan->ci_dni }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-sm">
                    <svg class="h-5 w-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9
                                 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-700">{{ $plan->tratamiento ?? '—' }}</p>
                        <p class="text-xs text-gray-500">{{ $plan->detalle_tratamiento ?? '' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-sm">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11
                                 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21
                                 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-400">Precio acordado:</p>
                        <p class="font-bold text-gray-700">Bs. {{ number_format($plan->costo_total, 2) }}</p>
                    </div>
                </div>

                <div class="ml-auto text-xs text-gray-400 flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Folder: {{ $plan->folder_codigo ?? 'S/N' }}
                </div>
            </div>

            {{-- Banner informativo --}}
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-5 text-sm text-gray-600">
                <span class="inline-flex items-center gap-1">
                    <svg class="h-4 w-4 text-orange-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Plan acordado el
                    <strong>{{ \Carbon\Carbon::parse($plan->fecha_creacion)->locale('es')->isoFormat('dddd D [de] MMMM [del] YYYY') }}</strong>
                    — Precio:
                    <strong>Bs. {{ number_format($plan->costo_total, 2) }}</strong>
                    por
                    <strong>{{ $plan->tratamiento ?? 'tratamiento' }}</strong>.
                    Estado actual:
                    <span class="inline-block bg-orange-100 text-orange-700 text-xs px-2 py-0.5 rounded-full font-bold ml-1">
                        {{ $plan->estado_plan }}
                    </span>
                </span>
            </div>

            <h2 class="text-center font-bold text-gray-700 mb-3">Pagos realizados</h2>

            {{-- Tabla de cuotas --}}
            <div class="bg-white rounded-xl shadow overflow-hidden mb-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gradient-to-r from-orange-500 to-red-600 text-white">
                                <th class="py-3 px-4 text-left font-semibold">#</th>
                                <th class="py-3 px-4 text-left font-semibold">Monto</th>
                                <th class="py-3 px-4 text-left font-semibold">Fecha / Hora</th>
                                <th class="py-3 px-4 text-left font-semibold">Método de pago</th>
                                <th class="py-3 px-4 text-left font-semibold">Comprobante N°</th>
                                <th class="py-3 px-4 text-left font-semibold">Asistente</th>
                                <th class="py-3 px-4 text-left font-semibold">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($pagos as $i => $pago)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-semibold text-gray-700">{{ $i + 1 }}</td>
                                    <td class="py-3 px-4 font-bold text-gray-700">
                                        Bs. {{ number_format($pago->monto_abonado, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 text-xs">
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}<br>
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i:s') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                                            {{ $pago->metodo_pago }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 text-xs font-mono">
                                        {{ $pago->nro_comprobante ?? '—' }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-500 text-xs">
                                        {{ $pago->asistente_nombre ?? 'Sistema' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3 text-gray-400">
                                            <button wire:click="abrirModificarCuota({{ $pago->id_pago }})"
                                                    title="Editar"
                                                    class="hover:text-orange-500 transition">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2
                                                             2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button wire:click="abrirEliminarCuota({{ $pago->id_pago }})"
                                                    title="Eliminar"
                                                    class="hover:text-red-500 transition">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5
                                                             7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-gray-400">
                                        Aún no se han registrado pagos para este plan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Resumen inferior --}}
            <div class="flex flex-wrap items-center justify-between bg-white rounded-xl shadow p-4 gap-4">
                <div class="flex flex-wrap gap-8 text-sm">
                    <div>
                        <p class="text-xs text-gray-400">{{ $plan->nro_cuotas_pagadas }} cuota(s) pagada(s)</p>
                        <p class="font-bold text-gray-700">Bs. {{ number_format($plan->total_pagado, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Costo total del plan</p>
                        <p class="font-bold text-gray-700">Bs. {{ number_format($plan->costo_total, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Saldo pendiente</p>
                        <p class="font-bold {{ $plan->saldo_pendiente > 0 ? 'text-red-500' : 'text-green-600' }}">
                            Bs. {{ number_format($plan->saldo_pendiente, 2) }}
                        </p>
                    </div>
                </div>
                @if ($plan->saldo_pendiente > 0)
                    <button wire:click="abrirAgregarCuota"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow flex items-center gap-2 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Registrar pago
                    </button>
                @else
                    <span class="inline-flex items-center gap-2 bg-green-50 text-green-700 font-semibold px-4 py-2.5 rounded-lg text-sm">
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
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <div class="bg-gradient-to-r from-orange-500 to-red-500 py-4 text-center relative">
                    <h2 class="text-white font-bold text-lg">
                        {{ $modoEdicion ? 'Modificar pago' : 'Registrar nuevo pago' }}
                    </h2>
                    <button wire:click="cerrarModalCuota"
                            class="absolute right-4 top-4 text-white/80 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">

                    {{-- Info del plan --}}
                    @if ($plan)
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                            <div>
                                <p class="font-bold text-gray-700 text-sm">{{ $plan->paciente_nombres }} {{ $plan->paciente_apellidos }}</p>
                                <p class="text-xs text-gray-500">{{ $plan->tratamiento ?? '—' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Saldo pendiente:</p>
                                <p class="font-bold text-red-500 text-base">
                                    Bs. {{ number_format($plan->saldo_pendiente, 2) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Formulario --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500">
                                Fecha y hora <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" wire:model="cuota_fecha"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('cuota_fecha')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">
                                Monto (Bs.) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.01" wire:model="cuota_monto"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('cuota_monto')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500">
                                Método de pago <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="cuota_metodo_pago"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm
                                           bg-white focus:outline-none focus:ring-2 focus:ring-orange-300">
                                @foreach ($metodosPago as $metodo)
                                    <option value="{{ $metodo }}">{{ $metodo }}</option>
                                @endforeach
                            </select>
                            @error('cuota_metodo_pago')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">N° Comprobante</label>
                            <input type="text" wire:model="cuota_comprobante"
                                   placeholder="Ej: REC-00123"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('cuota_comprobante')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if ($modoEdicion)
                        <div class="mb-4 bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <label class="text-xs font-semibold text-amber-700">
                                🔒 Contraseña de autorización (requerida para modificar)
                            </label>
                            <input type="password" wire:model="cuota_password"
                                   class="w-full border border-amber-200 rounded-lg px-4 py-2 mt-1 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-amber-300 bg-white">
                            @error('cuota_password')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <button wire:click="guardarCuota"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5
                                   rounded-lg flex items-center justify-center gap-2 transition mt-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0
                                     00-2 2v8a2 2 0 002 2z"/>
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
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">

                <div class="bg-gradient-to-r from-orange-500 to-red-500 py-4 text-center relative">
                    <h2 class="text-white font-bold text-lg">Nuevo Plan de Pago</h2>
                    <button wire:click="cerrarModalPlan"
                            class="absolute right-4 top-4 text-white/80 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">

                    {{-- Selector de paciente --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-500">
                            Paciente <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="plan_id_paciente"
                                class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm
                                       bg-white focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">— Seleccione un paciente —</option>
                            @foreach ($this->pacientes as $pac)
                                <option value="{{ $pac->id_paciente }}">
                                    {{ $pac->nombre_completo }} ({{ $pac->ci_dni }})
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id_paciente')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Selector de odontograma (se carga al seleccionar paciente) --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-500">
                            Odontograma / Tratamiento <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="plan_id_odontograma"
                                class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm
                                       bg-white focus:outline-none focus:ring-2 focus:ring-orange-300"
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
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                        @if ($plan_id_paciente && count($this->odontogramasDelPaciente) === 0)
                            <p class="text-xs text-amber-600 mt-1">
                                Este paciente no tiene odontogramas registrados.
                            </p>
                        @endif
                    </div>

                    {{-- Costo total acordado --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-500">
                            Precio total acordado (Bs.) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0.01" wire:model="plan_costo_total"
                               placeholder="Ej: 350.00"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 mt-1 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('plan_costo_total')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <button wire:click="guardarPlan"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold
                                   py-2.5 rounded-lg flex items-center justify-center gap-2 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                     01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden">

                <div class="bg-gradient-to-r from-red-500 to-red-600 py-4 text-center relative">
                    <h2 class="text-white font-bold text-lg">Eliminar pago</h2>
                    <button wire:click="$set('modalEliminarCuota', false)"
                            class="absolute right-4 top-4 text-white/80 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 text-center">
                    <svg class="h-14 w-14 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732
                                 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-gray-600 mb-2">¿Eliminar este pago?</p>
                    <p class="text-xs text-gray-400 mb-6">
                        El monto abonado será devuelto al saldo pendiente del plan.
                        Esta acción no se puede deshacer.
                    </p>
                    <div class="flex gap-3">
                        <button wire:click="$set('modalEliminarCuota', false)"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700
                                       font-semibold py-2.5 rounded-lg transition">
                            Cancelar
                        </button>
                        <button wire:click="eliminarCuota"
                                class="flex-1 bg-red-500 hover:bg-red-600 text-white
                                       font-semibold py-2.5 rounded-lg transition">
                            Sí, eliminar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>