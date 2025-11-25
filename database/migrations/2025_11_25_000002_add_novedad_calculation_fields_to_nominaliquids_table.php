<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNovedadCalculationFieldsToNominaliquidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nominaliquids', function (Blueprint $table) {
            // Campos para registrar ajustes por novedades
            $table->decimal('descuento_incapacidad', 10, 2)->default(0)->after('is_locked');
            $table->decimal('descuento_suspension', 10, 2)->default(0)->after('descuento_incapacidad');
            $table->decimal('pago_vacaciones', 10, 2)->default(0)->after('descuento_suspension');
            $table->decimal('otros_descuentos_novedades', 10, 2)->default(0)->after('pago_vacaciones');
            $table->decimal('otros_bonos_novedades', 10, 2)->default(0)->after('otros_descuentos_novedades');
            $table->text('novedades_aplicadas')->nullable()->after('otros_bonos_novedades'); // JSON con IDs de novedades aplicadas
            $table->integer('dias_trabajados')->nullable()->after('novedades_aplicadas'); // Días efectivamente trabajados considerando novedades
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nominaliquids', function (Blueprint $table) {
            $table->dropColumn([
                'descuento_incapacidad',
                'descuento_suspension',
                'pago_vacaciones',
                'otros_descuentos_novedades',
                'otros_bonos_novedades',
                'novedades_aplicadas',
                'dias_trabajados'
            ]);
        });
    }
}
