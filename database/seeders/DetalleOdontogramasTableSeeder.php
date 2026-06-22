<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetalleOdontogramasTableSeeder extends Seeder
{
    public function run(): void
    {
        $detalles = [
            ['id_odontograma' => 1, 'id_tratamiento' => 1, 'pieza_dental' => '18', 'cara' => 'Oclusal', 'diagnostico' => 'Caries dental incipiente', 'estado' => 'Por tratar'],
            ['id_odontograma' => 2, 'id_tratamiento' => 2, 'pieza_dental' => '24', 'cara' => 'Mesial', 'diagnostico' => 'Fractura parcial de amalgama antigua', 'estado' => 'Por tratar'],
            ['id_odontograma' => 3, 'id_tratamiento' => 2, 'pieza_dental' => '11', 'cara' => 'Vestibular', 'diagnostico' => 'Pérdida de estética dental', 'estado' => 'Por tratar'],
            ['id_odontograma' => 4, 'id_tratamiento' => 1, 'pieza_dental' => '46', 'cara' => 'Oclusal', 'diagnostico' => 'Placa bacteriana calcificada', 'estado' => 'Por tratar'],
            ['id_odontograma' => 5, 'id_tratamiento' => 7, 'pieza_dental' => 'Global', 'cara' => 'Arcadas', 'diagnostico' => 'Maloclusión Clase II division I', 'estado' => 'Por tratar'],
            ['id_odontograma' => 6, 'id_tratamiento' => 7, 'pieza_dental' => 'Global', 'cara' => 'Arcadas', 'diagnostico' => 'Apinamiento anteroinferior severo', 'estado' => 'Por tratar'],
            ['id_odontograma' => 7, 'id_tratamiento' => 1, 'pieza_dental' => '36', 'cara' => 'Lingual', 'diagnostico' => 'Manchas extrínsecas por sarro', 'estado' => 'Por tratar'],
            ['id_odontograma' => 8, 'id_tratamiento' => 2, 'pieza_dental' => '14', 'cara' => 'Distal', 'diagnostico' => 'Caries dentinaria penetrante', 'estado' => 'Por tratar'],
            ['id_odontograma' => 9, 'id_tratamiento' => 4, 'pieza_dental' => '38', 'cara' => 'Oclusal', 'diagnostico' => 'Pieza impactada en posición horizontal', 'estado' => 'Por tratar'],
            ['id_odontograma' => 10, 'id_tratamiento' => 4, 'pieza_dental' => '48', 'cara' => 'Oclusal', 'diagnostico' => 'Pericoronaritis recurrente', 'estado' => 'Por tratar'],
            ['id_odontograma' => 11, 'id_tratamiento' => 2, 'pieza_dental' => '12', 'cara' => 'Palatina', 'diagnostico' => 'Defecto estructural del esmalte', 'estado' => 'Por tratar'],
            ['id_odontograma' => 12, 'id_tratamiento' => 1, 'pieza_dental' => '26', 'cara' => 'Oclusal', 'diagnostico' => 'Sarro subgingival generalizado', 'estado' => 'Por tratar'],
            ['id_odontograma' => 13, 'id_tratamiento' => 8, 'pieza_dental' => '54', 'cara' => 'Oclusal', 'diagnostico' => 'Caries rampante del lactante', 'estado' => 'Por tratar'],
            ['id_odontograma' => 14, 'id_tratamiento' => 1, 'pieza_dental' => '63', 'cara' => 'Vestibular', 'diagnostico' => 'Profilaxis preventiva escolar', 'estado' => 'Por tratar'],
            ['id_odontograma' => 15, 'id_tratamiento' => 8, 'pieza_dental' => '75', 'cara' => 'Distal', 'diagnostico' => 'Necrosis pulpar decidua', 'estado' => 'Por tratar'],
            ['id_odontograma' => 16, 'id_tratamiento' => 2, 'pieza_dental' => '84', 'cara' => 'Oclusal', 'diagnostico' => 'Destrucción coronaria por caries', 'estado' => 'Por tratar'],
            ['id_odontograma' => 17, 'id_tratamiento' => 3, 'pieza_dental' => '21', 'cara' => 'Palatina', 'diagnostico' => 'Pulpitis irreversible aguda', 'estado' => 'Por tratar'],
            ['id_odontograma' => 18, 'id_tratamiento' => 3, 'pieza_dental' => '16', 'cara' => 'Oclusal', 'diagnostico' => 'Conducto calcificado con lesión apical', 'estado' => 'Por tratar'],
            ['id_odontograma' => 19, 'id_tratamiento' => 3, 'pieza_dental' => '42', 'cara' => 'Lingual', 'diagnostico' => 'Trauma oclusal con compromiso pulpar', 'estado' => 'Por tratar'],
            ['id_odontograma' => 20, 'id_tratamiento' => 1, 'pieza_dental' => '31', 'cara' => 'Oclusal', 'diagnostico' => 'Incrustaciones calcáreas densas', 'estado' => 'Por tratar']
        ];

        foreach ($detalles as $detalle) {
            DB::table('DETALLE_ODONTOGRAMA')->insert($detalle);
        }
    }
}