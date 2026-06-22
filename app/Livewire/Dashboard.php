<?php

namespace App\Livewire;

use App\Models\DetalleOdontograma;
use App\Models\Pago;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    // Refresca el dashboard cada 60s sin recargar la página si se requiere en el futuro.
    protected $listeners = [];

    /**
     * Renderiza la vista del componente asociando explícitamente el layout de Jetstream.
     */
    public function render()
    {
        return view('livewire.dashboard', [
            'resumenPacientes'    => $this->resumenPacientes(),
            'atencionesPorDia'    => $this->atencionesUltimosNDias(3),
            'estaSemana'          => $this->tratamientosEnRango(now()->startOfWeek(), now()->endOfWeek()),
            'semanaAnterior'      => $this->tratamientosEnRango(now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()),
            'esteMes'             => $this->tratamientosEnRango(now()->startOfMonth(), now()->endOfMonth()),
            'mesAnterior'         => $this->tratamientosEnRango(now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()),
            'pagosPorDia'         => $this->pagosUltimosNDias(3),
            'chartData'           => $this->datosParaGraficos(),
        ])->layout('components.dashboard-layout'); // <-- SOLUCIÓN LIMPIA: Fuerza el uso del layout de Jetstream
    }

    // ----------------------------------------------------------------
    // HELPER: condición de sexo segura y reutilizable.
    // Evita el bug de precedencia de operadores con whereNotIn()->orWhereNull()
    // suelto, que en Oracle (NOT IN + NULL = UNKNOWN) deja resultados
    // inconsistentes y "rompe" los conteos de Mujer/Varón/No definido.
    // ----------------------------------------------------------------
    private function scopeNoDefinido($query, string $columnaSexo = 'sexo')
    {
        return $query->where(function ($q) use ($columnaSexo) {
            $q->whereNotIn($columnaSexo, ['F', 'M'])
              ->orWhereNull($columnaSexo);
        });
    }

    // ----------------------------------------------------------------
    // 1. TARJETAS RESUMEN DE PACIENTES
    // ----------------------------------------------------------------
    private function resumenPacientes(): array
    {
        $total   = Paciente::count();
        $mujeres = Paciente::where('sexo', 'F')->count();
        $varones = Paciente::where('sexo', 'M')->count();
        $noDef   = $this->scopeNoDefinido(Paciente::query())->count();

        $pct = fn (int $n) => $total > 0 ? number_format($n * 100 / $total, 2) . ' %' : '0.00 %';

        return [
            ['label' => 'Pacientes',   'sub' => 'en total', 'total' => $total,   'pct' => $pct($total),   'icono' => 'users'],
            ['label' => 'Mujeres',     'sub' => 'en total', 'total' => $mujeres, 'pct' => $pct($mujeres), 'icono' => 'user-female'],
            ['label' => 'Varones',     'sub' => 'en total', 'total' => $varones, 'pct' => $pct($varones), 'icono' => 'user-male'],
            ['label' => 'No definido', 'sub' => 'en total', 'total' => $noDef,   'pct' => $pct($noDef),   'icono' => 'user-question'],
        ];
    }

    // ----------------------------------------------------------------
    // 2. ATENCIONES (tratamientos aplicados) DE LOS ÚLTIMOS N DÍAS
    // Fuente: DETALLE_ODONTOGRAMA + ODONTOGRAMA.fecha_evaluacion + TRATAMIENTO
    // ----------------------------------------------------------------
    private function atencionesUltimosNDias(int $dias): array
    {
        $resultado = [];

        for ($i = 0; $i < $dias; $i++) {
            $fecha = now()->subDays($i)->startOfDay();

            $detalles = DetalleOdontograma::query()
                ->join('ODONTOGRAMA', 'ODONTOGRAMA.id_odontograma', '=', 'DETALLE_ODONTOGRAMA.id_odontograma')
                ->join('PACIENTE', 'PACIENTE.id_paciente', '=', 'ODONTOGRAMA.id_paciente')
                ->leftJoin('TRATAMIENTO', 'TRATAMIENTO.id_tratamiento', '=', 'DETALLE_ODONTOGRAMA.id_tratamiento')
                ->whereDate('ODONTOGRAMA.fecha_evaluacion', $fecha->toDateString())
                ->select('TRATAMIENTO.nombre as tratamiento', 'PACIENTE.sexo')
                ->get();

            $porTratamiento = $detalles
                ->groupBy(fn ($d) => $d->tratamiento ?? 'Sin especificar')
                ->map(fn ($grupo) => $grupo->count())
                ->sortKeys();

            $varones = $detalles->where('sexo', 'M')->count();
            $mujeres = $detalles->where('sexo', 'F')->count();
            $otros   = $detalles->count() - $varones - $mujeres;

            $etiqueta = match ($i) {
                0 => 'Hoy día, ' . $fecha->translatedFormat('l j'),
                1 => 'Ayer, ' . $fecha->translatedFormat('l j'),
                2 => 'Antes de ayer, ' . $fecha->translatedFormat('l j'),
                default => $fecha->translatedFormat('l j'),
            };

            $detalleTexto = "Var:{$varones} Muj:{$mujeres}" . ($otros > 0 ? " Otr:{$otros}" : '');

            $resultado[] = [
                'titulo'  => 'Atenciones',
                'sub'     => $etiqueta,
                'total'   => $detalles->count(),
                'detalle' => $detalleTexto,
                'items'   => $porTratamiento->all(),
            ];
        }

        return $resultado;
    }

    // ----------------------------------------------------------------
    // 3. TRATAMIENTOS APLICADOS EN UN RANGO DE FECHAS (semana / mes)
    // ----------------------------------------------------------------
    private function tratamientosEnRango(Carbon $desde, Carbon $hasta): array
    {
        $detalles = DetalleOdontograma::query()
            ->join('ODONTOGRAMA', 'ODONTOGRAMA.id_odontograma', '=', 'DETALLE_ODONTOGRAMA.id_odontograma')
            ->join('PACIENTE', 'PACIENTE.id_paciente', '=', 'ODONTOGRAMA.id_paciente')
            ->leftJoin('TRATAMIENTO', 'TRATAMIENTO.id_tratamiento', '=', 'DETALLE_ODONTOGRAMA.id_tratamiento')
            ->whereBetween('ODONTOGRAMA.fecha_evaluacion', [$desde->toDateString(), $hasta->toDateString()])
            ->select('TRATAMIENTO.nombre as tratamiento', 'PACIENTE.sexo')
            ->get();

        $porTratamiento = $detalles
            ->groupBy(fn ($d) => $d->tratamiento ?? 'Sin especificar')
            ->map(fn ($grupo) => $grupo->count())
            ->sortKeys();

        $varones = $detalles->where('sexo', 'M')->count();
        $mujeres = $detalles->where('sexo', 'F')->count();
        $otros   = $detalles->count() - $varones - $mujeres;

        return [
            'total'   => $detalles->count(),
            'detalle' => "Var:{$varones} Muj:{$mujeres}" . ($otros > 0 ? " Otr:{$otros}" : ''),
            'items'   => $porTratamiento->all(),
        ];
    }

    // ----------------------------------------------------------------
    // 4. PAGOS DE PACIENTES DE LOS ÚLTIMOS N DÍAS
    // Fuente: PAGO + PLAN_PAGO + PACIENTE
    // ----------------------------------------------------------------
    private function pagosUltimosNDias(int $dias): array
    {
        $resultado = [];

        for ($i = 0; $i < $dias; $i++) {
            $fecha = now()->subDays($i)->startOfDay();

            $pagos = Pago::query()
                ->join('PLAN_PAGO', 'PLAN_PAGO.id_plan_pago', '=', 'PAGO.id_plan_pago')
                ->join('PACIENTE', 'PACIENTE.id_paciente', '=', 'PLAN_PAGO.id_paciente')
                ->whereDate('PAGO.fecha_pago', $fecha->toDateString())
                ->select(
                    'PAGO.monto_abonado',
                    'PACIENTE.nombres',
                    'PACIENTE.apellidos'
                )
                ->orderByDesc('PAGO.monto_abonado')
                ->get();

            $etiqueta = match ($i) {
                0 => 'Hoy día, ' . $fecha->translatedFormat('l j'),
                1 => 'Ayer, ' . $fecha->translatedFormat('l j'),
                2 => 'Antes de ayer, ' . $fecha->translatedFormat('l j'),
                default => $fecha->translatedFormat('l j'),
            };

            $resultado[] = [
                'titulo' => 'Pago de Pacientes',
                'sub'    => $etiqueta,
                'total'  => $pagos->sum('monto_abonado'),
                'cant'   => $pagos->count() . ' ' . ($pagos->count() === 1 ? 'pago' : 'pagos'),
                'items'  => $pagos->map(fn ($p) => [
                    'nombre' => trim("{$p->nombres} {$p->apellidos}"),
                    'monto'  => (float) $p->monto_abonado,
                ])->all(),
            ];
        }

        return $resultado;
    }

    // ----------------------------------------------------------------
    // 5. DATOS PARA LOS GRÁFICOS DE CHART.JS (todo agregado en SQL)
    // ----------------------------------------------------------------
    private function datosParaGraficos(): array
    {
        return [
            'pacientesSexo'         => $this->dataPacientesPorSexo(),
            'pacientesUltimosDias'  => $this->dataPacientesUltimosDias(10),
            'tratamientosSemana'    => $this->dataTratamientosDonut(now()->startOfWeek(), now()->endOfWeek()),
            'tratamientosMes'       => $this->dataTratamientosDonut(now()->startOfMonth(), now()->endOfMonth()),
            'tratamientosRadar'     => $this->dataTratamientosRadarUlt3Meses(),
            'pacientesUltimosMeses' => $this->dataPacientesUltimosMeses(12),
            'pagosPorDia'           => $this->dataPagosUltimosNDias(8),
            'pagosPorMes'           => $this->dataPagosUltimosNMeses(9),
        ];
    }

    /**
     * Distribución de pacientes por sexo, lista para el pie/donut.
     */
    private function dataPacientesPorSexo(): array
    {
        $mujeres = Paciente::where('sexo', 'F')->count();
        $varones = Paciente::where('sexo', 'M')->count();
        $noDef   = $this->scopeNoDefinido(Paciente::query())->count();

        return [
            'labels' => ['Mujer', 'Varón', 'No definido'],
            'data'   => [$mujeres, $varones, $noDef],
        ];
    }

    /**
     * Pacientes atendidos (con al menos un odontograma) por día,
     * desagregado por sexo, en los últimos N días.
     */
    private function dataPacientesUltimosDias(int $dias): array
    {
        $labels = [];
        $mujer = [];
        $varon = [];
        $noDef = [];

        for ($i = $dias - 1; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->startOfDay();
            $labels[] = $fecha->translatedFormat('l j');

            $pacientesDia = Paciente::query()
                ->join('ODONTOGRAMA', 'ODONTOGRAMA.id_paciente', '=', 'PACIENTE.id_paciente')
                ->whereDate('ODONTOGRAMA.fecha_evaluacion', $fecha->toDateString())
                ->distinct('PACIENTE.id_paciente')
                ->select('PACIENTE.id_paciente', 'PACIENTE.sexo')
                ->get();

            $m = $pacientesDia->where('sexo', 'F')->count();
            $v = $pacientesDia->where('sexo', 'M')->count();

            $mujer[] = $m;
            $varon[] = $v;
            $noDef[] = $pacientesDia->count() - $m - $v;
        }

        return compact('labels', 'mujer', 'varon', 'noDef');
    }

    /**
     * Conteo de tratamientos aplicados en un rango, listo para un donut/pie.
     */
    private function dataTratamientosDonut(Carbon $desde, Carbon $hasta): array
    {
        $datos = DetalleOdontograma::query()
            ->join('ODONTOGRAMA', 'ODONTOGRAMA.id_odontograma', '=', 'DETALLE_ODONTOGRAMA.id_odontograma')
            ->leftJoin('TRATAMIENTO', 'TRATAMIENTO.id_tratamiento', '=', 'DETALLE_ODONTOGRAMA.id_tratamiento')
            ->whereBetween('ODONTOGRAMA.fecha_evaluacion', [$desde->toDateString(), $hasta->toDateString()])
            ->selectRaw("NVL(TRATAMIENTO.nombre, 'Sin especificar') as tratamiento, COUNT(*) as cantidad")
            ->groupBy('TRATAMIENTO.nombre')
            ->orderBy('tratamiento')
            ->get();

        return [
            'labels' => $datos->pluck('tratamiento')->all(),
            'data'   => $datos->pluck('cantidad')->all(),
        ];
    }

    /**
     * Top tratamientos solicitados en los últimos 3 meses, uno por mes,
     * listo para un radar comparativo.
     */
    private function dataTratamientosRadarUlt3Meses(): array
    {
        $meses = collect([2, 1, 0])->map(fn ($i) => now()->subMonthsNoOverflow($i));

        $top = DetalleOdontograma::query()
            ->join('ODONTOGRAMA', 'ODONTOGRAMA.id_odontograma', '=', 'DETALLE_ODONTOGRAMA.id_odontograma')
            ->leftJoin('TRATAMIENTO', 'TRATAMIENTO.id_tratamiento', '=', 'DETALLE_ODONTOGRAMA.id_tratamiento')
            ->whereBetween('ODONTOGRAMA.fecha_evaluacion', [
                $meses->first()->copy()->startOfMonth()->toDateString(),
                $meses->last()->copy()->endOfMonth()->toDateString(),
            ])
            ->whereNotNull('TRATAMIENTO.nombre')
            ->selectRaw('TRATAMIENTO.nombre as tratamiento, COUNT(*) as cantidad')
            ->groupBy('TRATAMIENTO.nombre')
            ->orderByDesc('cantidad')
            ->limit(7)
            ->pluck('tratamiento');

        $datasets = $meses->map(function (Carbon $mes) use ($top) {
            $conteos = DetalleOdontograma::query()
                ->join('ODONTOGRAMA', 'ODONTOGRAMA.id_odontograma', '=', 'DETALLE_ODONTOGRAMA.id_odontograma')
                ->join('TRATAMIENTO', 'TRATAMIENTO.id_tratamiento', '=', 'DETALLE_ODONTOGRAMA.id_tratamiento')
                ->whereBetween('ODONTOGRAMA.fecha_evaluacion', [
                    $mes->copy()->startOfMonth()->toDateString(),
                    $mes->copy()->endOfMonth()->toDateString(),
                ])
                ->whereIn('TRATAMIENTO.nombre', $top)
                ->selectRaw('TRATAMIENTO.nombre as tratamiento, COUNT(*) as cantidad')
                ->groupBy('TRATAMIENTO.nombre')
                ->pluck('cantidad', 'tratamiento');

            return [
                'label' => $mes->translatedFormat('M.'),
                'data'  => $top->map(fn ($nombre) => $conteos->get($nombre, 0))->all(),
            ];
        });

        return [
            'labels'   => $top->values()->all(),
            'datasets' => $datasets->all(),
        ];
    }

    /**
     * Pacientes distintos atendidos por mes (últimos N meses),
     * desagregado por sexo, para la línea de tendencia.
     */
    private function dataPacientesUltimosMeses(int $meses): array
    {
        $labels = [];
        $total  = [];
        $mujer  = [];
        $varon  = [];
        $noDef  = [];

        for ($i = $meses - 1; $i >= 0; $i--) {
            $mes = now()->subMonthsNoOverflow($i);
            $labels[] = $mes->translatedFormat('M.');

            $pacientesMes = Paciente::query()
                ->join('ODONTOGRAMA', 'ODONTOGRAMA.id_paciente', '=', 'PACIENTE.id_paciente')
                ->whereBetween('ODONTOGRAMA.fecha_evaluacion', [
                    $mes->copy()->startOfMonth()->toDateString(),
                    $mes->copy()->endOfMonth()->toDateString(),
                ])
                ->distinct('PACIENTE.id_paciente')
                ->select('PACIENTE.id_paciente', 'PACIENTE.sexo')
                ->get();

            $m = $pacientesMes->where('sexo', 'F')->count();
            $v = $pacientesMes->where('sexo', 'M')->count();

            $total[] = $pacientesMes->count();
            $mujer[] = $m;
            $varon[] = $v;
            $noDef[] = $pacientesMes->count() - $m - $v;
        }

        return compact('labels', 'total', 'mujer', 'varon', 'noDef');
    }

    /**
     * Pagos totales recibidos en los últimos N días.
     */
    private function dataPagosUltimosNDias(int $dias): array
    {
        $labels = [];
        $data   = [];

        for ($i = $dias - 1; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->startOfDay();

            $labels[] = $fecha->translatedFormat('l (j)');
            $data[]   = (float) Pago::whereDate('fecha_pago', $fecha->toDateString())->sum('monto_abonado');
        }

        return compact('labels', 'data');
    }

    /**
     * Pagos agregados acumulados mensualmente en el histórico.
     */
    private function dataPagosUltimosNMeses(int $meses): array
    {
        $labels = [];
        $data   = [];

        for ($i = $meses - 1; $i >= 0; $i--) {
            $mes = now()->subMonthsNoOverflow($i);

            $labels[] = $mes->translatedFormat('F');
            $data[]   = (float) Pago::whereBetween('fecha_pago', [
                $mes->copy()->startOfMonth()->toDateString(),
                $mes->copy()->endOfMonth()->toDateString(),
            ])->sum('monto_abonado');
        }

        return compact('labels', 'data');
    }
}