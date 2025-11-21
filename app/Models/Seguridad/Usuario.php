<?php

namespace App\Models\Seguridad;

use App\Models\Admin\Cita;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Admin\Rol;
use App\Models\Listas\Listas;
use App\Models\Listas\ListasDetalle;
use App\Models\Nomina\Hoursxuser;
use App\Models\Nomina\Liquidationxuser;
use App\Models\Nomina\nominaliquid;
use App\Models\Nomina\Novedades;
use App\Models\Nomina\Position;
use App\Models\Paliativos\BasePaliativos;
use App\Models\Paliativos\ObsPaliativos;
use App\Models\Psicologica\LineaPsicologica;
use App\Models\Psicologica\ObservacionesPsicologia;
use DateTimeInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';


    protected $fillable = [
        'papellido',
        'sapellido',
        'pnombre',
        'snombre',
        'tipo_documento',
        'documento',
        'usuario',
        'password',
        'remenber_token',
        'email',
        'celular',
        'observacion',
        'ips',
        'activo'

    ];




    protected $hidden = ['password', 'remenber_token'];


    public function ListasGeneral(){
        return $this->hasMany(Listas::class, 'user_id');

    }

    public function ListasDetalle(){
        return $this->hasMany(ListasDetalle::class, 'user_id');

    }

    public function novedades(){
        return $this->hasMany(Novedades::class, 'user_id');

    }


    public function obs_palia(){
        return $this->hasMany(ObsPaliativos::class, 'user_id');

    }


    public function base_palia(){
        return $this->hasMany(BasePaliativos::class, 'user_id');

    }
    

    
     public function segui_fidemcontigo(){
        return $this->hasMany(Seguimiento::class, 'user_id');

    } 


    public function hours(){
        return $this->hasMany(nominaliquid::class, 'user_id');
    }

    public function hoursliquidation(){
        return $this->hasMany(Liquidationxuser::class, 'user_id');
    }


    public function psicologica(){
        return $this->hasMany(LineaPsicologica::class, 'user_id');
    }

    public function obspsicologica(){
        return $this->hasMany(ObservacionesPsicologia::class, 'user_id');
    }



    public function roles1(){
        return $this->belongsToMany(Rol::class,'usuario_rol');
    }

public function setSession(){

    $roles1 = $this->roles1()->get()->toArray();

        if (count($roles1) == 1) {
            Session::put(
                [
                    'rol_id' => $roles1[0]['id'],
                    'rol_nombre' => $roles1[0]['nombre'],
                    'usuario' => $this->usuario,
                    'usuario_id' => $this->id,
                    'profesion' => $this->profesion,
                    'especialidad' => $this->especialidad,
                    'email' => $this->email,
                    'activo'=>$this->activo,
                    'ips'=>$this->ips
                ]
                );
        }

    }
    public function setPasswordAttribute($value)
    {
        if ( !empty ($value))
        {
            $this->attributes['password'] = Hash::make($value);
            $this->attributes['remenber_token'] = Hash::make($value);
        }
    }

    protected function serializeDate(DateTimeInterface $date)
{
    return $date->format('Y-m-d H:i:s');
}


}
