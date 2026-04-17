<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirmaCI extends Model
{
    protected $table = 'firmas_ci';

    protected $fillable = [
        'consentimiento_id',
        'tipo_firmante',
        'firma_base64',
        'foto_base64',
        'no_sabe_firmar',
        'firmante_nombre',
        'firmante_cedula',
        'firmante_edad',
        'firmante_genero',
        'firmante_relacion',
        'ip_firma',
        'user_agent',
        'firmado_at'
    ];

    protected $casts = [
        'firmado_at' => 'datetime'
    ];

    /**
     * Relación con consentimiento
     */
    public function consentimiento()
    {
        return $this->belongsTo(ConsentimientoInformado::class, 'consentimiento_id');
    }

    /**
     * Scope para firmas de paciente
     */
    public function scopePaciente($query)
    {
        return $query->where('tipo_firmante', 'paciente');
    }

    /**
     * Scope para firmas de acudiente
     */
    public function scopeAcudiente($query)
    {
        return $query->where('tipo_firmante', 'acudiente');
    }

    /**
     * Scope para firmas de profesional
     */
    public function scopeProfesional($query)
    {
        return $query->where('tipo_firmante', 'profesional');
    }
}
