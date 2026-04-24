<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosNotificacionesTable extends Migration
{
    public function up()
    {
        Schema::create('pagos_notificaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('factura_id');
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->string('titulo', 150);
            $table->text('mensaje');
            $table->enum('tipo', ['proximo', 'vencido'])->default('proximo');
            $table->boolean('leido')->default(false);
            $table->timestamps();

            $table->foreign('factura_id')->references('id')->on('pagos_facturas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos_notificaciones');
    }
}
