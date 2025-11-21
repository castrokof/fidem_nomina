<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ips',50);
            $table->string('type_contrat', 255)->nullable();
            $table->date('day_inicio')->nullable();
            $table->date('day_fin')->nullable();
            $table->bigInteger('value_ps')->nullable();
            $table->bigInteger('value_ps_desc')->nullable();
            $table->bigInteger('road_v')->nullable();
            $table->integer('hours')->nullable();
            $table->integer('pac')->nullable();
            $table->longText('contrat_observacion')->nullable();
            $table->longText('photo_base64')->nullable();
            $table->unsignedBigInteger('empleadosc_id');
            $table->foreign('empleadosc_id', 'fk_empleadosc_contratos')->references('id')->on('empleados')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_contratos_usuario')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');
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
