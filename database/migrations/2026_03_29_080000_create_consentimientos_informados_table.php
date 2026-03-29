<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsentimientosInformadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consentimientos_informados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('agenda_ci_id')->nullable();
            $table->unsignedBigInteger('paciente_id')->nullable()->comment('FK a pacientes');
            $table->string('paciente_nombre', 200);
            $table->string('paciente_cedula', 20);
            $table->string('paciente_tipo_doc', 5)->default('CC');
            $table->tinyInteger('paciente_edad')->nullable();
            $table->string('paciente_genero', 5)->nullable();
            $table->date('paciente_fecha_nacimiento')->nullable();
            $table->unsignedBigInteger('profesional_id')->nullable()->comment('FK a profesionales');
            $table->string('profesional_nombre', 200);
            $table->unsignedBigInteger('especialidad_id')->nullable();
            $table->unsignedBigInteger('plantilla_id');
            $table->string('cups_codigo', 20)->nullable();
            $table->string('cups_descripcion', 300)->nullable();
            $table->date('fecha_procedimiento');
            $table->enum('estado', ['pendiente', 'en_proceso', 'firmado', 'cancelado'])->default('pendiente');
            $table->boolean('requiere_acudiente')->default(false);
            $table->string('pdf_path', 500)->nullable();
            $table->string('token_firma', 64)->unique();
            $table->timestamp('token_expira_at')->nullable();
            $table->string('ip_generacion', 45)->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('agenda_ci_id')->references('id')->on('agenda_ci')->onDelete('set null');
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('set null');
            $table->foreign('profesional_id')->references('id')->on('profesionales')->onDelete('set null');
            $table->foreign('especialidad_id')->references('id')->on('especialidades')->onDelete('set null');
            $table->foreign('plantilla_id')->references('id')->on('plantillas_ci')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consentimientos_informados');
    }
}
