<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'tipo',
        'descripcion'
    ];

    /**
     * Obtener el valor de una configuración por su clave
     */
    public static function obtener($clave, $valorPorDefecto = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $valorPorDefecto;
    }

    /**
     * Establecer el valor de una configuración
     */
    public static function establecer($clave, $valor, $tipo = 'texto', $descripcion = null)
    {
        return self::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'tipo' => $tipo,
                'descripcion' => $descripcion
            ]
        );
    }

    /**
     * Scope para configuraciones de tipo imagen
     */
    public function scopeTipoImagen($query)
    {
        return $query->where('tipo', 'imagen');
    }
}
