<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistente extends Model
{
    protected $connection = 'oracle';
    protected $table = 'asistente';
    protected $primaryKey = 'id_asistente';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'ci_dni',
        'nombres',
        'apellidos',
        'telefono',
        'turno',
        'fecha_contratacion',
        'estado',
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
        'estado'             => 'integer',
    ];

    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_asistente', 'id_asistente');
    }
}





