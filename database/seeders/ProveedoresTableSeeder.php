<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedoresTableSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            ['nit_ruc' => '102938101', 'razon_social' => 'Dent-Dental S.R.L.', 'nombre_contacto' => 'Juan Carlos Pérez', 'telefono' => '22441122', 'direccion' => 'Av. Intercomunitaria 450', 'correo' => 'ventas@dentdental.com'],
            ['nit_ruc' => '546372819', 'razon_social' => 'Importadora OdontoBol', 'nombre_contacto' => 'Sofía Terán', 'telefono' => '33552233', 'direccion' => 'Calle Junín Nro 89', 'correo' => 'pedidos@odontobol.com'],
            ['nit_ruc' => '987654321', 'razon_social' => 'Suministros Médicos Quirúrgicos', 'nombre_contacto' => 'Carlos Siles', 'telefono' => '44663344', 'direccion' => 'Zona Central Pasaje Flores', 'correo' => 'csiles@sumed.com'],
            ['nit_ruc' => '123456789', 'razon_social' => 'Protesis & Resinas Express', 'nombre_contacto' => 'Alejandra Rocha', 'telefono' => '22889988', 'direccion' => 'Av. de las Américas 12', 'correo' => 'express@resinaspro.com'],
            ['nit_ruc' => '112233445', 'razon_social' => 'Dental Global Import', 'nombre_contacto' => 'Ricardo Avila', 'telefono' => '33117766', 'direccion' => 'Calle Calvo Escuadra 4', 'correo' => 'r.avila@dentalglobal.com']
        ];

        foreach ($proveedores as $proveedor) {
            DB::table('PROVEEDOR')->insert($proveedor);
        }
    }
}