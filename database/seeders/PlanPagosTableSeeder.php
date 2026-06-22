<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanPagosTableSeeder extends Seeder
{
    public function run(): void
    {
        $planes = [
            ['id_paciente' => 1, 'id_odontograma' => 1, 'costo_total' => 150.00, 'saldo_pendiente' => 50.00, 'estado' => 'Vigente'],
            ['id_paciente' => 2, 'id_odontograma' => 2, 'costo_total' => 250.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado'],
            ['id_paciente' => 3, 'id_odontograma' => 3, 'costo_total' => 250.00, 'saldo_pendiente' => 100.00, 'estado' => 'Vigente'],
            ['id_paciente' => 4, 'id_odontograma' => 4, 'costo_total' => 150.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado'],
            ['id_paciente' => 5, 'id_odontograma' => 5, 'costo_total' => 3500.00, 'saldo_pendiente' => 2500.00, 'estado' => 'Vigente'],
            ['id_paciente' => 6, 'id_odontograma' => 6, 'costo_total' => 3500.00, 'saldo_pendiente' => 1500.00, 'estado' => 'Vigente'],
            ['id_paciente' => 7, 'id_odontograma' => 7, 'costo_total' => 150.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado'],
            ['id_paciente' => 8, 'id_odontograma' => 8, 'costo_total' => 250.00, 'saldo_pendiente' => 50.00, 'estado' => 'Vigente'],
            ['id_paciente' => 9, 'id_odontograma' => 9, 'costo_total' => 600.00, 'saldo_pendiente' => 200.00, 'estado' => 'Vigente'],
            ['id_paciente' => 10, 'id_odontograma' => 10, 'costo_total' => 600.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado'],
            ['id_paciente' => 11, 'id_odontograma' => 11, 'costo_total' => 250.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado'],
            ['id_paciente' => 12, 'id_odontograma' => 12, 'costo_total' => 150.00, 'saldo_pendiente' => 50.00, 'estado' => 'Vigente'],
            ['id_paciente' => 13, 'id_odontograma' => 13, 'costo_total' => 300.00, 'saldo_pendiente' => 100.00, 'estado' => 'Vigente'],
            ['id_paciente' => 14, 'id_odontograma' => 14, 'costo_total' => 150.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado'],
            ['id_paciente' => 15, 'id_odontograma' => 15, 'costo_total' => 300.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado'],
            ['id_paciente' => 16, 'id_odontograma' => 16, 'costo_total' => 250.00, 'saldo_pendiente' => 50.00, 'estado' => 'Vigente'],
            ['id_paciente' => 17, 'id_odontograma' => 17, 'costo_total' => 1200.00, 'saldo_pendiente' => 400.00, 'estado' => 'Vigente'],
            ['id_paciente' => 18, 'id_odontograma' => 18, 'costo_total' => 1200.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado'],
            ['id_paciente' => 19, 'id_odontograma' => 19, 'costo_total' => 1200.00, 'saldo_pendiente' => 200.00, 'estado' => 'Vigente'],
            ['id_paciente' => 20, 'id_odontograma' => 20, 'costo_total' => 150.00, 'saldo_pendiente' => 0.00, 'estado' => 'Pagado']
        ];

        foreach ($planes as $plan) {
            DB::table('PLAN_PAGO')->insert($plan);
        }
    }
}