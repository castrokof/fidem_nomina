<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatosVersioncalidadToImportacionPlantillasCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('importacion_plantillas_ci', function (Blueprint $table) {
            $table->string('codigo_calidad', 20)->nullable()->after('uso_general');
            $table->string('version_calidad', 20)->nullable()->after('uso_general');
            $table->date('fecha_calidad', 20)->nullable()->after('uso_general');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('importacion_plantillas_ci', function (Blueprint $table) {
            $table->dropColumn('codigo_calidad');
            $table->dropColumn('version_calidad');
            $table->dropColumn('fecha_calidad');
        });
    }
}
