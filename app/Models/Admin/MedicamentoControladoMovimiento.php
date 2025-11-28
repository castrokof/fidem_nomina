<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MedicamentoControladoMovimiento extends Model
{
    protected $table = 'medicamentos_controlados_movimientos';

    protected $fillable = [
        'medicamento_controlado_id',
        'lote_entrada_id',
        'fecha',
        'tipo_movimiento',
        'proveedor',
        'numero_factura',
        'fecha_vencimiento',
        'registro_invima',
        'lote',
        'observaciones',
        'nombre_paciente',
        'cedula_paciente',
        'numero_formula_control',
        'foto_formula',
        'entrada',
        'salida',
        'saldo',
        'user_id',
        'anulado',
        'anulado_por_movimiento_id',
        'anulado_por_user_id',
        'anulado_at',
        'motivo_anulacion'
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
     * Relación con el lote de entrada usado en esta salida
     */
    public function loteEntrada()
    {
        return $this->belongsTo('App\Models\Admin\MedicamentoControladoMovimiento', 'lote_entrada_id');
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
     * Relación con el movimiento que anuló este movimiento
     */
    public function movimientoAnulador()
    {
        return $this->belongsTo('App\Models\Admin\MedicamentoControladoMovimiento', 'anulado_por_movimiento_id');
    }

    /**
     * Relación con el usuario que anuló este movimiento
     */
    public function usuarioAnulador()
    {
        return $this->belongsTo('App\Models\Seguridad\Usuario', 'anulado_por_user_id');
    }

    /**
     * Verificar si el movimiento está anulado
     */
    public function estaAnulado()
    {
        return $this->anulado == true;
    }

    /**
     * Serialización de fechas
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
