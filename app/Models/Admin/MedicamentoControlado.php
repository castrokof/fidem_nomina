<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MedicamentoControlado extends Model
{
    protected $table = 'medicamentos_controlados';

    protected $fillable = [
        'nombre',
        'descripcion',
        'saldo_actual',
        'activo'
    ];

    /**
     * Relación con movimientos
     */
    public function movimientos()
    {
        return $this->hasMany('App\Models\Admin\MedicamentoControladoMovimiento', 'medicamento_controlado_id');
    }

    /**
     * Obtener el último saldo del medicamento
     */
    public function ultimoSaldo()
    {
        $ultimoMovimiento = $this->movimientos()->orderBy('id', 'desc')->first();
        return $ultimoMovimiento ? $ultimoMovimiento->saldo : 0;
    }

    /**
     * Actualizar saldo actual del medicamento
     */
    public function actualizarSaldo()
    {
        $this->saldo_actual = $this->ultimoSaldo();
        $this->save();
    }

    /**
     * Serialización de fechas
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
