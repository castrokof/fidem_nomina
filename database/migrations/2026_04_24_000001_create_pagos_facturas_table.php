<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosFacturasTable extends Migration
{
    public function up()
    {
        Schema::create('pagos_facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 100);
            $table->string('categoria', 60)->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedTinyInteger('dia_vencimiento'); // 1–31
            $table->decimal('monto_estimado', 12, 2)->default(0);
            $table->string('correo_notificacion', 150)->nullable();
            $table->unsignedTinyInteger('dias_aviso')->default(3);
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos_facturas');
    }
}
