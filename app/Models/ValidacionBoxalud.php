<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidacionBoxalud extends Model
{
    protected $table = 'validaciones_boxalud';

    protected $fillable = [
        // Identificación
        'tipo_documento',
        'numero_documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'tipo_afiliado',
        // Plan y estados
        'plan',
        'vigencia',
        'estado_pagos',
        'estado_documentos',
        // Datos biológicos
        'sexo_biologico',
        'sexo_identificacion',
        'rango_salarial',
        // Origen
        'nacionalidad',
        'pais_nacimiento',
        'departamento_nacimiento',
        'municipio_nacimiento',
        // Atención
        'departamento_atencion',
        'municipio_atencion',
        'localidad',
        'barrio',
        'direccion',
        'telefono',
        'celular',
        'correo_electronico',
        'fecha_inicio_atencion',
        'fecha_fin_atencion',
        // IPS
        'ips_nombre_oferta',
        'ips_codigo',
        'ips_sede',
        'ips_servicio',
        // Trazabilidad
        'screenshot_path',
        'datos_raw',
        'url_consultada',
        'ip_origen',
        'user_agent',
        'user_id',
        'fecha_consulta',
    ];

    // Fechas que Carbon maneja automáticamente
    protected $dates = [
        'fecha_consulta',
        'fecha_nacimiento',
        'fecha_inicio_atencion',
        'fecha_fin_atencion',
        'created_at',
        'updated_at',
    ];

    // ── Accessors ────────────────────────────────────────────────────────────

    // datos_raw como array (Laravel 5.1 no tiene cast json nativo)
    public function getDatosRawAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    // Nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim(implode(' ', array_filter([
            $this->primer_nombre,
            $this->segundo_nombre,
            $this->primer_apellido,
            $this->segundo_apellido,
        ])));
    }

    // URL pública del screenshot para descarga
    public function getScreenshotUrlAttribute()
    {
        if (!$this->screenshot_path) return null;
        return url("api/validaciones-boxalud/{$this->id}/screenshot");
    }

    // ── Relaciones ───────────────────────────────────────────────────────────

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Seguridad\Usuario::class, 'user_id');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    // Consultas de hoy
    public function scopeHoy($query)
    {
        return $query->whereRaw('DATE(fecha_consulta) = CURDATE()');
    }

    // Consultas de un documento específico
    public function scopeDocumento($query, $documento)
    {
        return $query->where('numero_documento', $documento);
    }

    // Solo vigentes
    public function scopeVigentes($query)
    {
        return $query->where('vigencia', 'Vigente');
    }
}
