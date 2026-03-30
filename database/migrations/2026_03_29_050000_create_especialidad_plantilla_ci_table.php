<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEspecialidadPlantillaCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especialidad_plantilla_ci', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('especialidad_id');
            $table->unsignedBigInteger('plantilla_ci_id');
            $table->timestamps();

            // Índice único compuesto
            $table->unique(['especialidad_id', 'plantilla_ci_id'], 'uk_esp_plt');

            // Foreign keys
            $table->foreign('especialidad_id')->references('id')->on('especialidades')->onDelete('cascade');
            $table->foreign('plantilla_ci_id')->references('id')->on('plantillas_ci')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('especialidad_plantilla_ci');
    }
}
