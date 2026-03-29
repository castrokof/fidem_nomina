<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgendaCI extends Model
{
    protected $table = 'agenda_ci';

    protected $fillable = [
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
        'sincronizado_at'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'atencion_factura' => 'datetime',
        'sincronizado_at' => 'datetime',
        'llegada_confirmada' => 'boolean'
    ];

    /**
     * Relación con paciente
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Relación con profesional
     */
    public function profesional()
    {
        return $this->belongsTo(Profesional::class, 'profesional_id');
    }

    /**
     * Relación con consentimientos
     */
    public function consentimientos()
    {
        return $this->hasMany(ConsentimientoInformado::class, 'agenda_ci_id');
    }

    /**
     * Scope para citas pendientes (sin llegada confirmada)
     */
    public function scopePendientes($query)
    {
        return $query->where('llegada_confirmada', false);
    }

    /**
     * Scope para citas confirmadas
     */
    public function scopeConfirmadas($query)
    {
        return $query->where('llegada_confirmada', true);
    }

    /**
     * Scope para citas de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    /**
     * Scope para citas por rango de fechas
     */
    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha', [$desde, $hasta]);
    }
}
