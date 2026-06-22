<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE PACIENTE (
                id_paciente NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_folder NUMBER,
                ci_dni VARCHAR2(20) UNIQUE NOT NULL,
                nombres VARCHAR2(80) NOT NULL,
                apellidos VARCHAR2(80) NOT NULL,
                fecha_nacimiento DATE NOT NULL,
                sexo CHAR(1), 
                telefono VARCHAR2(15),
                direccion VARCHAR2(255),
                antecedentes_medicos VARCHAR2(500),
                fecha_registro DATE DEFAULT SYSDATE,
                CONSTRAINT fk_paciente_folder FOREIGN KEY (id_folder) REFERENCES FOLDER(id_folder) ON DELETE SET NULL
            )
        ");

        DB::statement("
            CREATE TABLE CITA (
                id_cita NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_paciente NUMBER NOT NULL,
                id_doctor NUMBER NOT NULL,
                id_asistente NUMBER,
                fecha_hora TIMESTAMP NOT NULL,
                motivo VARCHAR2(255) NOT NULL,
                estado VARCHAR2(20) DEFAULT 'Pendiente' NOT NULL, 
                fecha_creacion DATE DEFAULT SYSDATE,
                CONSTRAINT fk_cita_paciente FOREIGN KEY (id_paciente) REFERENCES PACIENTE(id_paciente),
                CONSTRAINT fk_cita_doctor FOREIGN KEY (id_doctor) REFERENCES DOCTOR(id_doctor),
                CONSTRAINT fk_cita_asistente FOREIGN KEY (id_asistente) REFERENCES ASISTENTE(id_asistente),
                CONSTRAINT chk_estado_cita CHECK (estado IN ('Pendiente', 'Confirmada', 'Atendida', 'Cancelada', 'Reprogramada'))
            )
        ");

        DB::statement("
            CREATE TABLE ODONTOGRAMA (
                id_odontograma NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_paciente NUMBER NOT NULL,
                id_doctor NUMBER NOT NULL,
                fecha_evaluacion DATE DEFAULT SYSDATE NOT NULL,
                observaciones_generales VARCHAR2(500),
                CONSTRAINT fk_odonto_paciente FOREIGN KEY (id_paciente) REFERENCES PACIENTE(id_paciente),
                CONSTRAINT fk_odonto_doctor FOREIGN KEY (id_doctor) REFERENCES DOCTOR(id_doctor)
            )
        ");

        DB::statement("
            CREATE TABLE DETALLE_ODONTOGRAMA (
                id_detalle NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_odontograma NUMBER NOT NULL,
                id_tratamiento NUMBER,
                pieza_dental VARCHAR2(10) NOT NULL,
                cara VARCHAR2(20) NOT NULL,
                diagnostico VARCHAR2(100) NOT NULL,
                estado VARCHAR2(20) DEFAULT 'Por tratar',
                CONSTRAINT fk_det_odontograma FOREIGN KEY (id_odontograma) REFERENCES ODONTOGRAMA(id_odontograma) ON DELETE CASCADE,
                CONSTRAINT fk_det_tratamiento FOREIGN KEY (id_tratamiento) REFERENCES TRATAMIENTO(id_tratamiento)
            )
        ");
    }

    public function down(): void
    {
        $tables = ['DETALLE_ODONTOGRAMA', 'ODONTOGRAMA', 'CITA', 'PACIENTE'];
        foreach ($tables as $table) {
            DB::statement("DROP TABLE {$table} CASCADE CONSTRAINTS");
        }
    }
};