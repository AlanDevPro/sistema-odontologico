<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitasTableSeeder extends Seeder
{
    public function run(): void
    {
        // Citas Doctor 1 (Asistentes 1 y 2)
        $citas = [
            // Doctor 1
            ['id_paciente' => 1, 'id_doctor' => 1, 'id_asistente' => 1, 'fecha_hora' => '2026-06-22 09:00:00', 'motivo' => 'Evaluación y limpieza', 'estado' => 'Confirmada'],
            ['id_paciente' => 2, 'id_doctor' => 1, 'id_asistente' => 1, 'fecha_hora' => '2026-06-22 10:00:00', 'motivo' => 'Dolor molar izquierdo', 'estado' => 'Atendida'],
            ['id_paciente' => 3, 'id_doctor' => 1, 'id_asistente' => 2, 'fecha_hora' => '2026-06-23 15:00:00', 'motivo' => 'Calza estética resina', 'estado' => 'Pendiente'],
            ['id_paciente' => 4, 'id_doctor' => 1, 'id_asistente' => 2, 'fecha_hora' => '2026-06-24 16:30:00', 'motivo' => 'Revisión periódica', 'estado' => 'Cancelada'],
            ['id_paciente' => 1, 'id_doctor' => 1, 'id_asistente' => 1, 'fecha_hora' => '2026-06-25 09:00:00', 'motivo' => 'Profilaxis de seguimiento', 'estado' => 'Pendiente'],
            ['id_paciente' => 2, 'id_doctor' => 1, 'id_asistente' => 1, 'fecha_hora' => '2026-06-26 11:00:00', 'motivo' => 'Obturación compuesta', 'estado' => 'Pendiente'],
            ['id_paciente' => 3, 'id_doctor' => 1, 'id_asistente' => 2, 'fecha_hora' => '2026-06-27 14:00:00', 'motivo' => 'Corona provisional', 'estado' => 'Reprogramada'],
            ['id_paciente' => 4, 'id_doctor' => 1, 'id_asistente' => 2, 'fecha_hora' => '2026-06-28 17:00:00', 'motivo' => 'Blanqueamiento láser', 'estado' => 'Pendiente'],
            
            // Doctor 2
            ['id_paciente' => 5, 'id_doctor' => 2, 'id_asistente' => 3, 'fecha_hora' => '2026-06-22 08:30:00', 'motivo' => 'Control mensual ortodoncia', 'estado' => 'Atendida'],
            ['id_paciente' => 6, 'id_doctor' => 2, 'id_asistente' => 3, 'fecha_hora' => '2026-06-22 11:30:00', 'motivo' => 'Ajuste arco NiTi', 'estado' => 'Confirmada'],
            ['id_paciente' => 7, 'id_doctor' => 2, 'id_asistente' => 4, 'fecha_hora' => '2026-06-23 14:00:00', 'motivo' => 'Cementado de tubos', 'estado' => 'Pendiente'],
            ['id_paciente' => 8, 'id_doctor' => 2, 'id_asistente' => 4, 'fecha_hora' => '2026-06-24 15:00:00', 'motivo' => 'Cambio de ligaduras', 'estado' => 'Pendiente'],
            ['id_paciente' => 5, 'id_doctor' => 2, 'id_asistente' => 3, 'fecha_hora' => '2026-07-22 08:30:00', 'motivo' => 'Control brackets rutina', 'estado' => 'Pendiente'],
            ['id_paciente' => 6, 'id_doctor' => 2, 'id_asistente' => 3, 'fecha_hora' => '2026-07-22 11:30:00', 'motivo' => 'Reposición bracket caído', 'estado' => 'Pendiente'],
            ['id_paciente' => 7, 'id_doctor' => 2, 'id_asistente' => 4, 'fecha_hora' => '2026-07-23 14:00:00', 'motivo' => 'Evaluación perfil estético', 'estado' => 'Pendiente'],
            ['id_paciente' => 8, 'id_doctor' => 2, 'id_asistente' => 4, 'fecha_hora' => '2026-07-24 15:00:00', 'motivo' => 'Limpieza interproximal', 'estado' => 'Cancelada'],
            
            // Doctor 3
            ['id_paciente' => 9, 'id_doctor' => 3, 'id_asistente' => 5, 'fecha_hora' => '2026-06-22 14:00:00', 'motivo' => 'Exodoncia cordal 18', 'estado' => 'Atendida'],
            ['id_paciente' => 10, 'id_doctor' => 3, 'id_asistente' => 5, 'fecha_hora' => '2026-06-23 09:00:00', 'motivo' => 'Retiro de puntos', 'estado' => 'Confirmada'],
            ['id_paciente' => 11, 'id_doctor' => 3, 'id_asistente' => 6, 'fecha_hora' => '2026-06-24 10:30:00', 'motivo' => 'Evaluación tercer molar', 'estado' => 'Pendiente'],
            ['id_paciente' => 12, 'id_doctor' => 3, 'id_asistente' => 6, 'fecha_hora' => '2026-06-25 16:00:00', 'motivo' => 'Quiste radicular cirugía', 'estado' => 'Pendiente'],
            ['id_paciente' => 9, 'id_doctor' => 3, 'id_asistente' => 5, 'fecha_hora' => '2026-06-29 14:00:00', 'motivo' => 'Control óseo postquirúrgico', 'estado' => 'Pendiente'],
            ['id_paciente' => 10, 'id_doctor' => 3, 'id_asistente' => 5, 'fecha_hora' => '2026-06-30 09:00:00', 'motivo' => 'Regularización de reborde', 'estado' => 'Pendiente'],
            ['id_paciente' => 11, 'id_doctor' => 3, 'id_asistente' => 6, 'fecha_hora' => '2026-07-01 10:30:00', 'motivo' => 'Exodoncia cordal 28', 'estado' => 'Pendiente'],
            ['id_paciente' => 12, 'id_doctor' => 3, 'id_asistente' => 6, 'fecha_hora' => '2026-07-02 16:00:00', 'motivo' => 'Frenectomía lingual', 'estado' => 'Pendiente'],
            
            // Doctor 4
            ['id_paciente' => 13, 'id_doctor' => 4, 'id_asistente' => 7, 'fecha_hora' => '2026-06-22 16:00:00', 'motivo' => 'Pulpotomía pieza 54', 'estado' => 'Atendida'],
            ['id_paciente' => 14, 'id_doctor' => 4, 'id_asistente' => 7, 'fecha_hora' => '2026-06-23 11:00:00', 'motivo' => 'Fluorización tópica', 'estado' => 'Confirmada'],
            ['id_paciente' => 15, 'id_doctor' => 4, 'id_asistente' => 7, 'fecha_hora' => '2026-06-24 14:00:00', 'motivo' => 'Selladores de fosas', 'estado' => 'Pendiente'],
            ['id_paciente' => 16, 'id_doctor' => 4, 'id_asistente' => 7, 'fecha_hora' => '2026-06-25 15:30:00', 'motivo' => 'Corona de acero cromo', 'estado' => 'Pendiente'],
            ['id_paciente' => 13, 'id_doctor' => 4, 'id_asistente' => 7, 'fecha_hora' => '2026-07-05 16:00:00', 'motivo' => 'Control adaptación sellador', 'estado' => 'Pendiente'],
            ['id_paciente' => 14, 'id_doctor' => 4, 'id_asistente' => 7, 'fecha_hora' => '2026-07-06 11:00:00', 'motivo' => 'Revisión de higiene bucal', 'estado' => 'Pendiente'],
            ['id_paciente' => 15, 'id_doctor' => 4, 'id_asistente' => 7, 'fecha_hora' => '2026-07-07 14:00:00', 'motivo' => 'Extracción pieza decidua', 'estado' => 'Reprogramada'],
            ['id_paciente' => 16, 'id_doctor' => 4, 'id_asistente' => 7, 'fecha_hora' => '2026-07-08 15:30:00', 'motivo' => 'Reconstrucción fractura corona', 'estado' => 'Pendiente'],
            
            // Doctor 5
            ['id_paciente' => 17, 'id_doctor' => 5, 'id_asistente' => 8, 'fecha_hora' => '2026-06-22 10:30:00', 'motivo' => 'Biopulpectomía de pieza 21', 'estado' => 'Atendida'],
            ['id_paciente' => 18, 'id_doctor' => 5, 'id_asistente' => 8, 'fecha_hora' => '2026-06-23 16:00:00', 'motivo' => 'Instrumentación de conductos', 'estado' => 'Confirmada'],
            ['id_paciente' => 19, 'id_doctor' => 5, 'id_asistente' => 8, 'fecha_hora' => '2026-06-24 09:30:00', 'motivo' => 'Condensación gutapercha', 'estado' => 'Pendiente'],
            ['id_paciente' => 20, 'id_doctor' => 5, 'id_asistente' => 8, 'fecha_hora' => '2026-06-25 14:00:00', 'motivo' => 'Dolor agudo radicular', 'estado' => 'Pendiente'],
            ['id_paciente' => 17, 'id_doctor' => 5, 'id_asistente' => 8, 'fecha_hora' => '2026-06-29 10:30:00', 'motivo' => 'Obturación de conducto final', 'estado' => 'Pendiente'],
            ['id_paciente' => 18, 'id_doctor' => 5, 'id_asistente' => 8, 'fecha_hora' => '2026-06-30 16:00:00', 'motivo' => 'Radiografía de control pulpar', 'estado' => 'Pendiente'],
            ['id_paciente' => 19, 'id_doctor' => 5, 'id_asistente' => 8, 'fecha_hora' => '2026-07-01 09:30:00', 'motivo' => 'Medicamento intraconducto', 'estado' => 'Pendiente'],
            ['id_paciente' => 20, 'id_doctor' => 5, 'id_asistente' => 8, 'fecha_hora' => '2026-07-02 14:00:00', 'motivo' => 'Control clínico remisión dolor', 'estado' => 'Pendiente']
        ];

        foreach ($citas as $cita) {
            DB::table('CITA')->insert($cita);
        }
    }
}