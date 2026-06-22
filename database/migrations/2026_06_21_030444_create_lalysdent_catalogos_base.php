<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE ASISTENTE (
                id_asistente NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                ci_dni VARCHAR2(20) UNIQUE NOT NULL,
                nombres VARCHAR2(80) NOT NULL,
                apellidos VARCHAR2(80) NOT NULL,
                telefono VARCHAR2(15),
                turno VARCHAR2(20),
                fecha_contratacion DATE,
                estado NUMBER(1) DEFAULT 1 NOT NULL 
            )
        ");

        DB::statement("
            CREATE TABLE FOLDER (
                id_folder NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                codigo_archivo VARCHAR2(20) UNIQUE NOT NULL,
                estante VARCHAR2(20),
                seccion VARCHAR2(20),
                observaciones VARCHAR2(255)
            )
        ");

        DB::statement("
            CREATE TABLE TRATAMIENTO (
                id_tratamiento NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                nombre VARCHAR2(100) NOT NULL,
                descripcion VARCHAR2(255),
                costo_referencial NUMBER(10, 2) NOT NULL,
                estado NUMBER(1) DEFAULT 1 NOT NULL
            )
        ");

        DB::statement("
            CREATE TABLE DOCTOR (
                id_doctor NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                ci_dni VARCHAR2(20) UNIQUE NOT NULL,
                nombres VARCHAR2(80) NOT NULL,
                apellidos VARCHAR2(80) NOT NULL,
                especialidad VARCHAR2(100),
                telefono VARCHAR2(15),
                correo VARCHAR2(100) UNIQUE,
                estado NUMBER(1) DEFAULT 1 NOT NULL
            )
        ");

        DB::statement("
            CREATE TABLE PROVEEDOR (
                id_proveedor NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                nit_ruc VARCHAR2(20) UNIQUE NOT NULL,
                razon_social VARCHAR2(100) NOT NULL,
                nombre_contacto VARCHAR2(80),
                telefono VARCHAR2(15),
                direccion VARCHAR2(255),
                correo VARCHAR2(100)
            )
        ");

        DB::statement("
            CREATE TABLE SUMINISTRO (
                id_suministro NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                codigo_barras VARCHAR2(50) UNIQUE,
                nombre VARCHAR2(100) NOT NULL,
                categoria VARCHAR2(50),
                unidad_medida VARCHAR2(20),
                stock_minimo NUMBER(5) DEFAULT 5 NOT NULL,
                estado NUMBER(1) DEFAULT 1 NOT NULL
            )
        ");
    }

    public function down(): void
    {
        $tables = ['SUMINISTRO', 'PROVEEDOR', 'DOCTOR', 'TRATAMIENTO', 'FOLDER', 'ASISTENTE'];
        foreach ($tables as $table) {
            DB::statement("DROP TABLE {$table} CASCADE CONSTRAINTS");
        }
    }
};