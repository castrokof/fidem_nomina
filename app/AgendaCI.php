<?php
// app/AgendaCI.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgendaCI extends Model
{
    protected $table = 'agenda_ci';
    
    protected $fillable = [
        // Campos originales
        'id_registro',
        'fecha',
        'codigo_consultorio',
        'historia',
        'paciente_id',
        'paciente_nombre',
        'paciente_cedula',
        'paciente_tipo_doc',
        'paciente_telefono',
        'profesional_id',
        'codigo_usuario',
        'cups_codigo',
        'contrato',
        'empresafac',
        'llegada_confirmada',
        'numero_factura',
        'atencion_factura',
        'sincronizado_at',
        
        // ✅ NUEVOS: Todos los campos adicionales
        'orden',
        'fecha_solicitud',
        'fecha_solicitada',
        'tipo_solicitud',
        'ips',
        'centroprod',
        'tipdocum',
        'numdocum',
        'nombre1',
        'nombre2',
        'apellido1',
        'apellido2',
        'nuevo',
        'estado',
        'atendido',
        'observaciones',
        'usuario_externo',
        'ips_factura',
        'documento_factura',
        'px_factura',
        'cupo_web',
        'cups_descripcion',
        'ips_internacion',
        'documento_internacion',
        'orden_internacion',
        'atencion_internacion',
        'px_internacion',
        'embarazo',
        'regimenfac',
        'nivelfac',
        'tipoafilfac',
    ];
    
    protected $casts = [
        'fecha'                => 'datetime',
        'fecha_solicitud'      => 'datetime',
        'fecha_solicitada'     => 'datetime',
        'atencion_factura'     => 'datetime',
        'atencion_internacion' => 'datetime',
        'llegada_confirmada'   => 'boolean',
        'sincronizado_at'      => 'datetime',
    ];
    
    // Relationships
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function profesionalPorCodigo()
    {
        return $this->belongsTo(
            Profesional::class, 
            'codigo_consultorio',  // FK en agenda_ci
            'codigo_usuario'        // PK en profesionales
        );
    }

    
    public function profesional()
    {
        return $this->belongsTo(Profesional::class, 'profesional_id');
    }
    
    public function consentimientos()
    {
        return $this->hasMany(ConsentimientoInformado::class, 'agenda_ci_id');
    }
}