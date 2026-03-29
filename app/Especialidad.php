<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /**
     * Relación muchos a muchos con plantillas
     */
    public function plantillas()
    {
        return $this->belongsToMany(
            PlantillaCI::class,
            'especialidad_plantilla_ci',
            'especialidad_id',
            'plantilla_ci_id'
        )->withTimestamps();
    }

    /**
     * Relación con profesionales
     */
    public function profesionales()
    {
        return $this->hasMany(Profesional::class, 'especialidad_id');
    }

    /**
     * Scope para filtrar solo especialidades activas
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
