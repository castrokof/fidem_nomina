<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcudientesCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acudientes_ci', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consentimiento_id');
            $table->string('nombre_completo', 200);
            $table->string('cedula', 20);
            $table->string('relacion_con_paciente', 100);
            $table->string('telefono', 20)->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('consentimiento_id')->references('id')->on('consentimientos_informados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acudientes_ci');
    }
}
