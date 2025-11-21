<?php

namespace App\Models\Paliativos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cosultaspe extends Model
{
    protected $table = 'cosultaspes';

    protected $fillable = [

        'tipo_documento',
        'documento',
        'paciente',
        'dx_principal',
        'dx_relacionado',
        'paliativista1',
        'paliativista2',
        'paliativista3',
        'paliativista4',
        'paliativista5',
        'experto1',
        'experto2',
        'experto3',
        'experto4',
        'experto5'

    ];
    
    
    public function pacid()
    {
        return $this->belongsTo(BasePaliativos::class, 'documento');
    }
}
