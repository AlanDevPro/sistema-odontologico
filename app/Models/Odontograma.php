<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odontograma extends Model
{
    protected $connection = 'oracle';
    protected $table = 'odontograma';
    protected $primaryKey = 'id_odontograma';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_paciente',
        'id_doctor',
        'fecha_evaluacion',
        'observaciones_generales',
    ];

    protected $casts = [
        'fecha_evaluacion' => 'datetime',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doctor', 'id_doctor');
    }

    // DETALLE_ODONTOGRAMA tiene ON DELETE CASCADE hacia esta tabla,
    // así que eliminar un odontograma elimina sus hallazgos automáticamente.
    public function detalles()
    {
        return $this->hasMany(DetalleOdontograma::class, 'id_odontograma', 'id_odontograma');
    }
}