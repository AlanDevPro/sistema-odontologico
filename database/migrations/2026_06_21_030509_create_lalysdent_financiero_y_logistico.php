<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE PLAN_PAGO (
                id_plan_pago NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_paciente NUMBER NOT NULL,
                id_odontograma NUMBER NOT NULL,
                fecha_creacion DATE DEFAULT SYSDATE,
                costo_total NUMBER(10, 2) NOT NULL,
                saldo_pendiente NUMBER(10, 2) NOT NULL,
                estado VARCHAR2(20) DEFAULT 'Vigente',
                CONSTRAINT fk_plan_paciente FOREIGN KEY (id_paciente) REFERENCES PACIENTE(id_paciente),
                CONSTRAINT fk_plan_odonto FOREIGN KEY (id_odontograma) REFERENCES ODONTOGRAMA(id_odontograma)
            )
        ");

        DB::statement("
            CREATE TABLE PAGO (
                id_pago NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_plan_pago NUMBER NOT NULL,
                id_asistente NUMBER,
                fecha_pago TIMESTAMP DEFAULT SYSTIMESTAMP NOT NULL,
                monto_abonado NUMBER(10, 2) NOT NULL,
                metodo_pago VARCHAR2(30) NOT NULL,
                nro_comprobante VARCHAR2(50),
                CONSTRAINT fk_pago_plan FOREIGN KEY (id_plan_pago) REFERENCES PLAN_PAGO(id_plan_pago),
                CONSTRAINT fk_pago_asistente FOREIGN KEY (id_asistente) REFERENCES ASISTENTE(id_asistente),
                CONSTRAINT chk_monto_pago CHECK (monto_abonado > 0)
            )
        ");

        DB::statement("
            CREATE TABLE COMPRA (
                id_compra NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_proveedor NUMBER NOT NULL,
                id_asistente NUMBER,
                fecha_compra DATE NOT NULL,
                nro_factura VARCHAR2(50),
                total_compra NUMBER(10, 2) NOT NULL,
                estado VARCHAR2(20) DEFAULT 'Completada',
                CONSTRAINT fk_compra_proveedor FOREIGN KEY (id_proveedor) REFERENCES PROVEEDOR(id_proveedor),
                CONSTRAINT fk_compra_asistente FOREIGN KEY (id_asistente) REFERENCES ASISTENTE(id_asistente)
            )
        ");

        DB::statement("
            CREATE TABLE DETALLE_COMPRA (
                id_detalle_compra NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_compra NUMBER NOT NULL,
                id_suministro NUMBER NOT NULL,
                cantidad NUMBER(8,2) NOT NULL,
                precio_unitario NUMBER(10, 2) NOT NULL,
                subtotal NUMBER(10, 2) NOT NULL,
                CONSTRAINT fk_detcompra_compra FOREIGN KEY (id_compra) REFERENCES COMPRA(id_compra) ON DELETE CASCADE,
                CONSTRAINT fk_detcompra_suministro FOREIGN KEY (id_suministro) REFERENCES SUMINISTRO(id_suministro)
            )
        ");

        DB::statement("
            CREATE TABLE ALMACEN_INVENTARIO (
                id_inventario NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                id_suministro NUMBER UNIQUE NOT NULL,
                stock_actual NUMBER(8,2) DEFAULT 0 NOT NULL,
                ultima_actualizacion TIMESTAMP DEFAULT SYSTIMESTAMP,
                CONSTRAINT fk_inventario_suministro FOREIGN KEY (id_suministro) REFERENCES SUMINISTRO(id_suministro)
            )
        ");
    }

    public function down(): void
    {
        $tables = ['ALMACEN_INVENTARIO', 'DETALLE_COMPRA', 'COMPRA', 'PAGO', 'PLAN_PAGO'];
        foreach ($tables as $table) {
            DB::statement("DROP TABLE {$table} CASCADE CONSTRAINTS");
        }
    }
};