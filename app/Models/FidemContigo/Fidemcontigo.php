<?php

namespace App\Models\FidemContigo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fidemcontigo extends Model
{
     use HasFactory;

    protected $table = 'fidemcontigos';

    protected $fillable = [
        'tipdocum', 'numdocum', 'numhistoria', 'apellido1', 'apellido2',
        'nombre1', 'nombre2', 'entidad_salud', 'telefono', 'telefono_avi',
        'telefono_residencia', 'telefono_movil', 'estado',
        'fecha_ultima_evolucion', 'eva', 'tipo_evolucion',
    ];

    public function evoluciones()
    {
        return $this->hasMany(Evolucion::class, 'fidemcontigos_id')->orderBy('fechahora_evolucion', 'desc');
    }

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class, 'fidemcontigos_id');
    }

    public function ingresoegresos()
    {
        return $this->hasMany(Ingresoegreso::class);
    }
    
    public function ultimaEvolucion()
{
    return $this->hasOne(Evolucion::class, 'fidemcontigos_id')->orderBy('fechahora_evolucion', 'desc');
}
}
