<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novedades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type_nove', 255)->nullable();
            $table->bigInteger('road_v')->nullable();
            $table->bigInteger('value_ps')->nullable();
            $table->bigInteger('value_ps_desc')->nullable();
            $table->bigInteger('prestamo')->nullable();
            $table->integer('hours')->nullable();
            $table->integer('total_pac')->nullable();
            $table->integer('value_inc')->nullable();
            $table->string('day_inc')->nullable();
            $table->longText('nove_observacion')->nullable();
            $table->unsignedBigInteger('nove_id');
            $table->foreign('nove_id', 'fk_novedades_nominaliquids')->references('id')->on('nominaliquids')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_novedades_usuario')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('novedades');
    }
}
