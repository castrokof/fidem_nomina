<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirmaImagenToProfesionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profesionales', function (Blueprint $table) {
            $table->string('firma_imagen_path', 255)->nullable()->after('firma_base64')->comment('Ruta de la imagen de firma digital cargada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profesionales', function (Blueprint $table) {
            $table->dropColumn('firma_imagen_path');
        });
    }
}
