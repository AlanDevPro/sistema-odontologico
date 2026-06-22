<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $connection = 'oracle';
    protected $table = 'paciente';
    protected $primaryKey = 'id_paciente';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_folder',
        'ci_dni',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'direccion',
        'antecedentes_medicos',
        'fecha_registro',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_registro'   => 'date',
    ];

    // ===================== RELACIONES =====================
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'id_folder', 'id_folder');
    }

    public function odontogramas()
    {
        return $this->hasMany(Odontograma::class, 'id_paciente', 'id_paciente');
    }

    // ===================== APELLIDOS (la BD solo tiene 'apellidos' como un único campo) =====================
    // El formulario maneja apellido_paterno / apellido_materno por separado y aquí se
    // dividen/combinan para que coincidan con la columna real de Oracle.

    public function getApellidoPaternoAttribute(): string
    {
        $partes = preg_split('/\s+/', trim((string) $this->apellidos)) ?: [];
        return $partes[0] ?? '';
    }

    public function getApellidoMaternoAttribute(): string
    {
        $partes = preg_split('/\s+/', trim((string) $this->apellidos)) ?: [];
        if (count($partes) <= 1) {
            return '';
        }
        array_shift($partes);
        return implode(' ', $partes);
    }

    public static function combinarApellidos(string $paterno, ?string $materno = null): string
    {
        return trim($paterno . ' ' . ($materno ?? ''));
    }

    // ===================== AVATAR (la BD no tiene columna 'foto') =====================
    public function getInicialesAttribute(): string
    {
        $n = mb_substr(trim((string) $this->nombres), 0, 1);
        $a = mb_substr($this->apellido_paterno, 0, 1);
        return mb_strtoupper($n . $a);
    }

    public function getColorAvatarAttribute(): string
    {
        $paleta = ['#f97316', '#0ea5e9', '#10b981', '#8b5cf6', '#ef4444', '#0d9488', '#d946ef'];
        return $paleta[$this->id_paciente % count($paleta)];
    }

    // ===================== ÚLTIMO TRATAMIENTO REGISTRADO =====================
    // No existe columna 'tratamiento_principal' en PACIENTE: se deduce del último
    // hallazgo (DETALLE_ODONTOGRAMA) con tratamiento asignado, dentro de sus odontogramas.
    public function ultimoTratamiento(): ?string
    {
        $detalle = DetalleOdontograma::whereHas('odontograma', function ($q) {
                $q->where('id_paciente', $this->id_paciente);
            })
            ->whereNotNull('id_tratamiento')
            ->with('tratamiento')
            ->orderByDesc('id_detalle')
            ->first();

        return $detalle?->tratamiento?->nombre;
    }
}