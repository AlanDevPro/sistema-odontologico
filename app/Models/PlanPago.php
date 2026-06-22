<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanPago extends Model
{
    protected $table = 'PLAN_PAGO';
    protected $primaryKey = 'id_plan_pago';
    public $timestamps = false;

    protected $fillable = [
        'id_paciente',
        'id_odontograma',
        'fecha_creacion',
        'costo_total',
        'saldo_pendiente',
        'estado',
    ];

    protected $casts = [
        'fecha_creacion'  => 'date',
        'costo_total'     => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    public function odontograma(): BelongsTo
    {
        return $this->belongsTo(Odontograma::class, 'id_odontograma');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_plan_pago');
    }
}