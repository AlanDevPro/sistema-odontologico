<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            DoctorsTableSeeder::class,
            FoldersTableSeeder::class,
            AsistentesTableSeeder::class,
            TratamientosTableSeeder::class,
            ProveedoresTableSeeder::class,
            SuministrosTableSeeder::class,
            PacientesTableSeeder::class,
            CitasTableSeeder::class,
            OdontogramasTableSeeder::class,
            DetalleOdontogramasTableSeeder::class,
            PlanPagosTableSeeder::class,
            PagosTableSeeder::class,
            ComprasTableSeeder::class,
            DetalleComprasTableSeeder::class,
            InventarioTableSeeder::class,
        ]);
    }
}