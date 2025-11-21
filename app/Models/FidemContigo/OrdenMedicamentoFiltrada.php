<?php

namespace App\Models\FidemContigo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMedicamentoFiltrada extends Model
{
    use HasFactory;

    protected $table = 'ordenes_medicamentos_filtradas';

    protected $fillable = [
        'evoluciones_id', 'causa', 'codigo', 'presentacion',
        'nombre', 'cantidad', 'administracion', 'dosis_cant',
        'dosis_freq', 'dosis_hora', 'numero_dosis', 'posologia',
        'observaciones', 'entregado', 'observacion_entrega'
    ];

    public function evolucion()
    {
        return $this->belongsTo(Evolucion::class);
    }
  
}
