<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeseaSerInformadoToConsentimientosInformadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consentimientos_informados', function (Blueprint $table) {
            $table->boolean('desea_ser_informado')->default(true)->after('paciente_genero');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consentimientos_informados', function (Blueprint $table) {
            $table->dropColumn('desea_ser_informado');
        });
    }
}
