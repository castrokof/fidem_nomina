<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendaCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_ci', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_registro', 20)->unique();
            $table->dateTime('fecha');
            $table->string('codigo_consultorio', 20)->nullable();
            $table->string('historia', 50)->nullable();
            $table->unsignedBigInteger('paciente_id')->nullable()->comment('FK a pacientes si ya existe');
            $table->string('paciente_nombre', 200);
            $table->string('paciente_cedula', 20);
            $table->string('paciente_tipo_doc', 5)->default('CC');
            $table->string('paciente_telefono', 20)->nullable();
            $table->unsignedBigInteger('profesional_id')->nullable()->comment('FK a profesionales');
            $table->string('codigo_usuario', 50)->nullable();
            $table->string('cups_codigo', 20)->nullable();
            $table->string('contrato', 30)->nullable();
            $table->string('empresafac', 20)->nullable();
            $table->boolean('llegada_confirmada')->default(false);
            $table->string('numero_factura', 30)->nullable();
            $table->dateTime('atencion_factura')->nullable();
            $table->timestamp('sincronizado_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('set null');
            $table->foreign('profesional_id')->references('id')->on('profesionales')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_ci');
    }
}
