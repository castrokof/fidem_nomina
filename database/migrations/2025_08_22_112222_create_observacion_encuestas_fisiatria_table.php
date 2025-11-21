<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservacionEncuestasFisiatriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observacion_encuestas_fisiatria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('addobservacion');
            $table->unsignedBigInteger('enc_id');
            $table->foreign('enc_id', 'fk_encuesta_fisiatria')->references('id')->on('encuestasfisiatria')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('observacion_encuestas_fisiatria');
    }
}
