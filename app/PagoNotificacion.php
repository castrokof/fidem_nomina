<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoNotificacion extends Model
{
    protected $table = 'pagos_notificaciones';

    protected $fillable = [
        'factura_id', 'registro_id',
        'titulo', 'mensaje', 'tipo', 'leido',
    ];

    protected $casts = ['leido' => 'boolean'];

    public function factura()
    {
        return $this->belongsTo(PagoFactura::class, 'factura_id');
    }
}
