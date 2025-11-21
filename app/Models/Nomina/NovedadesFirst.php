<?php

namespace App\Models\Nomina;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovedadesFirst extends Model
{
    protected $table = 'novedadesfirts1';

    protected $fillable = [
        
            'papellido',
            'sapellido',
            'pname',
            'sname',
            'documento',
            'tipo_documento',
            'road_v',
            'value_ps',
            'value_ps_desc',
            'prestamo',
            'hours',
            'total_pac',
            'value_inc',
            'day_inc',
            'day_inicio',
            'day_fin',
            'nove_observacion',
            'user_id'
        
        ];


       

        public function useridnovefirst()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
