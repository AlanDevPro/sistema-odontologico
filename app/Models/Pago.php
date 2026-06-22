<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'PAGO';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'id_plan_pago',
        'id_asistente',
        'fecha_pago',
        'monto_abonado',
        'metodo_pago',
        'nro_comprobante',
    ];

    protected $casts = [
        'fecha_pago'    => 'datetime',
        'monto_abonado' => 'decimal:2',
    ];

    public function planPago(): BelongsTo
    {
        return $this->belongsTo(PlanPago::class, 'id_plan_pago');
    }

    public function asistente(): BelongsTo
    {
        return $this->belongsTo(Asistente::class, 'id_asistente');
    }
}