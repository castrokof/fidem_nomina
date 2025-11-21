<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_documento',10)->nullable();
            $table->string('documento',19)->nullable();
            $table->string('paciente', 255)->nullable();
            $table->string('estado_pac');
            $table->unsignedBigInteger('id_estado');
            $table->string('dx_principal');
            $table->string('dx_relacionado');
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
        Schema::dropIfExists('estados');
    }
}
