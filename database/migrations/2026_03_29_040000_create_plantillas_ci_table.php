<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantillasCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantillas_ci', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->string('cups_codigo', 20)->nullable();
            $table->longText('contenido_html');
            $table->json('variables_disponibles')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('uso_general')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plantillas_ci');
    }
}
