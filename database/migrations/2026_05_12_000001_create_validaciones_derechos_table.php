<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidacionesDerechosTable extends Migration
{
    public function up()
    {
        Schema::create('validaciones_derechos', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Enlace a la agenda sincronizada (opcional, por si no se encontró)
            $table->unsignedBigInteger('agenda_ci_id')->nullable();
            $table->foreign('agenda_ci_id')->references('id')->on('agenda_ci')->onDelete('set null');

            // Datos del paciente / cita (denormalizados para trazabilidad)
            $table->string('paciente_nombre', 250)->nullable();
            $table->string('paciente_cedula', 30)->nullable();
            $table->string('numero_factura', 50)->nullable();
            $table->string('atencion_factura', 50)->nullable();
            $table->string('contrato', 100)->nullable();
            $table->string('empresafac', 200)->nullable();
            $table->date('fecha_atencion')->nullable();
            $table->string('cups_codigo', 30)->nullable();
            $table->string('cups_descripcion', 300)->nullable();

            // Imagen del pantallazo
            $table->string('imagen_path', 500);          // ruta en storage/app/

            // Notas adicionales
            $table->text('observaciones')->nullable();

            // Trazabilidad
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('usuario')->onDelete('set null');
            $table->string('created_by_nombre', 150)->nullable();
            $table->string('ip_registro', 45)->nullable();

            $table->timestamps();

            $table->index(['paciente_cedula']);
            $table->index(['numero_factura']);
            $table->index(['fecha_atencion']);
            $table->index(['created_by']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('validaciones_derechos');
    }
}
