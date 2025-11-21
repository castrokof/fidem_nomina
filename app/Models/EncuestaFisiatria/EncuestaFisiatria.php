<?php

namespace App\Models\EncuestaFisiatria;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Seguridad\Usuario;
use App\Models\EncuestaFisiatria\ObservacionesFisiatria;

class EncuestaFisiatria extends Model
{
    use HasFactory;

    protected $table = 'encuestasfisiatria';

    protected $fillable = [
        'surname',
        'ssurname',
        'fname',
        'sname',
        'type_document',
        'document',
        'eapb',
        'fecha_solicitud',
        'profesional',
        'dx',
        'dispositivo_silla',
        'dispositivo_apoyo',
        'other',
        'solicitud_dispositivo',
        'antecedentes_dx_cancer',
        'antecedentes_toxina_espasticidad',
        'camilla_ambulancia',
        'tipo_solicitud',
        'observacion',
        'reason_consultation',
        'user_id',
        'future3',
        'future5',
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
    
     public function observacionadd(){
        return $this->hasMany(ObservacionesFisiatria::class, 'enc_id');
    }
}
