<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profesional extends Model
{
    protected $table = 'profesionales';

    protected $fillable = [
        'usuario_id',
        'especialidad_id',
        'codigo_usuario',
        'nombres',
        'apellidos',
        'tipo_documento',
        'numero_documento',
        'registro_medico',
        'tarjeta_profesional',
        'telefono',
        'email',
        'firma_base64',
        'firma_actualizada_at',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'firma_actualizada_at' => 'datetime'
    ];

    /**
     * Relación con la tabla de usuarios (para login)
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Usuarios::class, 'usuario_id');
    }

    /**
     * Relación con especialidad
     */
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    /**
     * Relación con agendas
     */
    public function agendas()
    {
        return $this->hasMany(AgendaCI::class, 'profesional_id');
    }

    /**
     * Relación con consentimientos
     */
    public function consentimientos()
    {
        return $this->hasMany(ConsentimientoInformado::class, 'profesional_id');
    }

    /**
     * Verifica si el profesional tiene firma registrada
     */
    public function tieneFirmaRegistrada()
    {
        return !empty($this->firma_base64);
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    /**
     * Plantillas disponibles según la especialidad del profesional
     */
    public function plantillasDisponibles()
    {
        if ($this->especialidad_id) {
            return PlantillaCI::activo()
                ->where(function($q) {
                    $q->where('uso_general', true)
                      ->orWhereHas('especialidades', function($q2) {
                          $q2->where('especialidades.id', $this->especialidad_id);
                      });
                })
                ->orderBy('nombre')
                ->get();
        }

        return PlantillaCI::activo()
            ->where('uso_general', true)
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Scope para profesionales activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para buscar por código de usuario
     */
    public function scopeByCodigo($query, $codigo)
    {
        return $query->where('codigo_usuario', trim($codigo));
    }
}
