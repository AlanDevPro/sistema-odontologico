<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetalleComprasTableSeeder extends Seeder
{
    public function run(): void
    {
        $detalles = [
            ['id_compra' => 1, 'id_suministro' => 1, 'cantidad' => 5.00, 'precio_unitario' => 100.00, 'subtotal' => 500.00],
            ['id_compra' => 2, 'id_suministro' => 2, 'cantidad' => 6.00, 'precio_unitario' => 50.00, 'subtotal' => 300.00],
            ['id_compra' => 3, 'id_suministro' => 3, 'cantidad' => 5.00, 'precio_unitario' => 30.00, 'subtotal' => 150.00],
            ['id_compra' => 4, 'id_suministro' => 4, 'cantidad' => 10.00, 'precio_unitario' => 45.00, 'subtotal' => 450.00],
            ['id_compra' => 5, 'id_suministro' => 7, 'cantidad' => 10.00, 'precio_unitario' => 120.00, 'subtotal' => 1200.00],
            ['id_compra' => 6, 'id_suministro' => 4, 'cantidad' => 10.00, 'precio_unitario' => 60.00, 'subtotal' => 600.00],
            ['id_compra' => 7, 'id_suministro' => 6, 'cantidad' => 5.00, 'precio_unitario' => 50.00, 'subtotal' => 250.00],
            ['id_compra' => 8, 'id_suministro' => 1, 'cantidad' => 8.00, 'precio_unitario' => 100.00, 'subtotal' => 800.00],
            ['id_compra' => 9, 'id_suministro' => 3, 'cantidad' => 10.00, 'precio_unitario' => 35.00, 'subtotal' => 350.00],
            ['id_compra' => 10, 'id_suministro' => 2, 'cantidad' => 18.00, 'precio_unitario' => 50.00, 'subtotal' => 900.00],
            ['id_compra' => 11, 'id_suministro' => 5, 'cantidad' => 3.00, 'precio_unitario' => 50.00, 'subtotal' => 150.00],
            ['id_compra' => 12, 'id_suministro' => 4, 'cantidad' => 8.00, 'precio_unitario' => 50.00, 'subtotal' => 400.00],
            ['id_compra' => 13, 'id_suministro' => 8, 'cantidad' => 10.00, 'precio_unitario' => 70.00, 'subtotal' => 700.00],
            ['id_compra' => 14, 'id_suministro' => 3, 'cantidad' => 5.00, 'precio_unitario' => 40.00, 'subtotal' => 200.00],
            ['id_compra' => 15, 'id_suministro' => 2, 'cantidad' => 10.00, 'precio_unitario' => 50.00, 'subtotal' => 500.00],
            ['id_compra' => 16, 'id_suministro' => 1, 'cantidad' => 3.00, 'precio_unitario' => 100.00, 'subtotal' => 300.00],
            ['id_compra' => 17, 'id_suministro' => 8, 'cantidad' => 15.00, 'precio_unitario' => 70.00, 'subtotal' => 1050.00],
            ['id_compra' => 18, 'id_suministro' => 5, 'cantidad' => 9.00, 'precio_unitario' => 50.00, 'subtotal' => 450.00],
            ['id_compra' => 19, 'id_suministro' => 1, 'cantidad' => 6.00, 'precio_unitario' => 100.00, 'subtotal' => 600.00],
            ['id_compra' => 20, 'id_suministro' => 3, 'cantidad' => 5.00, 'precio_unitario' => 30.00, 'subtotal' => 150.00]
        ];

        foreach ($detalles as $detalle) {
            DB::table('DETALLE_COMPRA')->insert($detalle);
        }
    }
}