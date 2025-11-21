<?php

namespace App\Models\FidemContigo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Seguridad\Usuario;

class Seguimiento extends Model
{
     use HasFactory;

    protected $table = 'seguimientos';

    protected $fillable = [
        'fidemcontigos_id', 'evoluciones_id', 'todos_entregados',
        'observacion_general', 'estado_contacto', 'new_eva', 'priorizado', 'user_id', 
    ];

    public function fidemcontigo()
    {
        return $this->belongsTo(Fidemcontigo::class);
    }

    public function evolucion()
    {
        return $this->belongsTo(Evolucion::class);
    }
    
     public function user_seguimiento()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
    
      
    public function medicamentosegui()
    {
        return $this->hasMany(OrdenMedicamentoFiltrada::class, 'evoluciones_id', 'evoluciones_id');
    }
}
