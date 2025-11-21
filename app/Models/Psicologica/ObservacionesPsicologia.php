<?php

namespace App\Models\Psicologica;

use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservacionesPsicologia extends Model
{
    protected $table = 'obspsicologia';

    protected $fillable = [

            'addobservacion',
            'evo_id',
            'user_id'

    ];

    public function evoid()
    {
        return $this->belongsTo(LineaPsicologica::class, 'id');
    }


    public function user_evo()
    {
        return $this->belongsTo(Usuario::class, 'id');
    }
}
