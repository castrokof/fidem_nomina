<?php

namespace App\Models\Nomina;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novedades extends Model
{
    protected $table = 'novedades';

    protected $fillable = [
        'road',
        'nove_observacion',
        'type_nove',
        'turn_night',
        'hours',
        'total_pac',
        'future3',
        'nove_id',
        'user_id'

        ];


        public function novedadnomina()
        {
            return $this->belongsTo(Hoursxuser::class, 'user_id');
        }

        public function useridnove()
    {
        return $this->belongsTo(Usuario::class, 'nove_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
