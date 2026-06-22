<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacientesTableSeeder extends Seeder
{
    public function run(): void
    {
        $pacientes = [
            // Doctor 1 (ID 1-4)
            ['id_folder' => 1, 'ci_dni' => '5001', 'nombres' => 'Alejandro', 'apellidos' => 'Quinteros', 'fecha_nacimiento' => '1990-05-12', 'sexo' => 'M', 'telefono' => '78900001', 'direccion' => 'Calle Loa 120', 'antecedentes_medicos' => 'Ninguno'],
            ['id_folder' => 2, 'ci_dni' => '5002', 'nombres' => 'Bárbara', 'apellidos' => 'Campero', 'fecha_nacimiento' => '1985-08-22', 'sexo' => 'F', 'telefono' => '78900002', 'direccion' => 'Av. Hernando Siles 43', 'antecedentes_medicos' => 'Alergia a la Penicilina'],
            ['id_folder' => 3, 'ci_dni' => '5003', 'nombres' => 'Carlos', 'apellidos' => 'Medinaceli', 'fecha_nacimiento' => '1993-11-02', 'sexo' => 'M', 'telefono' => '78900003', 'direccion' => 'Pasaje Tarija 5', 'antecedentes_medicos' => 'Hipertenso controlado'],
            ['id_folder' => 4, 'ci_dni' => '5004', 'nombres' => 'Diana', 'apellidos' => 'Zárate', 'fecha_nacimiento' => '1998-02-14', 'sexo' => 'F', 'telefono' => '78900004', 'direccion' => 'Zona San Roque Nro 34', 'antecedentes_medicos' => 'Ninguno'],
            
            // Doctor 2 (ID 5-8)
            ['id_folder' => 5, 'ci_dni' => '5005', 'nombres' => 'Edgar', 'apellidos' => 'Morales', 'fecha_nacimiento' => '1978-04-30', 'sexo' => 'M', 'telefono' => '78900005', 'direccion' => 'Calle Dalence 211', 'antecedentes_medicos' => 'Diabetes Tipo II'],
            ['id_folder' => 6, 'ci_dni' => '5006', 'nombres' => 'Fabiola', 'apellidos' => 'Ortega', 'fecha_nacimiento' => '2001-09-17', 'sexo' => 'F', 'telefono' => '78900006', 'direccion' => 'Av. Venezuela 890', 'antecedentes_medicos' => 'Ninguno'],
            ['id_folder' => 7, 'ci_dni' => '5007', 'nombres' => 'Gabriel', 'apellidos' => 'Hurtado', 'fecha_nacimiento' => '1988-01-25', 'sexo' => 'M', 'telefono' => '78900007', 'direccion' => 'Calle Bustillos Nro 402', 'antecedentes_medicos' => 'Asmático'],
            ['id_folder' => 8, 'ci_dni' => '5008', 'nombres' => 'Helena', 'apellidos' => 'Maldonado', 'fecha_nacimiento' => '1995-07-07', 'sexo' => 'F', 'telefono' => '78900008', 'direccion' => 'Barrio Petrolero C.3', 'antecedentes_medicos' => 'Ninguno'],
            
            // Doctor 3 (ID 9-12)
            ['id_folder' => 9, 'ci_dni' => '5009', 'nombres' => 'Ignacio', 'apellidos' => 'Suárez', 'fecha_nacimiento' => '1992-12-03', 'sexo' => 'M', 'telefono' => '78900009', 'direccion' => 'Calle Audiencia 78', 'antecedentes_medicos' => 'Ninguno'],
            ['id_folder' => 10, 'ci_dni' => '5010', 'nombres' => 'Jacqueline', 'apellidos' => 'Vaca', 'fecha_nacimiento' => '1989-03-19', 'sexo' => 'F', 'telefono' => '78900010', 'direccion' => 'Av. del Maestro 156', 'antecedentes_medicos' => 'Problemas de Coagulación'],
            ['id_folder' => 11, 'ci_dni' => '5011', 'nombres' => 'Kevin', 'apellidos' => 'Justiniano', 'fecha_nacimiento' => '1996-06-21', 'sexo' => 'M', 'telefono' => '78900011', 'direccion' => 'Calle Estudiantes Nro 12', 'antecedentes_medicos' => 'Ninguno'],
            ['id_folder' => 12, 'ci_dni' => '5012', 'nombres' => 'Laura', 'apellidos' => 'Montaño', 'fecha_nacimiento' => '2000-10-11', 'sexo' => 'F', 'telefono' => '78900012', 'direccion' => 'Zona Urbarí Calle 5', 'antecedentes_medicos' => 'Ninguno'],
            
            // Doctor 4 (ID 13-16)
            ['id_folder' => 13, 'ci_dni' => '5013', 'nombres' => 'Mauricio', 'apellidos' => 'Prado', 'fecha_nacimiento' => '1983-02-28', 'sexo' => 'M', 'telefono' => '78900013', 'direccion' => 'Calle Kilómetro 2', 'antecedentes_medicos' => 'Gastritis Crónica'],
            ['id_folder' => 14, 'ci_dni' => '5014', 'nombres' => 'Natalia', 'apellidos' => 'Torres', 'fecha_nacimiento' => '2015-05-05', 'sexo' => 'F', 'telefono' => '78900014', 'direccion' => 'Pasaje Arenales 45', 'antecedentes_medicos' => 'Ninguno (Paciente Niña)'],
            ['id_folder' => 15, 'ci_dni' => '5015', 'nombres' => 'Omar', 'apellidos' => 'Benítez', 'fecha_nacimiento' => '2017-08-19', 'sexo' => 'M', 'telefono' => '78900015', 'direccion' => 'Av. Las Vilas S/N', 'antecedentes_medicos' => 'Ninguno (Paciente Niño)'],
            ['id_folder' => 16, 'ci_dni' => '5016', 'nombres' => 'Paola', 'apellidos' => 'Villalba', 'fecha_nacimiento' => '2014-01-09', 'sexo' => 'F', 'telefono' => '78900016', 'direccion' => 'Calle Padilla Nro 617', 'antecedentes_medicos' => 'Alergia al Látex'],
            
            // Doctor 5 (ID 17-20)
            ['id_folder' => 17, 'ci_dni' => '5017', 'nombres' => 'Ricardo', 'apellidos' => 'Giménez', 'fecha_nacimiento' => '1975-07-24', 'sexo' => 'M', 'telefono' => '78900017', 'direccion' => 'Calle España 320', 'antecedentes_medicos' => 'Sopló Cardíaco Controlado'],
            ['id_folder' => 18, 'ci_dni' => '5018', 'nombres' => 'Silvia', 'apellidos' => 'Díaz', 'fecha_nacimiento' => '1982-11-13', 'sexo' => 'F', 'telefono' => '78900018', 'direccion' => 'Av. Marcelo Quiroga 109', 'antecedentes_medicos' => 'Ninguno'],
            ['id_folder' => 19, 'ci_dni' => '5019', 'nombres' => 'Tomás', 'apellidos' => 'Valenzuela', 'fecha_nacimiento' => '1991-03-08', 'sexo' => 'M', 'telefono' => '78900019', 'direccion' => 'Calle Colón Nro 44', 'antecedentes_medicos' => 'Ninguno'],
            ['id_folder' => 20, 'ci_dni' => '5020', 'nombres' => 'Úrsula', 'apellidos' => 'Fontana', 'fecha_nacimiento' => '1994-04-04', 'sexo' => 'F', 'telefono' => '78900020', 'direccion' => 'Zona El Abra Calle Arriba', 'antecedentes_medicos' => 'Ninguno']
        ];

        foreach ($pacientes as $paciente) {
            DB::table('PACIENTE')->insert($paciente);
        }
    }
}