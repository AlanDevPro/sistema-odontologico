<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TratamientosTableSeeder extends Seeder
{
    public function run(): void
    {
        $tratamientos = [
            ['nombre' => 'Profilaxis Adulto', 'descripcion' => 'Limpieza dental profunda ultrasónica', 'costo_referencial' => 150.00, 'estado' => 1],
            ['nombre' => 'Restauración Resina', 'descripcion' => 'Calza estética de fotocurado', 'costo_referencial' => 250.00, 'estado' => 1],
            ['nombre' => 'Tratamiento de Conducto (Endodoncia)', 'descripcion' => 'Terapia pulpar por pieza', 'costo_referencial' => 1200.00, 'estado' => 1],
            ['nombre' => 'Extracción Quirúrgica', 'descripcion' => 'Exodoncia compleja de terceros molares', 'costo_referencial' => 600.00, 'estado' => 1],
            ['nombre' => 'Blanqueamiento Clínico', 'descripcion' => 'Aclaramiento dental asistido por Láser/LED', 'costo_referencial' => 800.00, 'estado' => 1],
            ['nombre' => 'Diseño de Sonrisa Carillas', 'descripcion' => 'Lentes cerámicos estéticos por pieza', 'costo_referencial' => 2500.00, 'estado' => 1],
            ['nombre' => 'Instalación de Brackets', 'descripcion' => 'Ortodoncia correctiva metálica', 'costo_referencial' => 3500.00, 'estado' => 1],
            ['nombre' => 'Pulpotomía Infantil', 'descripcion' => 'Tratamiento pulpar en dientes deciduos', 'costo_referencial' => 300.00, 'estado' => 1]
        ];

        foreach ($tratamientos as $tratamiento) {
            DB::table('TRATAMIENTO')->insert($tratamiento);
        }
    }
}