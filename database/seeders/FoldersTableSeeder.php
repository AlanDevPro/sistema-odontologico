<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoldersTableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            DB::table('FOLDER')->insert([
                'codigo_archivo' => 'F-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'estante' => 'Estante ' . (int)(($i - 1) / 10 + 1),
                'seccion' => 'Nivel ' . ($i % 4),
                'observaciones' => 'Asignado secuencial'
            ]);
        }
    }
}