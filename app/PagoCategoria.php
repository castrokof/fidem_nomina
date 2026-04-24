<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoCategoria extends Model
{
    protected $table    = 'pagos_categorias';
    protected $fillable = ['nombre'];

    public function facturas()
    {
        return $this->hasMany(PagoFactura::class, 'categoria', 'nombre');
    }
}
