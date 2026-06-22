<div wire:poll.60s class="min-h-screen bg-[#f5f8fc] text-slate-700">
<div class="max-w-[1400px] mx-auto p-6 space-y-6">

    {{-- ============================================================ --}}
    {{-- ENCABEZADO --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Panel principal</h1>
            <p class="text-xs text-slate-400 flex items-center gap-1 mt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                / dashboard
            </p>
        </div>
        <a href="{{ route('pacientes.index') ?? '#' }}" class="inline-flex items-center gap-2 bg-[#3b7af7] hover:bg-[#2f68dd] transition text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm shadow-blue-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h6"/></svg>
            Ver lista de pacientes
        </a>
    </div>

    {{-- ============================================================ --}}
    {{-- TARJETAS RESUMEN DE PACIENTES --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($resumenPacientes as $rp)
            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">{{ $rp['label'] }}</p>
                        <p class="text-xs text-slate-400">{{ $rp['sub'] }}</p>
                    </div>
                    <button class="text-slate-300 hover:text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                        @if($rp['icono'] == 'users')
                            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/></svg>
                        @elseif($rp['icono'] == 'user-female')
                            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @elseif($rp['icono'] == 'user-male')
                            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @else
                            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div>
                        <span class="text-3xl font-bold text-slate-800">{{ number_format($rp['total']) }}</span>
                        <span class="block text-xs font-medium text-slate-400">{{ $rp['pct'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- PIE SEXO + LINEA PACIENTES ULTIMOS DIAS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5" wire:ignore>
        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5 flex flex-col items-center">
            <div class="w-full h-56 relative">
                <canvas id="chartSexoPie"></canvas>
            </div>
            <p class="text-sm font-semibold text-slate-600 mt-3 text-center">Grafico del total de Pacientes<br>según sexo</p>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
            <div class="h-64 relative">
                <canvas id="chartPacientesDias"></canvas>
            </div>
            <p class="text-sm font-semibold text-slate-600 mt-2 text-center">Grafico de Pacientes atendidos en los últimos días</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ATENCIONES (hoy / ayer / antes de ayer) --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($atencionesPorDia as $i => $atencion)
            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">{{ $atencion['titulo'] }}</p>
                        <p class="text-xs text-slate-400">{{ $atencion['sub'] }}</p>
                    </div>
                    <button class="text-slate-300 hover:text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-3 mt-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
                        {{ $i === 0 ? 'bg-amber-100' : ($i === 1 ? 'bg-amber-100' : 'bg-orange-100') }}">
                        <svg class="w-6 h-6 {{ $i === 0 || $i === 1 ? 'text-amber-400' : 'text-orange-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-3xl font-bold text-slate-800">{{ $atencion['total'] }}</span>
                </div>
                <span class="inline-block mt-2 text-xs font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $atencion['detalle'] }}</span>

                <div class="mt-4 border-t border-slate-100 pt-3 space-y-1.5 max-h-44 overflow-y-auto pr-1">
                    @forelse($atencion['items'] as $name => $qty)
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500">{{ $name }}:</span>
                            <span class="font-semibold text-slate-700">{{ $qty }}</span>
                        </div>
                    @empty
                        <span class="text-xs text-slate-400 italic">No se registraron atenciones.</span>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- ESTA SEMANA / DONUT SEMANA / SEMANA ANTERIOR --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Esta semana</p>
                    <p class="text-xs text-slate-400">Se atendieron</p>
                </div>
                <button class="text-slate-300 hover:text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div class="flex items-center gap-3 mt-3">
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-3xl font-bold text-slate-800">{{ $estaSemana['total'] }}</span>
            </div>
            <span class="inline-block mt-2 text-xs font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $estaSemana['detalle'] }}</span>
            <div class="mt-4 border-t border-slate-100 pt-3 space-y-1.5 max-h-44 overflow-y-auto pr-1">
                @forelse($estaSemana['items'] as $name => $qty)
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">{{ $name }}:</span>
                        <span class="font-semibold text-slate-700">{{ $qty }}</span>
                    </div>
                @empty
                    <span class="text-xs text-slate-400 italic">Sin tratamientos esta semana.</span>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5 flex flex-col items-center justify-between" wire:ignore>
            <div class="w-full h-56 relative">
                <canvas id="chartTratamientosSemana"></canvas>
            </div>
            <p class="text-sm font-semibold text-slate-600 mt-2 text-center">Grafico de Tratamientos<br>realizados esta semana</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-700">La semana anterior</p>
                    <p class="text-xs text-slate-400">Se atendieron</p>
                </div>
                <button class="text-slate-300 hover:text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div class="flex items-center gap-3 mt-3">
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-3xl font-bold text-slate-800">{{ $semanaAnterior['total'] }}</span>
            </div>
            <span class="inline-block mt-2 text-xs font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $semanaAnterior['detalle'] }}</span>
            <div class="mt-4 border-t border-slate-100 pt-3 space-y-1.5 max-h-44 overflow-y-auto pr-1">
                @forelse($semanaAnterior['items'] as $name => $qty)
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">{{ $name }}:</span>
                        <span class="font-semibold text-slate-700">{{ $qty }}</span>
                    </div>
                @empty
                    <span class="text-xs text-slate-400 italic">Sin tratamientos la semana anterior.</span>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ESTE MES / DONUT MES / MES ANTERIOR --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Este mes</p>
                    <p class="text-xs text-slate-400">Se atendieron</p>
                </div>
                <button class="text-slate-300 hover:text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div class="flex items-center gap-3 mt-3">
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-3xl font-bold text-slate-800">{{ $esteMes['total'] }}</span>
            </div>
            <span class="inline-block mt-2 text-xs font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $esteMes['detalle'] }}</span>
            <div class="mt-4 border-t border-slate-100 pt-3 space-y-1.5 max-h-44 overflow-y-auto pr-1">
                @forelse($esteMes['items'] as $name => $qty)
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">{{ $name }}:</span>
                        <span class="font-semibold text-slate-700">{{ $qty }}</span>
                    </div>
                @empty
                    <span class="text-xs text-slate-400 italic">Sin tratamientos este mes.</span>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5 flex flex-col items-center justify-between" wire:ignore>
            <div class="w-full h-56 relative">
                <canvas id="chartTratamientosMes"></canvas>
            </div>
            <p class="text-sm font-semibold text-slate-600 mt-2 text-center">Grafico de Tratamientos<br>realizados este mes</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-700">El mes anterior</p>
                    <p class="text-xs text-slate-400">Se atendieron</p>
                </div>
                <button class="text-slate-300 hover:text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div class="flex items-center gap-3 mt-3">
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-3xl font-bold text-slate-800">{{ $mesAnterior['total'] }}</span>
            </div>
            <span class="inline-block mt-2 text-xs font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $mesAnterior['detalle'] }}</span>
            <div class="mt-4 border-t border-slate-100 pt-3 space-y-1.5 max-h-44 overflow-y-auto pr-1">
                @forelse($mesAnterior['items'] as $name => $qty)
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">{{ $name }}:</span>
                        <span class="font-semibold text-slate-700">{{ $qty }}</span>
                    </div>
                @empty
                    <span class="text-xs text-slate-400 italic">Sin tratamientos el mes anterior.</span>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- RADAR TRIMESTRAL + LINEA PACIENTES ULTIMOS MESES --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5" wire:ignore>
        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5 flex flex-col items-center justify-between">
            <div class="w-full h-56 relative">
                <canvas id="chartTratamientosRadar"></canvas>
            </div>
            <p class="text-sm font-semibold text-slate-600 mt-2 text-center">Tratamientos más solicitados</p>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
            <div class="h-64 relative">
                <canvas id="chartPacientesMeses"></canvas>
            </div>
            <p class="text-sm font-semibold text-slate-600 mt-2 text-center">Grafico de Pacientes atendidos en los últimos meses</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- PAGOS DE PACIENTES (hoy / ayer / antes de ayer) --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($pagosPorDia as $i => $pago)
            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">{{ $pago['titulo'] }}</p>
                        <p class="text-xs text-slate-400">{{ $pago['sub'] }}</p>
                    </div>
                    <button class="text-slate-300 hover:text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-3 mt-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
                        {{ $i === 0 ? 'bg-amber-100' : ($i === 1 ? 'bg-amber-100' : 'bg-orange-100') }}">
                        <svg class="w-6 h-6 {{ $i === 0 || $i === 1 ? 'text-amber-400' : 'text-orange-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block">S/.</span>
                        <span class="text-2xl font-bold text-slate-800">{{ number_format($pago['total'], 0) }}</span>
                    </div>
                </div>
                <span class="inline-block mt-2 text-xs font-mono text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $pago['cant'] }}</span>

                <div class="mt-4 border-t border-slate-100 pt-3 space-y-2.5 max-h-44 overflow-y-auto pr-1">
                    @forelse($pago['items'] as $item)
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-600 font-medium truncate max-w-[140px]">{{ $item['nombre'] }}</span>
                            <span class="font-bold text-orange-500">S/. {{ number_format($item['monto'], 2) }}</span>
                        </div>
                    @empty
                        <span class="text-xs text-slate-400 italic">Sin pagos registrados.</span>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- DONUT PAGOS POR DIA + BARRAS PAGOS POR MES --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5" wire:ignore>
        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5 flex flex-col items-center justify-between">
            <div class="w-full h-56 relative">
                <canvas id="chartPagosDonut"></canvas>
            </div>
            <p class="text-sm font-semibold text-slate-600 mt-2 text-center">Grafico de Pagos realizados por Pacientes<br>en los últimos días</p>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-5">
            <div class="h-64 relative">
                <canvas id="chartPagosMeses"></canvas>
            </div>
            <p class="text-sm font-semibold text-slate-600 mt-2 text-center">Grafico de Pagos realizados por Pacientes en los últimos meses</p>
        </div>
    </div>

</div>

@script
<script>
    const chartData = @json($chartData);

    // Paleta naranja/durazno consistente con el diseño de referencia
    const PALETA = ['#fb923c', '#f97316', '#fdba74', '#fed7aa', '#ffedd5', '#fb7185', '#fbbf24', '#f59e0b', '#ea580c', '#c2410c', '#fca5a5', '#fdba8c'];
    const NARANJA = '#fb923c';
    const NARANJA_SUAVE = 'rgba(251, 146, 60, 0.12)';

    Chart.defaults.font.family = "'Inter', ui-sans-serif, system-ui, sans-serif";
    Chart.defaults.color = '#94a3b8';

    // ----------------------------------------------------------------
    // 1. PIE - Pacientes por sexo (CORREGIDO: ahora data realmente
    //    refleja Mujer/Varón/No definido sin sesgo)
    // ----------------------------------------------------------------
    const ctxSexoPie = document.getElementById('chartSexoPie').getContext('2d');
    const chartSexoPie = new Chart(ctxSexoPie, {
        type: 'pie',
        data: {
            labels: chartData.pacientesSexo.labels,
            datasets: [{
                data: chartData.pacientesSexo.data,
                backgroundColor: ['#fed7aa', '#fdba74', '#ffedd5'],
                borderColor: '#ffffff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 }, color: '#fb923c' } } }
        }
    });

    // ----------------------------------------------------------------
    // 2. LINEA - Pacientes atendidos últimos días (por sexo)
    // ----------------------------------------------------------------
    const ctxPacDias = document.getElementById('chartPacientesDias').getContext('2d');
    new Chart(ctxPacDias, {
        type: 'line',
        data: {
            labels: chartData.pacientesUltimosDias.labels,
            datasets: [
                { label: 'Mujer',     data: chartData.pacientesUltimosDias.mujer, borderColor: '#fb923c', backgroundColor: 'rgba(251,146,60,0.15)', fill: true, tension: 0.35, pointRadius: 3 },
                { label: 'Varón',     data: chartData.pacientesUltimosDias.varon, borderColor: '#fdba74', backgroundColor: 'rgba(253,186,116,0.10)', fill: true, tension: 0.35, pointRadius: 3 },
                { label: 'No definido', data: chartData.pacientesUltimosDias.noDef, borderColor: '#ffd9b3', backgroundColor: 'rgba(255,217,179,0.10)', fill: true, tension: 0.35, pointRadius: 3 },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });

    // ----------------------------------------------------------------
    // 3. DONUT - Tratamientos esta semana
    // ----------------------------------------------------------------
    const ctxTratSemana = document.getElementById('chartTratamientosSemana').getContext('2d');
    new Chart(ctxTratSemana, {
        type: 'doughnut',
        data: {
            labels: chartData.tratamientosSemana.labels,
            datasets: [{
                data: chartData.tratamientosSemana.data,
                backgroundColor: PALETA,
                borderColor: '#ffffff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 8, font: { size: 9 } } } }
        }
    });

    // ----------------------------------------------------------------
    // 4. DONUT - Tratamientos este mes
    // ----------------------------------------------------------------
    const ctxTratMes = document.getElementById('chartTratamientosMes').getContext('2d');
    new Chart(ctxTratMes, {
        type: 'pie',
        data: {
            labels: chartData.tratamientosMes.labels,
            datasets: [{
                data: chartData.tratamientosMes.data,
                backgroundColor: PALETA,
                borderColor: '#ffffff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 8, font: { size: 9 } } } }
        }
    });

    // ----------------------------------------------------------------
    // 5. RADAR - Top tratamientos últimos 3 meses
    // ----------------------------------------------------------------
    const ctxRadar = document.getElementById('chartTratamientosRadar').getContext('2d');
    const coloresRadarFondo = ['rgba(251,146,60,0.15)', 'rgba(253,186,116,0.15)', 'rgba(254,215,170,0.20)'];
    const coloresRadarBorde = ['#fb923c', '#fdba74', '#fdba8c'];

    const datasetsRadar = chartData.tratamientosRadar.datasets.map((dataset, idx) => ({
        label: dataset.label,
        data: dataset.data,
        backgroundColor: coloresRadarFondo[idx] || 'rgba(148,163,184,0.15)',
        borderColor: coloresRadarBorde[idx] || '#94a3b8',
        borderWidth: 2,
        borderDash: [4, 3],
        pointBackgroundColor: coloresRadarBorde[idx] || '#94a3b8',
        pointRadius: 3,
    }));

    new Chart(ctxRadar, {
        type: 'radar',
        data: { labels: chartData.tratamientosRadar.labels, datasets: datasetsRadar },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 10 } } } },
            scales: { r: { pointLabels: { font: { size: 9 } }, ticks: { display: true, font: { size: 8 }, backdropColor: 'transparent' }, grid: { color: '#f1f5f9' } } }
        }
    });

    // ----------------------------------------------------------------
    // 6. LINEA - Pacientes atendidos últimos meses (total + sexo)
    // ----------------------------------------------------------------
    const ctxPacMeses = document.getElementById('chartPacientesMeses').getContext('2d');
    new Chart(ctxPacMeses, {
        type: 'line',
        data: {
            labels: chartData.pacientesUltimosMeses.labels,
            datasets: [
                { label: 'Total Personas', data: chartData.pacientesUltimosMeses.total, borderColor: '#fbbf24', backgroundColor: 'transparent', borderDash: [5, 4], tension: 0.3, pointRadius: 3 },
                { label: 'Mujer', data: chartData.pacientesUltimosMeses.mujer, borderColor: '#fb923c', backgroundColor: 'transparent', borderDash: [5, 4], tension: 0.3, pointRadius: 3 },
                { label: 'Varón', data: chartData.pacientesUltimosMeses.varon, borderColor: '#fdba74', backgroundColor: 'transparent', borderDash: [5, 4], tension: 0.3, pointRadius: 3 },
                { label: 'No definido', data: chartData.pacientesUltimosMeses.noDef, borderColor: '#ffd9b3', backgroundColor: 'transparent', borderDash: [5, 4], tension: 0.3, pointRadius: 3 },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 10 } } } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });

    // ----------------------------------------------------------------
    // 7. DONUT - Pagos por día (últimos 8)
    // ----------------------------------------------------------------
    const ctxPagosDonut = document.getElementById('chartPagosDonut').getContext('2d');
    new Chart(ctxPagosDonut, {
        type: 'doughnut',
        data: {
            labels: chartData.pagosPorDia.labels,
            datasets: [{
                data: chartData.pagosPorDia.data,
                backgroundColor: PALETA,
                borderColor: '#ffffff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 9 } } } }
        }
    });

    // ----------------------------------------------------------------
    // 8. BARRAS - Pagos por mes con línea de tendencia
    // ----------------------------------------------------------------
    const ctxPagosMeses = document.getElementById('chartPagosMeses').getContext('2d');
    new Chart(ctxPagosMeses, {
        data: {
            labels: chartData.pagosPorMes.labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Pagos Mensuales',
                    data: chartData.pagosPorMes.data,
                    backgroundColor: chartData.pagosPorMes.data.map((_, i, arr) => {
                        const t = arr.length > 1 ? i / (arr.length - 1) : 0;
                        return `rgba(251, ${Math.round(191 - t * 80)}, ${Math.round(146 - t * 100)}, 0.85)`;
                    }),
                    borderRadius: 4,
                },
                {
                    type: 'line',
                    label: 'Tendencia',
                    data: chartData.pagosPorMes.data,
                    borderColor: '#475569',
                    borderDash: [4, 3],
                    backgroundColor: 'transparent',
                    pointBackgroundColor: '#f97316',
                    pointRadius: 3,
                    tension: 0.3,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });

    // ----------------------------------------------------------------
    // RE-RENDERIZADO LIVEWIRE (poll cada 60s) - actualiza datasets
    // sin recrear los charts para evitar parpadeo.
    // ----------------------------------------------------------------
    Livewire.hook('morph.updated', () => {
        // Si necesitas refrescar datasets tras el poll, vuelve a
        // emitir chartData y actualiza aquí cada chart.update().
    });
</script>
@endscript
</div>