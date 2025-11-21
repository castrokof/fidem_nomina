<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('ordenes_medicamentos_filtradas', function (Blueprint $table) {
        $table->bigIncrements('id'); // ID autoincremental de Laravel
        $table->foreignId('evoluciones_id')->constrained('evoluciones')->onDelete('cascade');
        $table->string('causa')->nullable();
        $table->string('codigo')->nullable();
        $table->string('presentacion')->nullable();
        $table->string('nombre')->nullable();
        $table->integer('cantidad')->nullable();
        $table->string('administracion')->nullable();
        $table->decimal('dosis_cant', 8, 2)->nullable();
        $table->string('dosis_freq')->nullable();
        $table->string('dosis_hora')->nullable();
        $table->integer('numero_dosis')->nullable();
        $table->text('posologia')->nullable();
        $table->text('observaciones')->nullable();
        $table->boolean('entregado')->nullable(); // si el medicamento fue entregado
        $table->text('observacion_entrega')->nullable(); // observación específica del medicamento
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
        Schema::dropIfExists('ordenes_medicamentos_filtradas'); 
    }
}
