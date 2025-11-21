<?php

namespace App\Models\FidemContigo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingresoegreso extends Model
{
     use HasFactory;

    protected $table = 'ingresoegresos';

    protected $fillable = [
        'fidemcontigos_id', 'tipo', 'fecha', 'observaciones'
    ];

    public function fidemcontigo()
    {
        return $this->belongsTo(Fidemcontigo::class);
    }
}
