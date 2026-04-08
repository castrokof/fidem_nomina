<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportacionPlantillaCI extends Model
{
    protected $table = 'importacion_plantillas_ci';

    protected $fillable = [
        'nombre',
        'especialidades',
        'cups_codigo',
        'uso_general',
        'contenido_texto',
        'contenido_html',
        'estado',
        'error_mensaje',
        'codigo_calidad',
        'version_calidad',
        'fecha_calidad'
    ];

    protected $casts = [
        'uso_general' => 'boolean'
    ];

    /**
     * Scope para importaciones pendientes
     */
    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para importaciones procesadas
     */
    public function scopeProcesado($query)
    {
        return $query->where('estado', 'procesado');
    }

    /**
     * Scope para importaciones con error
     */
    public function scopeError($query)
    {
        return $query->where('estado', 'error');
    }

    /**
     * Marca la importación como procesada
     */
    public function marcarProcesado()
    {
        $this->update([
            'estado' => 'procesado',
            'error_mensaje' => null
        ]);
    }

    /**
     * Marca la importación con error
     */
    public function marcarError($mensaje)
    {
        $this->update([
            'estado' => 'error',
            'error_mensaje' => $mensaje
        ]);
    }
}
