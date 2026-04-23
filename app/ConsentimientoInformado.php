<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsentimientoInformado extends Model
{
    protected $table = 'consentimientos_informados';

    protected $fillable = [
        'agenda_ci_id',
        'paciente_id',
        'paciente_nombre',
        'paciente_cedula',
        'paciente_tipo_doc',
        'paciente_edad',
        'paciente_genero',
        'paciente_fecha_nacimiento',
        'desea_ser_informado',
        'profesional_id',
        'profesional_nombre',
        'especialidad_id',
        'plantilla_id',
        'cups_codigo',
        'cups_descripcion',
        'fecha_procedimiento',
        'estado',
        'requiere_acudiente',
        'pdf_path',
        'token_firma',
        'token_expira_at',
        'ip_generacion',
        'created_by',
        'created_by_nombre',
    ];

    protected $casts = [
        'fecha_procedimiento' => 'date',
        'paciente_fecha_nacimiento' => 'date',
        'token_expira_at' => 'datetime',
        'requiere_acudiente' => 'boolean',
        'desea_ser_informado' => 'boolean'
    ];

    /**
     * Relación con agenda
     */
    public function agenda()
    {
        return $this->belongsTo(AgendaCI::class, 'agenda_ci_id');
    }

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
     * Relación con especialidad
     */
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    /**
     * Relación con plantilla
     */
    public function plantilla()
    {
        return $this->belongsTo(PlantillaCI::class, 'plantilla_id');
    }

    /**
     * Relación con todas las firmas
     */
    public function firmas()
    {
        return $this->hasMany(FirmaCI::class, 'consentimiento_id');
    }

    /**
     * Relación con acudiente
     */
    public function acudiente()
    {
        return $this->hasOne(AcudienteCI::class, 'consentimiento_id');
    }

    /**
     * Relación con firma del paciente
     */
    public function firmaPaciente()
    {
        return $this->hasOne(FirmaCI::class, 'consentimiento_id')
                    ->where('tipo_firmante', 'paciente');
    }

    /**
     * Relación con firma del acudiente
     */
    public function firmaAcudiente()
    {
        return $this->hasOne(FirmaCI::class, 'consentimiento_id')
                    ->where('tipo_firmante', 'acudiente');
    }

    /**
     * Relación con firma del profesional
     */
    public function firmaProfesional()
    {
        return $this->hasOne(FirmaCI::class, 'consentimiento_id')
                    ->where('tipo_firmante', 'profesional');
    }

    /**
     * Verifica si el token es válido
     */
    public function tokenEsValido()
    {
        return $this->token_expira_at && $this->token_expira_at->isFuture();
    }

    /**
     * Verifica si el consentimiento está completo (todas las firmas requeridas)
     */
    public function estaCompleto()
    {
        $tieneFirmaPaciente = $this->firmaPaciente()->exists();
        $tieneFirmaProfesional = $this->firmaProfesional()->exists();
        $tieneFirmaAcudiente = $this->firmaAcudiente()->exists();

        return $tieneFirmaPaciente
            && $tieneFirmaProfesional
            && (!$this->requiere_acudiente || $tieneFirmaAcudiente);
    }

    /**
     * Obtiene array de firmas faltantes
     */
    public function firmasFaltantes()
    {
        $faltantes = [];

        if (!$this->firmaPaciente()->exists()) {
            $faltantes[] = 'paciente';
        }

        if ($this->requiere_acudiente && !$this->firmaAcudiente()->exists()) {
            $faltantes[] = 'acudiente';
        }

        if (!$this->firmaProfesional()->exists()) {
            $faltantes[] = 'profesional';
        }

        return $faltantes;
    }

    /**
     * Contador de firmas en formato "X/Y"
     */
    public function contadorFirmas()
    {
        $total = $this->requiere_acudiente ? 3 : 2;
        $firmadas = $this->firmas()->count();

        return "$firmadas/$total";
    }

    /**
     * Scope para consentimientos pendientes
     */
    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para consentimientos en proceso
     */
    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'en_proceso');
    }

    /**
     * Scope para consentimientos firmados
     */
    public function scopeFirmado($query)
    {
        return $query->where('estado', 'firmado');
    }
}
