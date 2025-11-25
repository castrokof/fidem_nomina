<?php

namespace App\Models\nomina;
use App\Models\Seguridad\Usuario;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadosNovedades extends Model
{
    use HasFactory;

    protected $table = 'empleados_novedades';

    protected $fillable = [
        'tipo_novedad',
        'fecha_inicio',
        'fecha_fin',
        'dias',
        'valor',
        'observacion',
        'documento_soporte',
        'estado',
        'empleado_id',
        'user_id'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleado_id');
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
