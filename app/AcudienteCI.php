<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcudienteCI extends Model
{
    protected $table = 'acudientes_ci';

    protected $fillable = [
        'consentimiento_id',
        'nombre_completo',
        'cedula',
        'relacion_con_paciente',
        'telefono'
    ];

    /**
     * Relación con consentimiento
     */
    public function consentimiento()
    {
        return $this->belongsTo(ConsentimientoInformado::class, 'consentimiento_id');
    }
}
