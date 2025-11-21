<?php

namespace App\Models\FidemContigo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evolucion extends Model
{
     use HasFactory;

    protected $table = 'evoluciones';

    protected $fillable = [
        'fidemcontigos_id', 'id_evolucion', 'fechahora_apertura',
        'fechahora_evolucion', 'cuestionario', 'respuesta',
        'codigo_profesional', 'dx_principal', 'dx_secondary', 'tipo_evolucion'
    ];

    public function fidemcontigo()
    {
        return $this->belongsTo(Fidemcontigo::class);
    }

    public function medicamentos()
    {
        return $this->hasMany(OrdenMedicamentoFiltrada::class, 'evoluciones_id');
    }

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class);
    }
}
