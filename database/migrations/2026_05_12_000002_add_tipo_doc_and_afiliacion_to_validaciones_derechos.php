<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoDocAndAfiliacionToValidacionesDerechos extends Migration
{
    public function up()
    {
        Schema::table('validaciones_derechos', function (Blueprint $table) {
            $table->string('paciente_tipo_doc', 5)->nullable()->after('paciente_nombre');
            $table->string('estado_afiliacion', 100)->nullable()->after('paciente_cedula');
        });
    }

    public function down()
    {
        Schema::table('validaciones_derechos', function (Blueprint $table) {
            $table->dropColumn(['paciente_tipo_doc', 'estado_afiliacion']);
        });
    }
}
