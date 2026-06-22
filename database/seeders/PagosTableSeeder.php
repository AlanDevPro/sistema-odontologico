<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosTableSeeder extends Seeder
{
    public function run(): void
    {
        $pagos = [
            // Plan 1 (Asistente 1)
            ['id_plan_pago' => 1, 'id_asistente' => 1, 'monto_abonado' => 50.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-001'],
            ['id_plan_pago' => 1, 'id_asistente' => 1, 'monto_abonado' => 50.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-002'],
            // Plan 2 (Asistente 1)
            ['id_plan_pago' => 2, 'id_asistente' => 1, 'monto_abonado' => 150.00, 'metodo_pago' => 'Tarjeta Débito', 'nro_comprobante' => 'REC-003'],
            ['id_plan_pago' => 2, 'id_asistente' => 1, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-004'],
            // Plan 3 (Asistente 2)
            ['id_plan_pago' => 3, 'id_asistente' => 2, 'monto_abonado' => 100.00, 'metodo_pago' => 'Transferencia QR', 'nro_comprobante' => 'REC-005'],
            ['id_plan_pago' => 3, 'id_asistente' => 2, 'monto_abonado' => 50.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-006'],
            // Plan 4 (Asistente 2)
            ['id_plan_pago' => 4, 'id_asistente' => 2, 'monto_abonado' => 75.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-007'],
            ['id_plan_pago' => 4, 'id_asistente' => 2, 'monto_abonado' => 75.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-008'],
            // Plan 5 (Asistente 3)
            ['id_plan_pago' => 5, 'id_asistente' => 3, 'monto_abonado' => 500.00, 'metodo_pago' => 'Tarjeta Crédito', 'nro_comprobante' => 'REC-009'],
            ['id_plan_pago' => 5, 'id_asistente' => 3, 'monto_abonado' => 500.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-010'],
            // Plan 6 (Asistente 3)
            ['id_plan_pago' => 6, 'id_asistente' => 3, 'monto_abonado' => 1000.00, 'metodo_pago' => 'Transferencia QR', 'nro_comprobante' => 'REC-011'],
            ['id_plan_pago' => 6, 'id_asistente' => 3, 'monto_abonado' => 1000.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-012'],
            // Plan 7 (Asistente 4)
            ['id_plan_pago' => 7, 'id_asistente' => 4, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-013'],
            ['id_plan_pago' => 7, 'id_asistente' => 4, 'monto_abonado' => 50.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-014'],
            // Plan 8 (Asistente 4)
            ['id_plan_pago' => 8, 'id_asistente' => 4, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-015'],
            ['id_plan_pago' => 8, 'id_asistente' => 4, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-016'],
            // Plan 9 (Asistente 5)
            ['id_plan_pago' => 9, 'id_asistente' => 5, 'monto_abonado' => 200.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-017'],
            ['id_plan_pago' => 9, 'id_asistente' => 5, 'monto_abonado' => 200.00, 'metodo_pago' => 'Tarjeta Débito', 'nro_comprobante' => 'REC-018'],
            // Plan 10 (Asistente 5)
            ['id_plan_pago' => 10, 'id_asistente' => 5, 'monto_abonado' => 300.00, 'metodo_pago' => 'Transferencia QR', 'nro_comprobante' => 'REC-019'],
            ['id_plan_pago' => 10, 'id_asistente' => 5, 'monto_abonado' => 300.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-020'],
            // Plan 11 (Asistente 6)
            ['id_plan_pago' => 11, 'id_asistente' => 6, 'monto_abonado' => 150.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-021'],
            ['id_plan_pago' => 11, 'id_asistente' => 6, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-022'],
            // Plan 12 (Asistente 6)
            ['id_plan_pago' => 12, 'id_asistente' => 6, 'monto_abonado' => 50.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-023'],
            ['id_plan_pago' => 12, 'id_asistente' => 6, 'monto_abonado' => 50.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-024'],
            // Plan 13 (Asistente 7)
            ['id_plan_pago' => 13, 'id_asistente' => 7, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-025'],
            ['id_plan_pago' => 13, 'id_asistente' => 7, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-026'],
            // Plan 14 (Asistente 7)
            ['id_plan_pago' => 14, 'id_asistente' => 7, 'monto_abonado' => 100.00, 'metodo_pago' => 'Transferencia QR', 'nro_comprobante' => 'REC-027'],
            ['id_plan_pago' => 14, 'id_asistente' => 7, 'monto_abonado' => 50.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-028'],
            // Plan 15 (Asistente 7)
            ['id_plan_pago' => 15, 'id_asistente' => 7, 'monto_abonado' => 150.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-029'],
            ['id_plan_pago' => 15, 'id_asistente' => 7, 'monto_abonado' => 150.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-030'],
            // Plan 16 (Asistente 7)
            ['id_plan_pago' => 16, 'id_asistente' => 7, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-031'],
            ['id_plan_pago' => 16, 'id_asistente' => 7, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-032'],
            // Plan 17 (Asistente 8)
            ['id_plan_pago' => 17, 'id_asistente' => 8, 'monto_abonado' => 400.00, 'metodo_pago' => 'Tarjeta Débito', 'nro_comprobante' => 'REC-033'],
            ['id_plan_pago' => 17, 'id_asistente' => 8, 'monto_abonado' => 400.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-034'],
            // Plan 18 (Asistente 8)
            ['id_plan_pago' => 18, 'id_asistente' => 8, 'monto_abonado' => 600.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-035'],
            ['id_plan_pago' => 18, 'id_asistente' => 8, 'monto_abonado' => 600.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-036'],
            // Plan 19 (Asistente 8)
            ['id_plan_pago' => 19, 'id_asistente' => 8, 'monto_abonado' => 500.00, 'metodo_pago' => 'Transferencia QR', 'nro_comprobante' => 'REC-037'],
            ['id_plan_pago' => 19, 'id_asistente' => 8, 'monto_abonado' => 500.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-038'],
            // Plan 20 (Asistente 8)
            ['id_plan_pago' => 20, 'id_asistente' => 8, 'monto_abonado' => 100.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-039'],
            ['id_plan_pago' => 20, 'id_asistente' => 8, 'monto_abonado' => 50.00, 'metodo_pago' => 'Efectivo', 'nro_comprobante' => 'REC-040']
        ];

        foreach ($pagos as $pago) {
            DB::table('PAGO')->insert($pago);
        }
    }
}