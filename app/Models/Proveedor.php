<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /**
     * Nombre real de la tabla en Oracle (en mayúsculas según el DDL).
     */
    protected $table = 'PROVEEDOR';

    /**
     * Clave primaria definida con GENERATED ALWAYS AS IDENTITY.
     */
    protected $primaryKey = 'id_proveedor';

    /**
     * La tabla no maneja timestamps (created_at / updated_at).
     */
    public $timestamps = false;

    /**
     * Columnas que se pueden asignar de forma masiva.
     * Coinciden exactamente con las columnas físicas de PROVEEDOR.
     */
    protected $fillable = [
        'nit_ruc',
        'razon_social',
        'nombre_contacto',
        'telefono',
        'direccion',
        'correo',
    ];

    // ----------------------------------------------------------------
    // RELACIONES
    // ----------------------------------------------------------------

    /**
     * Compras realizadas a este proveedor.
     */
    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_proveedor');
    }
}