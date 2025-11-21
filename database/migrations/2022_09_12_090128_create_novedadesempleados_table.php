<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovedadesempleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novedadesempleados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('observacion_noveempleado')->nullable();
            $table->string('type_noveempleado', 255)->nullable();
            $table->unsignedBigInteger('noveempleado_id');
            $table->foreign('noveempleado_id', 'fk_novedadesempleados_empleados')->references('id')->on('empleados')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_novedadesempleados_usuario')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('novedadesempleados');
    }
}
