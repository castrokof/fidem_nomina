<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidacionDerecho extends Model
{
    protected $table = 'validaciones_derechos';

    protected $fillable = [
        'agenda_ci_id',
        'paciente_nombre',
        'paciente_cedula',
        'numero_factura',
        'atencion_factura',
        'contrato',
        'empresafac',
        'fecha_atencion',
        'cups_codigo',
        'cups_descripcion',
        'imagen_path',
        'observaciones',
        'created_by',
        'created_by_nombre',
        'ip_registro',
    ];

    protected $dates = ['fecha_atencion', 'created_at', 'updated_at'];

    public function agenda()
    {
        return $this->belongsTo(AgendaCI::class, 'agenda_ci_id');
    }

    public function usuario()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }
}
