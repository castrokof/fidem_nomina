<?php

namespace App\Models\Nomina;

use App\Models\Seguridad\Usuario;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    protected $table = 'salary_history';

    protected $fillable = [
        'empleado_id',
        'salary',
        'salary_ps',
        'fecha_inicio',
        'fecha_fin',
        'created_by',
        'motivo',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    /**
     * Relación con el empleado
     */
    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleado_id');
    }

    /**
     * Relación con el usuario que creó el registro
     */
    public function createdBy()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    /**
     * Scope para obtener el salario activo (vigente)
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener salarios en una fecha específica
     */
    public function scopeEnFecha($query, $fecha)
    {
        return $query->where('fecha_inicio', '<=', $fecha)
                     ->where(function($q) use ($fecha) {
                         $q->whereNull('fecha_fin')
                           ->orWhere('fecha_fin', '>=', $fecha);
                     });
    }

    /**
     * Scope para obtener el historial ordenado por fecha
     */
    public function scopeOrdenadoPorFecha($query)
    {
        return $query->orderBy('fecha_inicio', 'desc');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
