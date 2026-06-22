<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $connection = 'oracle';
    protected $table = 'folder';
    protected $primaryKey = 'id_folder';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'codigo_archivo',
        'estante',
        'seccion',
        'observaciones',
    ];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'id_folder', 'id_folder');
    }
}