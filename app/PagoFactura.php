<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoFactura extends Model
{
    protected $table = 'pagos_facturas';

    protected $fillable = [
        'nombre', 'categoria', 'descripcion',
        'dia_vencimiento', 'monto_estimado',
        'correo_notificacion', 'dias_aviso',
        'activo', 'created_by',
    ];

    protected $casts = [
        'activo'          => 'boolean',
        'monto_estimado'  => 'float',
        'dia_vencimiento' => 'integer',
        'dias_aviso'      => 'integer',
    ];

    public function registros()
    {
        return $this->hasMany(PagoRegistro::class, 'factura_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(PagoNotificacion::class, 'factura_id');
    }

    public function registroDelMes(int $mes, int $anio)
    {
        return $this->registros()->where('mes', $mes)->where('anio', $anio)->first();
    }
}
