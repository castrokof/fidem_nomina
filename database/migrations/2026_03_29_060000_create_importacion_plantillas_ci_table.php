<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportacionPlantillasCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importacion_plantillas_ci', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 200);
            $table->string('especialidades', 500)->nullable()->comment('Nombres separados por coma');
            $table->string('cups_codigo', 20)->nullable();
            $table->boolean('uso_general')->default(false);
            $table->longText('contenido_texto');
            $table->longText('contenido_html')->nullable();
            $table->enum('estado', ['pendiente', 'procesado', 'error'])->default('pendiente');
            $table->text('error_mensaje')->nullable();
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
        Schema::dropIfExists('importacion_plantillas_ci');
    }
}
