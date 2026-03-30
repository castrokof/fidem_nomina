<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_documento', 5)->default('CC');
            $table->string('numero_documento', 20);
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento')->nullable();
            $table->tinyInteger('edad')->nullable();
            $table->enum('genero', ['M', 'F', 'O'])->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('historia_clinica', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->timestamps();

            // Índice único compuesto
            $table->unique(['tipo_documento', 'numero_documento'], 'uk_paciente_doc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
}
