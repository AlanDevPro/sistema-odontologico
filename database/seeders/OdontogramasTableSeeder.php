<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OdontogramasTableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $doctorId = (int)(($i - 1) / 4) + 1;
            DB::table('ODONTOGRAMA')->insert([
                'id_paciente' => $i,
                'id_doctor' => $doctorId,
                'fecha_evaluacion' => now()->subDays(5),
                'observaciones_generales' => 'Odontograma inicial de control del paciente'
            ]);
        }
    }
}