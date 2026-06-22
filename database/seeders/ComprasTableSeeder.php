<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComprasTableSeeder extends Seeder
{
    public function run(): void
    {
        $compras = [
            ['id_proveedor' => 1, 'id_asistente' => 1, 'fecha_compra' => '2026-06-01', 'nro_factura' => 'FACT-901', 'total_compra' => 500.00, 'estado' => 'Completada'],
            ['id_proveedor' => 2, 'id_asistente' => 1, 'fecha_compra' => '2026-06-02', 'nro_factura' => 'FACT-902', 'total_compra' => 300.00, 'estado' => 'Completada'],
            ['id_proveedor' => 3, 'id_asistente' => 2, 'fecha_compra' => '2026-06-03', 'nro_factura' => 'FACT-903', 'total_compra' => 150.00, 'estado' => 'Completada'],
            ['id_proveedor' => 4, 'id_asistente' => 2, 'fecha_compra' => '2026-06-04', 'nro_factura' => 'FACT-904', 'total_compra' => 450.00, 'estado' => 'Completada'],
            ['id_proveedor' => 5, 'id_asistente' => 3, 'fecha_compra' => '2026-06-05', 'nro_factura' => 'FACT-905', 'total_compra' => 1200.00, 'estado' => 'Completada'],
            ['id_proveedor' => 1, 'id_asistente' => 3, 'fecha_compra' => '2026-06-06', 'nro_factura' => 'FACT-906', 'total_compra' => 600.00, 'estado' => 'Completada'],
            ['id_proveedor' => 2, 'id_asistente' => 4, 'fecha_compra' => '2026-06-07', 'nro_factura' => 'FACT-907', 'total_compra' => 250.00, 'estado' => 'Completada'],
            ['id_proveedor' => 3, 'id_asistente' => 4, 'fecha_compra' => '2026-06-08', 'nro_factura' => 'FACT-908', 'total_compra' => 800.00, 'estado' => 'Completada'],
            ['id_proveedor' => 4, 'id_asistente' => 5, 'fecha_compra' => '2026-06-09', 'nro_factura' => 'FACT-909', 'total_compra' => 350.00, 'estado' => 'Completada'],
            ['id_proveedor' => 5, 'id_asistente' => 5, 'fecha_compra' => '2026-06-10', 'nro_factura' => 'FACT-910', 'total_compra' => 900.00, 'estado' => 'Completada'],
            ['id_proveedor' => 1, 'id_asistente' => 6, 'fecha_compra' => '2026-06-11', 'nro_factura' => 'FACT-911', 'total_compra' => 150.00, 'estado' => 'Completada'],
            ['id_proveedor' => 2, 'id_asistente' => 6, 'fecha_compra' => '2026-06-12', 'nro_factura' => 'FACT-912', 'total_compra' => 400.00, 'estado' => 'Completada'],
            ['id_proveedor' => 3, 'id_asistente' => 7, 'fecha_compra' => '2026-06-13', 'nro_factura' => 'FACT-913', 'total_compra' => 700.00, 'estado' => 'Completada'],
            ['id_proveedor' => 4, 'id_asistente' => 7, 'fecha_compra' => '2026-06-14', 'nro_factura' => 'FACT-914', 'total_compra' => 200.00, 'estado' => 'Completada'],
            ['id_proveedor' => 5, 'id_asistente' => 7, 'fecha_compra' => '2026-06-15', 'nro_factura' => 'FACT-915', 'total_compra' => 500.00, 'estado' => 'Completada'],
            ['id_proveedor' => 1, 'id_asistente' => 7, 'fecha_compra' => '2026-06-16', 'nro_factura' => 'FACT-916', 'total_compra' => 300.00, 'estado' => 'Completada'],
            ['id_proveedor' => 2, 'id_asistente' => 8, 'fecha_compra' => '2026-06-17', 'nro_factura' => 'FACT-917', 'total_compra' => 1050.00, 'estado' => 'Completada'],
            ['id_proveedor' => 3, 'id_asistente' => 8, 'fecha_compra' => '2026-06-18', 'nro_factura' => 'FACT-918', 'total_compra' => 450.00, 'estado' => 'Completada'],
            ['id_proveedor' => 4, 'id_asistente' => 8, 'fecha_compra' => '2026-06-19', 'nro_factura' => 'FACT-919', 'total_compra' => 600.00, 'estado' => 'Completada'],
            ['id_proveedor' => 5, 'id_asistente' => 8, 'fecha_compra' => '2026-06-20', 'nro_factura' => 'FACT-920', 'total_compra' => 150.00, 'estado' => 'Completada']
        ];

        foreach ($compras as $compra) {
            DB::table('COMPRA')->insert($compra);
        }
    }
}