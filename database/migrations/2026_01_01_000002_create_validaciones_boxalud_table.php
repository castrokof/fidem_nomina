<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidacionesBoxaludTable extends Migration
{
    public function up()
    {
        Schema::create('validaciones_boxalud', function (Blueprint $table) {

            $table->increments('id');

            // ── Identificación ──────────────────────────────────────────────
            $table->string('tipo_documento', 5)->nullable();        // CC, TI, CE...
            $table->string('numero_documento', 20)->index();
            $table->string('primer_nombre', 60)->nullable();
            $table->string('segundo_nombre', 60)->nullable();
            $table->string('primer_apellido', 60)->nullable();
            $table->string('segundo_apellido', 60)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('tipo_afiliado', 80)->nullable();        // Cotizante, Beneficiario...

            // ── Plan y estados ──────────────────────────────────────────────
            $table->string('plan', 60)->nullable();                 // Régimen Contributivo / Subsidiado
            $table->string('vigencia', 30)->nullable();             // Vigente / No vigente
            $table->string('estado_pagos', 30)->nullable();         // Al día / En mora
            $table->string('estado_documentos', 30)->nullable();    // Al día / Pendiente

            // ── Datos biológicos ────────────────────────────────────────────
            $table->char('sexo_biologico', 1)->nullable();
            $table->char('sexo_identificacion', 1)->nullable();
            $table->string('rango_salarial', 20)->nullable();

            // ── Origen ──────────────────────────────────────────────────────
            $table->string('nacionalidad', 50)->nullable();
            $table->string('pais_nacimiento', 50)->nullable();
            $table->string('departamento_nacimiento', 60)->nullable();
            $table->string('municipio_nacimiento', 60)->nullable();

            // ── Datos de atención actuales ──────────────────────────────────
            $table->string('departamento_atencion', 60)->nullable();
            $table->string('municipio_atencion', 60)->nullable();
            $table->string('localidad', 80)->nullable();
            $table->string('barrio', 100)->nullable();
            $table->string('direccion', 120)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('correo_electronico', 100)->nullable();
            $table->date('fecha_inicio_atencion')->nullable();
            $table->date('fecha_fin_atencion')->nullable();

            // ── IPS ─────────────────────────────────────────────────────────
            $table->string('ips_nombre_oferta', 100)->nullable();   // IPS JAMUNDI
            $table->string('ips_codigo', 130)->nullable();           // 7600105817 - SERVIMEDIC...
            $table->string('ips_sede', 130)->nullable();             // 8005 - IPS JAMUNDI
            $table->string('ips_servicio', 60)->nullable();          // 0001 - MEDICINA GENERAL

            // ── Evidencia y trazabilidad ────────────────────────────────────
            $table->string('screenshot_path')->nullable();           // ruta en storage/app/
            $table->text('datos_raw')->nullable();                   // JSON completo del plugin
            $table->string('url_consultada')->nullable();
            $table->string('ip_origen', 45)->nullable();
            $table->string('user_agent')->nullable();

            // ── Relaciones ──────────────────────────────────────────────────
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('usuario')
                  ->onDelete('set null');

            // ── Fechas ──────────────────────────────────────────────────────
            $table->timestamp('fecha_consulta')->useCurrent();       // fecha real de la consulta
            $table->timestamps();                                     // created_at / updated_at

            // ── Índices para búsquedas frecuentes ───────────────────────────
            $table->index(['numero_documento', 'fecha_consulta']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::drop('validaciones_boxalud');
    }
}
