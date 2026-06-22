<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOdontograma extends Model
{
    protected $connection = 'oracle';
    protected $table = 'detalle_odontograma';
    protected $primaryKey = 'id_detalle';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_odontograma',
        'id_tratamiento',
        'pieza_dental',
        'cara',
        'diagnostico',
        'estado',
    ];

    public function odontograma()
    {
        return $this->belongsTo(Odontograma::class, 'id_odontograma', 'id_odontograma');
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'id_tratamiento', 'id_tratamiento');
    }
}