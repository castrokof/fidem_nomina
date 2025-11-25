<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNovedadFieldsToEmpleadosNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados_novedades', function (Blueprint $table) {
            // Verificar si las columnas no existen antes de agregarlas
            if (!Schema::hasColumn('empleados_novedades', 'tipo_novedad')) {
                $table->string('tipo_novedad', 50)->after('id')->comment('incapacidad, licencia, vacaciones, suspension, permiso, otro');
            }
            if (!Schema::hasColumn('empleados_novedades', 'fecha_inicio')) {
                $table->date('fecha_inicio')->after('tipo_novedad');
            }
            if (!Schema::hasColumn('empleados_novedades', 'fecha_fin')) {
                $table->date('fecha_fin')->nullable()->after('fecha_inicio');
            }
            if (!Schema::hasColumn('empleados_novedades', 'dias')) {
                $table->integer('dias')->nullable()->after('fecha_fin')->comment('Días de la novedad');
            }
            if (!Schema::hasColumn('empleados_novedades', 'valor')) {
                $table->bigInteger('valor')->nullable()->after('dias')->comment('Valor monetario si aplica');
            }
            if (!Schema::hasColumn('empleados_novedades', 'observacion')) {
                $table->text('observacion')->nullable()->after('valor');
            }
            if (!Schema::hasColumn('empleados_novedades', 'documento_soporte')) {
                $table->string('documento_soporte', 255)->nullable()->after('observacion')->comment('Ruta del documento de soporte');
            }
            if (!Schema::hasColumn('empleados_novedades', 'estado')) {
                $table->string('estado', 20)->default('activo')->after('documento_soporte')->comment('activo, finalizado, cancelado');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados_novedades', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_novedad',
                'fecha_inicio',
                'fecha_fin',
                'dias',
                'valor',
                'observacion',
                'documento_soporte',
                'estado'
            ]);
        });
    }
}
