<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MedicamentoControladoMovimiento extends Model
{
    protected $table = 'medicamentos_controlados_movimientos';

    protected $fillable = [
        'medicamento_controlado_id',
        'fecha',
        'tipo_movimiento',
        'proveedor',
        'numero_factura',
        'nombre_paciente',
        'cedula_paciente',
        'numero_formula_control',
        'foto_formula',
        'entrada',
        'salida',
        'saldo',
        'user_id'
    ];

    /**
     * Relación con medicamento controlado
     */
    public function medicamentoControlado()
    {
        return $this->belongsTo('App\Models\Admin\MedicamentoControlado', 'medicamento_controlado_id');
    }

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo('App\Models\Seguridad\Usuario', 'user_id');
    }

    /**
     * Calcular el saldo después del movimiento
     */
    public function calcularSaldo()
    {
        // Obtener el saldo anterior (último movimiento anterior a este)
        $movimientoAnterior = self::where('medicamento_controlado_id', $this->medicamento_controlado_id)
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();

        $saldoAnterior = $movimientoAnterior ? $movimientoAnterior->saldo : 0;

        // Calcular nuevo saldo
        if ($this->tipo_movimiento == 'entrada') {
            return $saldoAnterior + $this->entrada;
        } else {
            return $saldoAnterior - $this->salida;
        }
    }

    /**
     * Serialización de fechas
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
