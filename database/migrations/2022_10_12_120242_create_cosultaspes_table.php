<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCosultaspesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cosultaspes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_documento',10)->nullable();
            $table->string('documento',20);
            $table->string('paciente', 255)->nullable();
            $table->string('dx_principal')->nullable();
            $table->string('dx_relacionado')->nullable();
            $table->dateTime('paliativista1')->nullable();
            $table->dateTime('paliativista2')->nullable();
            $table->dateTime('paliativista3')->nullable();
            $table->dateTime('paliativista4')->nullable();
            $table->dateTime('paliativista5')->nullable();
            $table->dateTime('experto1')->nullable();
            $table->dateTime('experto2')->nullable();
            $table->dateTime('experto3')->nullable();
            $table->dateTime('experto4')->nullable();
            $table->dateTime('experto5')->nullable();
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
        Schema::dropIfExists('cosultaspes');
    }
}
