<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('fidemcontigos_id')->constrained('fidemcontigos')->onDelete('cascade');
            $table->foreignId('evoluciones_id')->constrained('evoluciones')->onDelete('cascade'); // muchos seguimientos para una evolución
            $table->boolean('todos_entregados')->default(false); // indica si se entregaron todos
            $table->text('observacion_general')->nullable(); // observación del seguimiento completo
            $table->string('estado_contacto')->nullable();
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
        Schema::dropIfExists('seguimientos');
    }
}
