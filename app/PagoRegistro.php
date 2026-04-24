<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PagoRegistro extends Model
{
    protected $table = 'pagos_registros';

    protected $fillable = [
        'factura_id', 'mes', 'anio', 'estado',
        'fecha_pago', 'monto_pagado', 'notas', 'notificacion_enviada',
    ];

    protected $casts = [
        'notificacion_enviada' => 'boolean',
        'fecha_pago'           => 'date',
        'monto_pagado'         => 'float',
    ];

    public function factura()
    {
        return $this->belongsTo(PagoFactura::class, 'factura_id');
    }

    /** Fecha exacta de vencimiento para este registro */
    public function fechaVencimiento(): Carbon
    {
        $diasEnMes = Carbon::create($this->anio, $this->mes, 1)->daysInMonth;
        $dia       = min($this->factura->dia_vencimiento, $diasEnMes);
        return Carbon::create($this->anio, $this->mes, $dia);
    }

    /** Estado calculado dinámicamente (sin tocar la BD) */
    public function estadoCalc(): string
    {
        if ($this->estado === 'pagado') return 'pagado';

        $hoy       = Carbon::today();
        $vence     = $this->fechaVencimiento();
        $diasAviso = $this->factura->dias_aviso ?? 3;

        if ($hoy->gt($vence)) return 'vencido';
        if ($hoy->gte($vence->copy()->subDays($diasAviso))) return 'proximo';
        return 'pendiente';
    }
}
