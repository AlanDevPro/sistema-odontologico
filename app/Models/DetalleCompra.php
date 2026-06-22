<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCompra extends Model
{
    /**
     * Nombre real de la tabla en Oracle (en mayúsculas según el DDL).
     */
    protected $table = 'DETALLE_COMPRA';

    /**
     * Clave primaria definida con GENERATED ALWAYS AS IDENTITY.
     */
    protected $primaryKey = 'id_detalle_compra';

    /**
     * La tabla no maneja timestamps de Laravel (created_at / updated_at).
     */
    public $timestamps = false;

    /**
     * Columnas que se pueden asignar de forma masiva.
     * Coinciden exactamente con las columnas físicas de DETALLE_COMPRA.
     *
     * IMPORTANTE: insertar una fila aquí dispara el trigger Oracle
     * TRG_ACTUALIZAR_STOCK_COMPRA, que suma 'cantidad' al stock_actual
     * del suministro en ALMACEN_INVENTARIO (o crea el registro si no existe).
     */
    protected $fillable = [
        'id_compra',
        'id_suministro',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad'        => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'subtotal'        => 'decimal:2',
    ];

    // ----------------------------------------------------------------
    // RELACIONES
    // ----------------------------------------------------------------

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'id_compra');
    }

    public function suministro(): BelongsTo
    {
        return $this->belongsTo(Suministro::class, 'id_suministro');
    }
}