<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener los IDs de los usuarios insertados
        $users = DB::table('USERS')->orderBy('id')->get();
        
        $doctors = [
            ['ci_dni' => '10011', 'nombres' => 'Christian', 'apellidos' => 'Mendoza', 'especialidad' => 'Odontología General', 'telefono' => '71111111', 'correo' => 'c.mendoza@lalysdent.com', 'estado' => 1],
            ['ci_dni' => '10022', 'nombres' => 'Claudia', 'apellidos' => 'Ibáñez', 'especialidad' => 'Ortodoncia', 'telefono' => '72222222', 'correo' => 'c.ibanez@lalysdent.com', 'estado' => 1],
            ['ci_dni' => '10033', 'nombres' => 'Alan Nicolas', 'apellidos' => 'Villarroel', 'especialidad' => 'Cirugía Oral y Maxilofacial', 'telefono' => '73333333', 'correo' => 'a.villarroel@lalysdent.com', 'estado' => 1],
            ['ci_dni' => '10044', 'nombres' => 'Laly', 'apellidos' => 'Torrico', 'especialidad' => 'Odontopediatría', 'telefono' => '74444444', 'correo' => 'l.torrico@lalysdent.com', 'estado' => 1],
            ['ci_dni' => '10055', 'nombres' => 'Sergio', 'apellidos' => 'Escalante', 'especialidad' => 'Endodoncia', 'telefono' => '75555555', 'correo' => 's.escalante@lalysdent.com', 'estado' => 1]
        ];

        foreach ($doctors as $index => $doctor) {
            DB::table('DOCTOR')->insert([
                'ci_dni' => $doctor['ci_dni'],
                'nombres' => $doctor['nombres'],
                'apellidos' => $doctor['apellidos'],
                'especialidad' => $doctor['especialidad'],
                'telefono' => $doctor['telefono'],
                'correo' => $doctor['correo'],
                'estado' => $doctor['estado'],
                'user_id' => $users[$index]->id
            ]);
        }
    }
}