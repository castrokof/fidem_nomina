<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFidemcontigoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fidemcontigos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipdocum', 10);
            $table->string('numdocum', 20)->unique(); // Documento como identificador único
            $table->string('numhistoria');
            $table->string('apellido1');
            $table->string('apellido2')->nullable();
            $table->string('nombre1');
            $table->string('nombre2')->nullable();
            $table->string('entidad_salud');
            $table->string('telefono');
            $table->string('telefono_avi')->nullable();
            $table->string('telefono_residencia')->nullable();
            $table->string('telefono_movil')->nullable();
            $table->string('estado')->default('pendiente'); // activo o inactivo según EVA
            $table->string('id_evolucion')->nullable();
            $table->string('tipo_evolucion');
            $table->timestamp('fecha_ultima_evolucion')->nullable();
            $table->string('eva')->nullable(); // último EVA
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
        Schema::dropIfExists('fidemcontigos');
    }
}
