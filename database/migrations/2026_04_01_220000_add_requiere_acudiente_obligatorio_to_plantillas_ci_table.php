<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequiereAcudienteObligatorioToPlantillasCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plantillas_ci', function (Blueprint $table) {
            $table->boolean('requiere_acudiente_obligatorio')->default(false)->after('uso_general');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plantillas_ci', function (Blueprint $table) {
            $table->dropColumn('requiere_acudiente_obligatorio');
        });
    }
}
