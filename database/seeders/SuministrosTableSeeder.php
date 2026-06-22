<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuministrosTableSeeder extends Seeder
{
    public function run(): void
    {
        $suministros = [
            ['codigo_barras' => '75010011', 'nombre' => 'Resina Filtek Z250 3M', 'categoria' => 'Restauración', 'unidad_medida' => 'Tubo', 'stock_minimo' => 5, 'estado' => 1],
            ['codigo_barras' => '75010022', 'nombre' => 'Anestesia Tubos Lidocaína 2%', 'categoria' => 'Anestésicos', 'unidad_medida' => 'Caja x50', 'stock_minimo' => 3, 'estado' => 1],
            ['codigo_barras' => '75010033', 'nombre' => 'Agujas Cortas Desechables 30G', 'categoria' => 'Descartables', 'unidad_medida' => 'Caja x100', 'stock_minimo' => 4, 'estado' => 1],
            ['codigo_barras' => '75010044', 'nombre' => 'Guantes de Látex Talla M', 'categoria' => 'Protección', 'unidad_medida' => 'Caja x100', 'stock_minimo' => 10, 'estado' => 1],
            ['codigo_barras' => '75010055', 'nombre' => 'Ácido Grabadoc Etch 37%', 'categoria' => 'Restauración', 'unidad_medida' => 'Jeringa', 'stock_minimo' => 2, 'estado' => 1],
            ['codigo_barras' => '75010066', 'nombre' => 'Alginato de Alta Precisión', 'categoria' => 'Impresión', 'unidad_medida' => 'Bolsa 500g', 'stock_minimo' => 3, 'estado' => 1],
            ['codigo_barras' => '75010077', 'nombre' => 'Arcos NiTi 0.014 Superior', 'categoria' => 'Ortodoncia', 'unidad_medida' => 'Paquete x10', 'stock_minimo' => 5, 'estado' => 1],
            ['codigo_barras' => '75010088', 'nombre' => 'Puntas de Gutapercha 2da Serie', 'categoria' => 'Endodoncia', 'unidad_medida' => 'Caja x120', 'stock_minimo' => 2, 'estado' => 1]
        ];

        foreach ($suministros as $suministro) {
            DB::table('SUMINISTRO')->insert($suministro);
        }
    }
}