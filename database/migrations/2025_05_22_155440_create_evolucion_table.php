<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvolucionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evoluciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('fidemcontigos_id')->constrained('fidemcontigos')->onDelete('cascade');
            $table->string('id_evolucion')->unique();
            $table->timestamp('fechahora_apertura')->nullable();
            $table->timestamp('fechahora_evolucion')->nullable();
            $table->string('cuestionario');
            $table->string('respuesta'); // EVA
            $table->string('codigo_profesional');
            $table->string('dx_principal')->nullable();
            $table->string('dx_secondary')->nullable();
            $table->string('tipo_evolucion');
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
        Schema::dropIfExists('evoluciones');
    }
}
