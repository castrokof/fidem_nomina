<?php

namespace App\Models\Paliativos;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObsPaliativos extends Model
{
    protected $table = 'obspaliativos';

    protected $fillable = [

            'observacion',
            'date_hospi',
            'type_obs',
            'subtype_obs',
            'future1',
            'future2',
            'future3',
            'pac_id',
            'user_id'

    ];

    public function pacid()
    {
        return $this->belongsTo(BasePaliativos::class, 'pac_id');
    }


    public function user_pacid()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
