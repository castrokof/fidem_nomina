<?php

namespace App\Models\EncuestaFisiatria;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservacionesFisiatria extends Model
{
        use HasFactory;

    protected $table = 'observacion_encuestas_fisiatria';

    protected $fillable = [
        'addobservacion',
        'enc_id',
        'user_id'
    ];

    // Relación con encuesta
    public function encuesta()
    {
        return $this->belongsTo(EncuestasFisiatria::class, 'enc_id');
    }
    
   

    public function user_evo()
    {
        return $this->belongsTo(Usuario::class, 'id');
    }
}
