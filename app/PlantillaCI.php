<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantillaCI extends Model
{
    protected $table = 'plantillas_ci';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cups_codigo',
        'contenido_html',
        'variables_disponibles',
        'activo',
        'uso_general'
    ];

    protected $casts = [
        'variables_disponibles' => 'array',
        'activo' => 'boolean',
        'uso_general' => 'boolean'
    ];

    /**
     * Relación muchos a muchos con especialidades
     */
    public function especialidades()
    {
        return $this->belongsToMany(
            Especialidad::class,
            'especialidad_plantilla_ci',
            'plantilla_ci_id',
            'especialidad_id'
        )->withTimestamps();
    }

    /**
     * Scope para plantillas activas
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Renderiza la plantilla reemplazando las variables
     */
    public function renderizar(array $variables)
    {
        $contenido = $this->contenido_html;

        foreach ($variables as $clave => $valor) {
            $contenido = str_replace('{{' . $clave . '}}', $valor ?? '', $contenido);
        }

        return $contenido;
    }

    /**
     * Variables disponibles para usar en las plantillas
     */
    public static function variablesDisponibles()
    {
        return [
            '{{paciente_nombre}}'     => 'Nombre completo del paciente',
            '{{paciente_cedula}}'     => 'Número de documento',
            '{{paciente_tipo_doc}}'   => 'Tipo de documento (CC, TI, CE...)',
            '{{paciente_edad}}'       => 'Edad del paciente',
            '{{paciente_genero}}'     => 'Género del paciente',
            '{{profesional_nombre}}'  => 'Nombre del profesional de salud',
            '{{registro_medico}}'     => 'Número de registro médico',
            '{{tarjeta_profesional}}' => 'Número de tarjeta profesional',
            '{{especialidad}}'        => 'Especialidad del profesional',
            '{{fecha_procedimiento}}' => 'Fecha del procedimiento',
            '{{cups_codigo}}'         => 'Código CUPS',
            '{{cups_descripcion}}'    => 'Descripción del procedimiento',
            '{{clinica_nombre}}'      => 'Nombre de la clínica',
            '{{clinica_direccion}}'   => 'Dirección de la clínica',
            '{{fecha_actual}}'        => 'Fecha de generación del documento',
        ];
    }
}
