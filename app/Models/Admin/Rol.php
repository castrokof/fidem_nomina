<?php

namespace App\Models\Admin;

use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $fillable = ['nombre'];
    protected $guarded = ['id'];




    public function user1(){

        return $this->belongsToMany(Usuario::class,'rol_id');
        }


}

