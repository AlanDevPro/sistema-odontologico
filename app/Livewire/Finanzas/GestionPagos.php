<?php

namespace App\Livewire\Finanzas;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GestionPagos extends Component
{
    // ── Control de vista ─────────────────────────────────────────────
    public string $vista = 'lista'; // 'lista' | 'detalle'
    public ?int $planSeleccionado = null;

    // ── Búsqueda y filtros (vista lista) ─────────────────────────────
    public string $buscarNombre    = '';
    public string $buscarApellidos = '';
    public string $orden           = 'recientes'; // recientes | antiguos
    public string $filtroPlan      = 'todos';     // todos | debe | no_debe

    // ── Modal cuota: agregar / modificar ─────────────────────────────
    public bool   $modalCuota      = false;
    public bool   $modoEdicion     = false;
    public ?int   $idPagoEditando  = null;

    public ?string $cuota_fecha        = null;
    public ?float  $cuota_monto        = null;
    public string  $cuota_metodo_pago  = 'Efectivo';
    public ?string $cuota_comprobante  = null;
    public ?string $cuota_password     = null;

    // ── Modal: crear nuevo plan de pago ──────────────────────────────
    public bool    $modalPlan           = false;
    public ?int    $plan_id_paciente    = null;
    public ?int    $plan_id_odontograma = null;
    public ?float  $plan_costo_total    = null;

    // ── Modal: confirmar eliminación de cuota ────────────────────────
    public bool $modalEliminarCuota = false;

    // ── Catálogos para selects ────────────────────────────────────────
    public array $metodosPago = ['Efectivo', 'Tarjeta', 'Transferencia', 'Yape', 'Plin', 'Otro'];

    // ── Propiedad computada: lista de planes filtrada / ordenada ─────
    public function getPlanesFiltradosProperty(): \Illuminate\Support\Collection
    {
        $orderDir = $this->orden === 'recientes' ? 'DESC' : 'ASC';

        $sql = "
            SELECT
                pp.id_plan_pago,
                pp.costo_total,
                pp.saldo_pendiente,
                pp.estado            AS estado_plan,
                pp.fecha_creacion,
                p.id_paciente,
                p.nombres            AS paciente_nombres,
                p.apellidos          AS paciente_apellidos,
                p.ci_dni,
                f.codigo_archivo     AS folder_codigo,
                (
                    SELECT COUNT(*)
                    FROM PAGO pg
                    WHERE pg.id_plan_pago = pp.id_plan_pago
                ) AS nro_cuotas_pagadas,
                (
                    SELECT NVL(SUM(pg2.monto_abonado), 0)
                    FROM PAGO pg2
                    WHERE pg2.id_plan_pago = pp.id_plan_pago
                ) AS total_pagado,
                (
                    SELECT t.nombre
                    FROM DETALLE_ODONTOGRAMA dod
                    JOIN TRATAMIENTO t ON dod.id_tratamiento = t.id_tratamiento
                    WHERE dod.id_odontograma = pp.id_odontograma
                    AND ROWNUM = 1
                ) AS tratamiento
            FROM PLAN_PAGO pp
            JOIN PACIENTE p  ON pp.id_paciente  = p.id_paciente
            LEFT JOIN FOLDER f ON p.id_folder = f.id_folder
            WHERE 1 = 1
        ";

        $params = [];

        if (!empty($this->buscarNombre)) {
            $sql .= " AND UPPER(p.nombres) LIKE UPPER(:nombres)";
            $params['nombres'] = '%' . $this->buscarNombre . '%';
        }

        if (!empty($this->buscarApellidos)) {
            $sql .= " AND UPPER(p.apellidos) LIKE UPPER(:apellidos)";
            $params['apellidos'] = '%' . $this->buscarApellidos . '%';
        }

        if ($this->filtroPlan === 'debe') {
            $sql .= " AND pp.saldo_pendiente > 0";
        } elseif ($this->filtroPlan === 'no_debe') {
            $sql .= " AND pp.saldo_pendiente <= 0";
        }

        $sql .= " ORDER BY pp.fecha_creacion {$orderDir}";

        return collect(DB::select($sql, $params));
    }

    // ── Propiedad computada: plan activo en vista detalle ────────────
    public function getPlanActivoProperty(): ?object
    {
        if (!$this->planSeleccionado) return null;

        $plan = DB::selectOne("
            SELECT
                pp.id_plan_pago,
                pp.id_odontograma,
                pp.costo_total,
                pp.saldo_pendiente,
                pp.estado            AS estado_plan,
                pp.fecha_creacion,
                p.id_paciente,
                p.nombres            AS paciente_nombres,
                p.apellidos          AS paciente_apellidos,
                p.ci_dni,
                f.codigo_archivo     AS folder_codigo,
                (
                    SELECT NVL(SUM(pg2.monto_abonado), 0)
                    FROM PAGO pg2
                    WHERE pg2.id_plan_pago = pp.id_plan_pago
                ) AS total_pagado,
                (
                    SELECT COUNT(*)
                    FROM PAGO pg3
                    WHERE pg3.id_plan_pago = pp.id_plan_pago
                ) AS nro_cuotas_pagadas,
                (
                    SELECT t.nombre
                    FROM DETALLE_ODONTOGRAMA dod
                    JOIN TRATAMIENTO t ON dod.id_tratamiento = t.id_tratamiento
                    WHERE dod.id_odontograma = pp.id_odontograma
                    AND ROWNUM = 1
                ) AS tratamiento,
                (
                    SELECT t2.descripcion
                    FROM DETALLE_ODONTOGRAMA dod2
                    JOIN TRATAMIENTO t2 ON dod2.id_tratamiento = t2.id_tratamiento
                    WHERE dod2.id_odontograma = pp.id_odontograma
                    AND ROWNUM = 1
                ) AS detalle_tratamiento
            FROM PLAN_PAGO pp
            JOIN PACIENTE p  ON pp.id_paciente  = p.id_paciente
            LEFT JOIN FOLDER f ON p.id_folder = f.id_folder
            WHERE pp.id_plan_pago = :id
        ", ['id' => $this->planSeleccionado]);

        return $plan;
    }

    // ── Pagos (cuotas) del plan activo ───────────────────────────────
    public function getPagosDelPlanProperty(): array
    {
        if (!$this->planSeleccionado) return [];

        return DB::select("
            SELECT
                pg.id_pago,
                pg.monto_abonado,
                pg.fecha_pago,
                pg.metodo_pago,
                pg.nro_comprobante,
                a.nombres || ' ' || a.apellidos AS asistente_nombre
            FROM PAGO pg
            LEFT JOIN ASISTENTE a ON pg.id_asistente = a.id_asistente
            WHERE pg.id_plan_pago = :id
            ORDER BY pg.fecha_pago ASC
        ", ['id' => $this->planSeleccionado]);
    }

    // ── Catálogos para el modal de nuevo plan ────────────────────────
    public function getPacientesProperty(): array
    {
        return DB::select("
            SELECT id_paciente,
                   nombres || ' ' || apellidos AS nombre_completo,
                   ci_dni
            FROM PACIENTE
            ORDER BY apellidos, nombres
        ");
    }

    public function getOdontogramasDelPacienteProperty(): array
    {
        if (!$this->plan_id_paciente) return [];

        return DB::select("
            SELECT
                o.id_odontograma,
                o.fecha_evaluacion,
                d.nombres || ' ' || d.apellidos AS doctor,
                (
                    SELECT t.nombre
                    FROM DETALLE_ODONTOGRAMA dod
                    JOIN TRATAMIENTO t ON dod.id_tratamiento = t.id_tratamiento
                    WHERE dod.id_odontograma = o.id_odontograma
                    AND ROWNUM = 1
                ) AS tratamiento_principal
            FROM ODONTOGRAMA o
            JOIN DOCTOR d ON o.id_doctor = d.id_doctor
            WHERE o.id_paciente = :id
            ORDER BY o.fecha_evaluacion DESC
        ", ['id' => $this->plan_id_paciente]);
    }

    // ── Navegación entre vistas ───────────────────────────────────────
    public function verDetalle(int $idPlan): void
    {
        $this->planSeleccionado = $idPlan;
        $this->vista = 'detalle';
    }

    public function volverALista(): void
    {
        $this->vista = 'lista';
        $this->planSeleccionado = null;
    }

    // ── Modal cuota: abrir en modo Agregar ───────────────────────────
    public function abrirAgregarCuota(): void
    {
        $this->modoEdicion        = false;
        $this->idPagoEditando     = null;
        $this->cuota_fecha        = now()->format('Y-m-d\TH:i');
        $this->cuota_monto        = null;
        $this->cuota_metodo_pago  = 'Efectivo';
        $this->cuota_comprobante  = null;
        $this->cuota_password     = null;
        $this->resetErrorBag();
        $this->modalCuota = true;
    }

    // ── Modal cuota: abrir en modo Modificar ─────────────────────────
    public function abrirModificarCuota(int $idPago): void
    {
        $pago = DB::selectOne(
            'SELECT id_pago, monto_abonado, fecha_pago, metodo_pago, nro_comprobante
               FROM PAGO WHERE id_pago = :id',
            ['id' => $idPago]
        );

        if (!$pago) return;

        $this->modoEdicion       = true;
        $this->idPagoEditando    = $idPago;
        $this->cuota_fecha       = Carbon::parse($pago->fecha_pago)->format('Y-m-d\TH:i');
        $this->cuota_monto       = (float) $pago->monto_abonado;
        $this->cuota_metodo_pago = $pago->metodo_pago;
        $this->cuota_comprobante = $pago->nro_comprobante;
        $this->cuota_password    = null;
        $this->resetErrorBag();
        $this->modalCuota = true;
    }

    public function cerrarModalCuota(): void
    {
        $this->modalCuota     = false;
        $this->idPagoEditando = null;
        $this->resetErrorBag();
    }

    // ── Guardar cuota: agregar o modificar ───────────────────────────
    public function guardarCuota(): void
    {
        $reglas = [
            'cuota_fecha'       => 'required',
            'cuota_monto'       => 'required|numeric|min:0.01',
            'cuota_metodo_pago' => 'required|string',
            'cuota_comprobante' => 'nullable|string|max:50',
        ];

        if ($this->modoEdicion) {
            $reglas['cuota_password'] = 'required|string|min:4';
        }

        $this->validate($reglas, [
            'cuota_fecha.required'       => 'La fecha es obligatoria.',
            'cuota_monto.required'       => 'El monto es obligatorio.',
            'cuota_monto.min'            => 'El monto debe ser mayor a cero.',
            'cuota_metodo_pago.required' => 'Seleccione un método de pago.',
            'cuota_password.required'    => 'Se requiere contraseña para modificar.',
        ]);

        try {
            if ($this->modoEdicion) {
                // ── Modificar cuota existente ───────────────────────
                // Recuperar monto anterior para recalcular saldo
                $pagoAnterior = DB::selectOne(
                    'SELECT monto_abonado FROM PAGO WHERE id_pago = :id',
                    ['id' => $this->idPagoEditando]
                );

                if (!$pagoAnterior) {
                    session()->flash('error', 'El pago no existe.');
                    return;
                }

                $diferencia = (float)$this->cuota_monto - (float)$pagoAnterior->monto_abonado;

                DB::statement("
                    UPDATE PAGO
                       SET monto_abonado  = :monto,
                           fecha_pago     = TO_TIMESTAMP(:fecha, 'YYYY-MM-DD HH24:MI'),
                           metodo_pago    = :metodo,
                           nro_comprobante = :comprobante
                     WHERE id_pago = :id
                ", [
                    'monto'       => $this->cuota_monto,
                    'fecha'       => Carbon::parse($this->cuota_fecha)->format('Y-m-d H:i'),
                    'metodo'      => $this->cuota_metodo_pago,
                    'comprobante' => $this->cuota_comprobante,
                    'id'          => $this->idPagoEditando,
                ]);

                // Actualizar saldo del plan (el trigger valida que no sea negativo)
                DB::statement("
                    UPDATE PLAN_PAGO
                       SET saldo_pendiente = saldo_pendiente - :diferencia
                     WHERE id_plan_pago = :id
                ", [
                    'diferencia' => $diferencia,
                    'id'         => $this->planSeleccionado,
                ]);

                session()->flash('mensaje', 'Cuota modificada correctamente.');

            } else {
                // ── Registrar nueva cuota via SP_REGISTRAR_PAGO ─────
                // El stored procedure valida que el monto no exceda el saldo
                // y actualiza PLAN_PAGO automáticamente.
                // El trigger TRG_AUDITAR_PLAN_PAGO cambia estado a 'Pagado' si saldo = 0.

                DB::statement("
                    BEGIN
                        SP_REGISTRAR_PAGO(
                            p_id_plan_pago => :id_plan,
                            p_id_asistente => NULL,
                            p_monto        => :monto,
                            p_metodo_pago  => :metodo,
                            p_comprobante  => :comprobante
                        );
                    END;
                ", [
                    'id_plan'     => $this->planSeleccionado,
                    'monto'       => $this->cuota_monto,
                    'metodo'      => $this->cuota_metodo_pago,
                    'comprobante' => $this->cuota_comprobante ?: strtoupper(uniqid('COMP-')),
                ]);

                session()->flash('mensaje', 'Cuota registrada correctamente.');
            }
        } catch (\Exception $e) {
            // ORA-20002: monto excede saldo (lanzado por SP_REGISTRAR_PAGO)
            // ORA-20001: saldo negativo (lanzado por TRG_AUDITAR_PLAN_PAGO)
            $mensaje = $e->getMessage();

            if (str_contains($mensaje, 'ORA-20002') || str_contains($mensaje, 'excede el saldo')) {
                $this->addError('cuota_monto', 'El monto supera el saldo pendiente del plan.');
            } elseif (str_contains($mensaje, 'ORA-20001')) {
                $this->addError('cuota_monto', 'El saldo pendiente no puede ser negativo.');
            } else {
                session()->flash('error', 'Error al registrar el pago: ' . $e->getMessage());
            }
            return;
        }

        $this->cerrarModalCuota();
    }

    // ── Eliminar cuota ────────────────────────────────────────────────
    public function abrirEliminarCuota(int $idPago): void
    {
        $this->idPagoEditando    = $idPago;
        $this->modalEliminarCuota = true;
    }

    public function eliminarCuota(): void
    {
        try {
            $pago = DB::selectOne(
                'SELECT monto_abonado, id_plan_pago FROM PAGO WHERE id_pago = :id',
                ['id' => $this->idPagoEditando]
            );

            if (!$pago) {
                session()->flash('error', 'El pago no fue encontrado.');
                $this->modalEliminarCuota = false;
                return;
            }

            // Restaurar saldo antes de eliminar
            DB::statement("
                UPDATE PLAN_PAGO
                   SET saldo_pendiente = saldo_pendiente + :monto,
                       estado = CASE
                                    WHEN estado = 'Pagado' THEN 'Vigente'
                                    ELSE estado
                                END
                 WHERE id_plan_pago = :id
            ", [
                'monto' => $pago->monto_abonado,
                'id'    => $pago->id_plan_pago,
            ]);

            DB::statement('DELETE FROM PAGO WHERE id_pago = :id', ['id' => $this->idPagoEditando]);

            session()->flash('mensaje', 'Cuota eliminada y saldo restaurado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la cuota: ' . $e->getMessage());
        }

        $this->modalEliminarCuota = false;
        $this->idPagoEditando     = null;
    }

    // ── Modal: crear nuevo plan de pago ──────────────────────────────
    public function abrirAgregarPlan(): void
    {
        $this->plan_id_paciente    = null;
        $this->plan_id_odontograma = null;
        $this->plan_costo_total    = null;
        $this->resetErrorBag();
        $this->modalPlan = true;
    }

    public function cerrarModalPlan(): void
    {
        $this->modalPlan = false;
        $this->resetErrorBag();
    }

    public function guardarPlan(): void
    {
        $this->validate([
            'plan_id_paciente'    => 'required|integer',
            'plan_id_odontograma' => 'required|integer',
            'plan_costo_total'    => 'required|numeric|min:0.01',
        ], [
            'plan_id_paciente.required'    => 'Seleccione un paciente.',
            'plan_id_odontograma.required' => 'Seleccione un odontograma.',
            'plan_costo_total.required'    => 'Ingrese el costo total acordado.',
            'plan_costo_total.min'         => 'El costo debe ser mayor a cero.',
        ]);

        // Verificar que no exista ya un plan para ese odontograma
        $existe = DB::selectOne(
            'SELECT 1 FROM PLAN_PAGO WHERE id_odontograma = :id AND estado != :estado',
            ['id' => $this->plan_id_odontograma, 'estado' => 'Pagado']
        );

        if ($existe) {
            $this->addError('plan_id_odontograma', 'Ya existe un plan vigente para ese odontograma.');
            return;
        }

        try {
            DB::statement("
                INSERT INTO PLAN_PAGO
                    (id_paciente, id_odontograma, costo_total, saldo_pendiente, fecha_creacion, estado)
                VALUES
                    (:id_paciente, :id_odontograma, :costo_total, :saldo_pendiente, SYSDATE, 'Vigente')
            ", [
                'id_paciente'    => $this->plan_id_paciente,
                'id_odontograma' => $this->plan_id_odontograma,
                'costo_total'    => $this->plan_costo_total,
                'saldo_pendiente'=> $this->plan_costo_total,
            ]);

            session()->flash('mensaje', 'Plan de pago creado correctamente.');
            $this->cerrarModalPlan();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el plan: ' . $e->getMessage());
        }
    }

    // ── Render ────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.finanzas.gestion-pagos');
    }
}