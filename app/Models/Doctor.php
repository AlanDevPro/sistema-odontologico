<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    // Conexión específica para Oracle
    protected $connection = 'oracle';
    
    // En Oracle 19c las tablas se almacenan en mayúsculas por defecto
    protected $table = 'DOCTOR';
    
    protected $primaryKey = 'id_doctor';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    /**
     * Se añade 'user_id' para poder vincular el perfil médico con el usuario de Laravel.
     */
    protected $fillable = [
        'user_id',
        'ci_dni',
        'nombres',
        'apellidos',
        'especialidad',
        'telefono',
        'correo',
        'estado',
    ];

    protected $casts = [
        'estado' => 'integer',
    ];

    /**
     * Obtener el usuario de autenticación de Jetstream/Fortify asociado al doctor.
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        // Relaciona 'user_id' de la tabla DOCTOR con el 'id' de la tabla USERS
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope para filtrar únicamente los doctores activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }

    /**
     * Accesor para obtener el nombre completo del médico de forma limpia.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    /**
     * Relación con los odontogramas realizados por el doctor.
     */
    public function odontogramas()
    {
        return $this->hasMany(Odontograma::class, 'id_doctor', 'id_doctor');
    }
}