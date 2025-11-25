<?php

namespace App\Models\Models\Nomina;

use App\Models\Nomina\Empleados;
use App\Models\Seguridad\Usuario;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'ips',
        'type_contrat',
        'day_inicio',
        'day_fin',
        'value_ps',
        'value_ps_desc',
        'road_v',
        'hours',
        'pac',
        'contrat_observacion',
        'photo_base64',
        'empleadosc_id',
        'user_id'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleadosc_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
