<?php

namespace App\Models\Nomina;

use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Hoursxuser extends Model
{
    protected $table = 'hoursxuser';

    protected $fillable = [
        'date_turn',
        'date_hour_initial_turn',
        'date_hour_end_turn',
        'working_type',
        'hours',
        'observation',
        'supervisor',
        'quincena',
        'user_id'
        ];


        public function userid()
        {
            return $this->belongsTo(Usuario::class, 'id');
        }

        protected function serializeDate(DateTimeInterface $date)
        {
            return $date->format('Y-m-d H:i:s');
        }

}
