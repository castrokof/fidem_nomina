<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'edad',
        'genero',
        'telefono',
        'historia_clinica',
        'email'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    /**
     * Relación con consentimientos
     */
    public function consentimientos()
    {
        return $this->hasMany(ConsentimientoInformado::class, 'paciente_id');
    }

    /**
     * Relación con agendas
     */
    public function agendas()
    {
        return $this->hasMany(AgendaCI::class, 'paciente_id');
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    /**
     * Busca o crea un paciente por tipo y número de documento
     */
    public static function buscarOCrear(array $datos)
    {
        return static::firstOrCreate(
            [
                'tipo_documento' => $datos['tipo_documento'],
                'numero_documento' => $datos['numero_documento']
            ],
            $datos
        );
    }

    /**
     * Scope para buscar por documento
     */
    public function scopeByDocumento($query, $tipo, $numero)
    {
        return $query->where('tipo_documento', $tipo)
                    ->where('numero_documento', $numero);
    }
}
