<?php

namespace App\Models\Paliativos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    protected $table = 'estados';

    protected $fillable = [

        'tipo_documento',
        'documento',
        'paciente',
        'estado_pac',
        'id_estado',
        'dx_principal',
        'dx_relacionado'

    ];
}
