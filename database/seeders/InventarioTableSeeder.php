<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventarioTableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            DB::table('ALMACEN_INVENTARIO')->updateOrInsert(
                ['id_suministro' => $i],
                [
                    'stock_actual' => 25.00,
                    'ultima_actualizacion' => now()
                ]
            );
        }
    }
}