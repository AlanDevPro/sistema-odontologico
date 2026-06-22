<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsistentesTableSeeder extends Seeder
{
    public function run(): void
    {
        $asistentes = [
            ['ci_dni' => '4001', 'nombres' => 'Patricia', 'apellidos' => 'Arce', 'telefono' => '60000001', 'turno' => 'Mañana', 'fecha_contratacion' => '2025-01-10', 'estado' => 1],
            ['ci_dni' => '4002', 'nombres' => 'Lucía', 'apellidos' => 'Fernández', 'telefono' => '60000002', 'turno' => 'Tarde', 'fecha_contratacion' => '2025-01-15', 'estado' => 1],
            ['ci_dni' => '4003', 'nombres' => 'Mateo', 'apellidos' => 'Salazar', 'telefono' => '60000003', 'turno' => 'Mañana', 'fecha_contratacion' => '2025-02-01', 'estado' => 1],
            ['ci_dni' => '4004', 'nombres' => 'Camila', 'apellidos' => 'Vargas', 'telefono' => '60000004', 'turno' => 'Tarde', 'fecha_contratacion' => '2025-02-20', 'estado' => 1],
            ['ci_dni' => '4005', 'nombres' => 'Daniela', 'apellidos' => 'Rojas', 'telefono' => '60000005', 'turno' => 'Mañana', 'fecha_contratacion' => '2025-03-05', 'estado' => 1],
            ['ci_dni' => '4006', 'nombres' => 'Andrés', 'apellidos' => 'Guzmán', 'telefono' => '60000006', 'turno' => 'Tarde', 'fecha_contratacion' => '2025-03-12', 'estado' => 1],
            ['ci_dni' => '4007', 'nombres' => 'Valeria', 'apellidos' => 'Pinto', 'telefono' => '60000007', 'turno' => 'Completo', 'fecha_contratacion' => '2025-04-01', 'estado' => 1],
            ['ci_dni' => '4008', 'nombres' => 'Mariana', 'apellidos' => 'Flores', 'telefono' => '60000008', 'turno' => 'Completo', 'fecha_contratacion' => '2025-04-15', 'estado' => 1]
        ];

        foreach ($asistentes as $asistente) {
            DB::table('ASISTENTE')->insert([
                'ci_dni' => $asistente['ci_dni'],
                'nombres' => $asistente['nombres'],
                'apellidos' => $asistente['apellidos'],
                'telefono' => $asistente['telefono'],
                'turno' => $asistente['turno'],
                'fecha_contratacion' => $asistente['fecha_contratacion'],
                'estado' => $asistente['estado']
            ]);
        }
    }
}