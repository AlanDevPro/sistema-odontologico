<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
{
    /**
     * Nombre real de la tabla en Oracle (en mayúsculas según el DDL).
     */
    protected $table = 'COMPRA';

    /**
     * Clave primaria definida con GENERATED ALWAYS AS IDENTITY.
     */
    protected $primaryKey = 'id_compra';

    /**
     * La tabla no maneja timestamps de Laravel (created_at / updated_at).
     */
    public $timestamps = false;

    /**
     * Columnas que se pueden asignar de forma masiva.
     * Coinciden exactamente con las columnas físicas de COMPRA.
     */
    protected $fillable = [
        'id_proveedor',
        'id_asistente',
        'fecha_compra',
        'nro_factura',
        'total_compra',
        'estado',
    ];

    protected $casts = [
        'fecha_compra'  => 'date',
        'total_compra'  => 'decimal:2',
    ];

    // ----------------------------------------------------------------
    // RELACIONES
    // ----------------------------------------------------------------

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function asistente(): BelongsTo
    {
        return $this->belongsTo(Asistente::class, 'id_asistente');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra');
    }

    // ----------------------------------------------------------------
    // LÓGICA DE NEGOCIO
    // ----------------------------------------------------------------

    /**
     * Recalcula y persiste total_compra a partir de la suma de subtotales
     * de DETALLE_COMPRA. COMPRA.total_compra no tiene trigger propio,
     * así que debe mantenerse desde la aplicación.
     */
    public function recalcularTotal(): void
    {
        $total = $this->detalles()->sum('subtotal');
        $this->update(['total_compra' => $total]);
    }
}