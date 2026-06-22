<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $connection = 'oracle';
    protected $table = 'tratamiento';
    protected $primaryKey = 'id_tratamiento';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'costo_referencial',
        'estado',
    ];

    protected $casts = [
        'costo_referencial' => 'decimal:2',
        'estado'            => 'integer',
    ];

    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleOdontograma::class, 'id_tratamiento', 'id_tratamiento');
    }
}