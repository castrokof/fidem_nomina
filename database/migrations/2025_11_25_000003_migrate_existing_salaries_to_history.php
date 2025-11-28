<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MigrateExistingSalariesToHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Migrar los salarios existentes de la tabla empleados a salary_history
        $empleados = DB::table('empleados')
            ->where('activo', 'S')
            ->whereNotNull('date_in')
            ->get();

        foreach ($empleados as $empleado) {
            // Solo crear el registro si al menos uno de los salarios tiene valor
            if ($empleado->salary || $empleado->salary_ps) {
                // Usar la fecha de ingreso del contrato o la fecha de creación del registro
                $fechaInicio = $empleado->date_incontrat ?? $empleado->date_in ?? $empleado->created_at;

                DB::table('salary_history')->insert([
                    'empleado_id' => $empleado->id,
                    'salary' => $empleado->salary,
                    'salary_ps' => $empleado->salary_ps,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => null,
                    'created_by' => $empleado->user_id,
                    'motivo' => 'Migración de datos existentes',
                    'activo' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar los registros migrados (aquellos con motivo de migración)
        DB::table('salary_history')
            ->where('motivo', 'Migración de datos existentes')
            ->delete();
    }
}
