<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migración: Ruta de Contratación Personal de Salud
 * Fidem Clínica del Dolor — Coordinación RRHH
 * Compatible con Laravel 5.7
 *
 * Ejecutar: php artisan migrate
 */
class CreateContratacionPersonalTable extends Migration
{
    public function up()
    {
        // ─────────────────────────────────────────────
        // Tabla principal de candidatos en proceso
        // ─────────────────────────────────────────────
        Schema::create('contratacion_candidatos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_completo');
            $table->string('cedula', 20)->unique();
            $table->string('cargo');
            $table->enum('tipo_personal', ['asistencial', 'administrativo'])->default('asistencial');
            $table->string('area')->nullable();                    // Ej: Urgencias, Consulta Externa
            $table->string('correo')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->unsignedTinyInteger('fase_actual')->default(1); // 1-7
            $table->unsignedTinyInteger('progreso_porcentaje')->default(0);
            $table->enum('estado', [
                'en_proceso',
                'documentos_pendientes',
                'aprobado',
                'rechazado',
                'vinculado'
            ])->default('en_proceso');
            $table->boolean('rethus_validado')->default(false);
            $table->date('rethus_fecha_validacion')->nullable();
            $table->string('rethus_numero')->nullable();
            $table->date('fecha_inicio_proceso')->nullable();
            $table->date('fecha_vinculacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->unsignedInteger('creado_por')->nullable();     // users.id
            $table->unsignedInteger('actualizado_por')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
            $table->index('tipo_personal');
            $table->index('fase_actual');
        });

        // ─────────────────────────────────────────────
        // Tabla de ítems del checklist por candidato
        // ─────────────────────────────────────────────
        Schema::create('contratacion_checklist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('candidato_id');
            $table->unsignedTinyInteger('fase');       // 1-7
            $table->string('item_key', 60);            // Ej: 'rethus', 'cedula', 'hep_b'
            $table->string('item_nombre');
            $table->boolean('completado')->default(false);
            $table->string('completado_por')->nullable();          // Nombre del responsable
            $table->timestamp('completado_at')->nullable();
            $table->text('nota')->nullable();                      // Observación por ítem
            $table->timestamps();

            $table->foreign('candidato_id')
                  ->references('id')
                  ->on('contratacion_candidatos')
                  ->onDelete('cascade');

            $table->unique(['candidato_id', 'item_key'], 'uq_candidato_item');
            $table->index(['candidato_id', 'fase']);
        });

        // ─────────────────────────────────────────────
        // Tabla de documentos adjuntos por candidato
        // ─────────────────────────────────────────────
        Schema::create('contratacion_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('candidato_id');
            $table->string('tipo_documento', 60);      // Ej: 'cedula', 'diploma', 'rethus'
            $table->string('nombre_archivo');
            $table->string('ruta_archivo');            // storage/contratacion/{cedula}/
            $table->string('mime_type', 60)->nullable();
            $table->unsignedInteger('subido_por')->nullable();
            $table->timestamps();

            $table->foreign('candidato_id')
                  ->references('id')
                  ->on('contratacion_candidatos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contratacion_documentos');
        Schema::dropIfExists('contratacion_checklist');
        Schema::dropIfExists('contratacion_candidatos');
    }
}
