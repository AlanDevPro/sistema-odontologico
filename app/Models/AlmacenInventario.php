<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlmacenInventario extends Model
{
    /**
     * Nombre real de la tabla en Oracle (en mayúsculas según el DDL).
     */
    protected $table = 'ALMACEN_INVENTARIO';

    /**
     * Clave primaria definida con GENERATED ALWAYS AS IDENTITY.
     */
    protected $primaryKey = 'id_inventario';

    /**
     * La tabla no usa created_at / updated_at de Laravel; tiene su propia
     * columna 'ultima_actualizacion' que el trigger de Oracle mantiene.
     */
    public $timestamps = false;

    protected $fillable = [
        'id_suministro',
        'stock_actual',
        'ultima_actualizacion',
    ];

    protected $casts = [
        'stock_actual'         => 'float',
        'ultima_actualizacion' => 'datetime',
    ];

    public function suministro(): BelongsTo
    {
        return $this->belongsTo(Suministro::class, 'id_suministro');
    }
}