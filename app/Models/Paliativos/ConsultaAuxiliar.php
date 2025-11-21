<?php

namespace App\Models\Paliativos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultaAuxiliar extends Model
{
    protected $table = 'consulta_auxiliars';

    protected $fillable = [

        'tipo_documento',
        'documento',
        'paciente',
        'dx_principal',
        'dx_relacionado',
        'auxiliar1',
        'auxiliar2',
        'auxiliar3',
        'auxiliar4',
        'auxiliar5',
        'auxiliar6',
        'auxiliar7',
        'auxiliar8',
        'auxiliar9',
        'auxiliar10'

    ];
}
