<?php

namespace App\Models\Psicologica;

use Illuminate\Database\Eloquent\Model;

class LineaPsicologica extends Model
{
    protected $table = 'psicologica';

    protected $fillable = [

            'surname',
            'ssurname',
            'fname',
            'sname',
            'type_document',
            'document',
            'date_birth',
            'municipality',
            'other',
            'address',
            'celular',
            'phone',
            'email',
            'sex',
            'eapb',
            'user_id',
            'reason_consultation',
            'consultation',
            'diagnosis',
            'future1',
            'future2',
            'future3',
            'future4',
            'future5'
    ];


    public function userid()
    {
        return $this->belongsTo(Usuario::class, 'id');
    }

    public function observacionadd(){
        return $this->hasMany(ObservacionesPsicologia::class, 'evo_id');
    }

}
