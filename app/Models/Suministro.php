<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Suministro extends Model
{
    /**
     * Nombre real de la tabla en Oracle (en mayúsculas según el DDL).
     */
    protected $table = 'SUMINISTRO';

    /**
     * Clave primaria definida con GENERATED ALWAYS AS IDENTITY.
     */
    protected $primaryKey = 'id_suministro';

    /**
     * La tabla no maneja timestamps (created_at / updated_at).
     */
    public $timestamps = false;

    /**
     * Columnas que se pueden asignar de forma masiva.
     * Coinciden exactamente con las columnas físicas de SUMINISTRO.
     */
    protected $fillable = [
        'codigo_barras',
        'nombre',
        'categoria',
        'unidad_medida',
        'stock_minimo',
        'estado',
    ];

    protected $casts = [
        'stock_minimo' => 'integer',
        'estado'       => 'integer',
    ];

    // ----------------------------------------------------------------
    // RELACIONES
    // ----------------------------------------------------------------

    /**
     * Registro de inventario asociado (1 a 1).
     * El trigger TRG_ACTUALIZAR_STOCK_COMPRA mantiene stock_actual
     * actualizado automáticamente al registrar compras.
     */
    public function inventario(): HasOne
    {
        return $this->hasOne(AlmacenInventario::class, 'id_suministro');
    }

    /**
     * Detalle de compras donde participa este suministro.
     */
    public function detallesCompra()
    {
        return $this->hasMany(DetalleCompra::class, 'id_suministro');
    }

    // ----------------------------------------------------------------
    // SCOPES
    // ----------------------------------------------------------------

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', 1);
    }

    /**
     * Filtra suministros cuyo stock actual (en ALMACEN_INVENTARIO) es > 0.
     * Si el suministro no tiene fila en inventario, se considera sin stock.
     */
    public function scopeConStock(Builder $query): Builder
    {
        return $query->whereHas('inventario', fn ($q) => $q->where('stock_actual', '>', 0));
    }

    /**
     * Filtra suministros sin stock (0 o sin registro en ALMACEN_INVENTARIO).
     */
    public function scopeSinStock(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereDoesntHave('inventario')
              ->orWhereHas('inventario', fn ($sub) => $sub->where('stock_actual', '<=', 0));
        });
    }

    // ----------------------------------------------------------------
    // ATRIBUTOS CALCULADOS
    // ----------------------------------------------------------------

    /**
     * Stock actual leído desde ALMACEN_INVENTARIO (0 si no existe fila aún).
     */
    public function getStockActualAttribute(): float
    {
        return $this->inventario?->stock_actual ?? 0;
    }

    /**
     * Indica si el stock actual está por debajo del mínimo definido.
     */
    public function getStockBajoAttribute(): bool
    {
        return $this->stock_actual < $this->stock_minimo;
    }
}