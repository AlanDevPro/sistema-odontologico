<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. VISTA GERENCIAL
        DB::statement("
            CREATE OR REPLACE VIEW VW_HISTORIAL_CLINICO AS
            SELECT 
                p.ci_dni AS dni_paciente,
                p.nombres || ' ' || p.apellidos AS nombre_paciente,
                d.nombres || ' ' || d.apellidos AS doctor_tratante,
                o.fecha_evaluacion,
                det.pieza_dental,
                det.diagnostico,
                t.nombre AS tratamiento_aplicado,
                det.estado AS estado_tratamiento
            FROM PACIENTE p
            JOIN ODONTOGRAMA o ON p.id_paciente = o.id_paciente
            JOIN DOCTOR d ON o.id_doctor = d.id_doctor
            JOIN DETALLE_ODONTOGRAMA det ON o.id_odontograma = det.id_odontograma
            LEFT JOIN TRATAMIENTO t ON det.id_tratamiento = t.id_tratamiento
        ");

        // 2. TRIGGER: ACTUALIZAR STOCK COMPRA
        DB::statement("
            CREATE OR REPLACE TRIGGER TRG_ACTUALIZAR_STOCK_COMPRA
            AFTER INSERT ON DETALLE_COMPRA
            FOR EACH ROW
            BEGIN
                UPDATE ALMACEN_INVENTARIO
                SET stock_actual = stock_actual + :NEW.cantidad,
                    ultima_actualizacion = SYSTIMESTAMP
                WHERE id_suministro = :NEW.id_suministro;
                
                IF SQL%ROWCOUNT = 0 THEN
                    INSERT INTO ALMACEN_INVENTARIO (id_suministro, stock_actual)
                    VALUES (:NEW.id_suministro, :NEW.cantidad);
                END IF;
            END;
        ");

        // 3. TRIGGER: AUDITAR PLAN PAGO
        DB::statement("
            CREATE OR REPLACE TRIGGER TRG_AUDITAR_PLAN_PAGO
            BEFORE UPDATE OF saldo_pendiente ON PLAN_PAGO
            FOR EACH ROW
            BEGIN
                IF :NEW.saldo_pendiente < 0 THEN
                    RAISE_APPLICATION_ERROR(-20001, 'Error: El saldo pendiente no puede ser menor a cero.');
                ELSIF :NEW.saldo_pendiente = 0 THEN
                    :NEW.estado := 'Pagado';
                END IF;
            END;
        ");

        // 4. PROCEDIMIENTO ALMACENADO: REGISTRAR PAGO
        DB::statement("
            CREATE OR REPLACE PROCEDURE SP_REGISTRAR_PAGO (
                p_id_plan_pago IN NUMBER,
                p_id_asistente IN NUMBER,
                p_monto IN NUMBER,
                p_metodo_pago IN VARCHAR2,
                p_comprobante IN VARCHAR2
            ) 
            IS
                v_saldo_actual NUMBER(10,2);
            BEGIN
                SELECT saldo_pendiente INTO v_saldo_actual 
                FROM PLAN_PAGO 
                WHERE id_plan_pago = p_id_plan_pago;

                IF p_monto > v_saldo_actual THEN
                    RAISE_APPLICATION_ERROR(-20002, 'El monto a pagar excede el saldo pendiente.');
                END IF;

                INSERT INTO PAGO (id_plan_pago, id_asistente, monto_abonado, metodo_pago, nro_comprobante)
                VALUES (p_id_plan_pago, p_id_asistente, p_monto, p_metodo_pago, p_comprobante);

                UPDATE PLAN_PAGO
                SET saldo_pendiente = saldo_pendiente - p_monto
                WHERE id_plan_pago = p_id_plan_pago;

                COMMIT;
            EXCEPTION
                WHEN NO_DATA_FOUND THEN
                    ROLLBACK;
                    RAISE_APPLICATION_ERROR(-20003, 'Error: El Plan de Pago especificado no existe.');
                WHEN OTHERS THEN
                    ROLLBACK;
                    RAISE_APPLICATION_ERROR(-20004, 'Error en la transacción: ' || SQLERRM);
            END;
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW VW_HISTORIAL_CLINICO");
        DB::statement("DROP TRIGGER TRG_ACTUALIZAR_STOCK_COMPRA");
        DB::statement("DROP TRIGGER TRG_AUDITAR_PLAN_PAGO");
        DB::statement("DROP PROCEDURE SP_REGISTRAR_PAGO");
    }
};