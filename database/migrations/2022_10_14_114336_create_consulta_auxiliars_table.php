<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultaAuxiliarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consulta_auxiliars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_documento',10)->nullable();
            $table->string('documento',20);
            $table->string('paciente', 255)->nullable();
            $table->string('dx_principal')->nullable();
            $table->string('dx_relacionado')->nullable();
            $table->dateTime('auxiliar1')->nullable();
            $table->dateTime('auxiliar2')->nullable();
            $table->dateTime('auxiliar3')->nullable();
            $table->dateTime('auxiliar4')->nullable();
            $table->dateTime('auxiliar5')->nullable();
            $table->dateTime('auxiliar6')->nullable();
            $table->dateTime('auxiliar7')->nullable();
            $table->dateTime('auxiliar8')->nullable();
            $table->dateTime('auxiliar9')->nullable();
            $table->dateTime('auxiliar10')->nullable();
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
        Schema::dropIfExists('consulta_auxiliars');
    }
}
