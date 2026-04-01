<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEdadGeneroToFirmasCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firmas_ci', function (Blueprint $table) {
            $table->integer('firmante_edad')->nullable()->after('firmante_cedula')->comment('Edad del firmante al momento de firmar');
            $table->enum('firmante_genero', ['Masculino', 'Femenino', 'Otro'])->nullable()->after('firmante_edad')->comment('Género del firmante al momento de firmar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firmas_ci', function (Blueprint $table) {
            $table->dropColumn(['firmante_edad', 'firmante_genero']);
        });
    }
}
