<?php

namespace App\Models\Nomina;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NominaNovedadesAplicadas extends Model
{
    use HasFactory;

    protected $table = 'nomina_novedades_aplicadas';

    protected $fillable = [
        'nominaliquid_id',
        'empleado_novedad_id',
        'tipo_novedad',
        'fecha_inicio',
        'fecha_fin',
        'dias_aplicados',
        'valor_aplicado',
        'tipo_afectacion',
        'observacion'
    ];

    /**
     * Relación con nominaliquid
     */
    public function nominaliquid()
    {
        return $this->belongsTo(nominaliquid::class, 'nominaliquid_id');
    }

    /**
     * Relación con empleado_novedad
     */
    public function empleadoNovedad()
    {
        return $this->belongsTo(EmpleadosNovedades::class, 'empleado_novedad_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
