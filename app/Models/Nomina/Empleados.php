<?php

namespace App\Models\nomina;
use DateTimeInterface;
use App\Models\Nomina\nominaliquid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    protected $table = 'empleados';
    
    protected $fillable = [
    'papellido',
    'sapellido',
    'pnombre',
    'snombre',
    'tipo_documento',
    'documento',
    'email',
    'celular',
    'observacion',
    'ips',
    'position',
    'eps',
    'arl',
    'afp',
    'fc',
    'salary',
    'salary_ps',
    'value_hour',
    'value_patient_attended',
    'value_add_security_social',
    'value_transporte',
    'value_salary_add',
    'name_bank',
    'account',
    'type_account',
    'type_contrat',
    'type_salary',
    'date_in',
    'date_out',
    'date_incontrat',
    'date_endcontrat',
    'activo',
    'user_id'
    ];

    public function liquidacion()
    {
        return $this->hasMany(nominaliquid::class, 'empleado_id');
    }

    public function contratos()
    {
        return $this->hasMany('App\Models\Models\Nomina\Contrato', 'empleadosc_id');
    }

    public function novedades()
    {
        return $this->hasMany(EmpleadosNovedades::class, 'empleado_id');
    }

    /**
     * Relación con el historial de salarios
     */
    public function salaryHistory()
    {
        return $this->hasMany(SalaryHistory::class, 'empleado_id')->ordenadoPorFecha();
    }

    /**
     * Obtiene el salario activo (vigente) del empleado
     */
    public function salarioActual()
    {
        return $this->hasOne(SalaryHistory::class, 'empleado_id')
                    ->where('activo', true)
                    ->latest('fecha_inicio');
    }

    /**
     * Obtiene el salario vigente en una fecha específica
     */
    public function salarioEnFecha($fecha)
    {
        return $this->salaryHistory()
                    ->enFecha($fecha)
                    ->first();
    }

    /**
     * Método auxiliar para obtener el salario fijo actual
     */
    public function getSalaryAttribute($value)
    {
        // Si existe historial de salarios, usar el salario activo
        $salarioActual = $this->salarioActual;
        if ($salarioActual) {
            return $salarioActual->salary;
        }

        // Si no, usar el valor de la tabla empleados (para compatibilidad con datos antiguos)
        return $value;
    }

    /**
     * Método auxiliar para obtener el salario prestación de servicios actual
     */
    public function getSalaryPsAttribute($value)
    {
        // Si existe historial de salarios, usar el salario activo
        $salarioActual = $this->salarioActual;
        if ($salarioActual) {
            return $salarioActual->salary_ps;
        }

        // Si no, usar el valor de la tabla empleados (para compatibilidad con datos antiguos)
        return $value;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


}
