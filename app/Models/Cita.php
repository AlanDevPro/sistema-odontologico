<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $connection = 'oracle';
    protected $table = 'cita';
    protected $primaryKey = 'id_cita';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    public const ESTADOS = ['Pendiente', 'Confirmada', 'Atendida', 'Cancelada', 'Reprogramada'];

    protected $fillable = [
        'id_paciente',
        'id_doctor',
        'id_asistente',
        'fecha_hora',
        'motivo',
        'estado',
    ];

    protected $casts = [
        'fecha_hora'     => 'datetime',
        'fecha_creacion' => 'date',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doctor', 'id_doctor');
    }

    public function asistente()
    {
        return $this->belongsTo(Asistente::class, 'id_asistente', 'id_asistente');
    }
}