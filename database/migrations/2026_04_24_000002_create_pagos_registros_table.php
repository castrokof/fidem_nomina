<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosRegistrosTable extends Migration
{
    public function up()
    {
        Schema::create('pagos_registros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('factura_id');
            $table->unsignedTinyInteger('mes');   // 1–12
            $table->unsignedSmallInteger('anio'); // e.g. 2026
            $table->enum('estado', ['pendiente', 'pagado', 'vencido'])->default('pendiente');
            $table->date('fecha_pago')->nullable();
            $table->decimal('monto_pagado', 12, 2)->nullable();
            $table->text('notas')->nullable();
            $table->boolean('notificacion_enviada')->default(false);
            $table->timestamps();

            $table->unique(['factura_id', 'mes', 'anio']);
            $table->foreign('factura_id')->references('id')->on('pagos_facturas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos_registros');
    }
}
