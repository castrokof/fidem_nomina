<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposEntradaToMedicamentosControladosMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicamentos_controlados_movimientos', function (Blueprint $table) {
            // Agregar campos para entradas de medicamentos
            $table->date('fecha_vencimiento')->nullable()->after('numero_factura');
            $table->string('registro_invima', 100)->nullable()->after('fecha_vencimiento');
            $table->string('lote', 100)->nullable()->after('registro_invima');
            $table->text('observaciones')->nullable()->after('lote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicamentos_controlados_movimientos', function (Blueprint $table) {
            $table->dropColumn(['fecha_vencimiento', 'registro_invima', 'lote', 'observaciones']);
        });
    }
}
