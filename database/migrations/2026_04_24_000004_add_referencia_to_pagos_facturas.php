<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenciaToPageosFacturas extends Migration
{
    public function up()
    {
        Schema::table('pagos_facturas', function (Blueprint $table) {
            $table->string('referencia', 100)->nullable()->after('descripcion');
            // Ampliar para soportar múltiples correos separados por coma
            $table->text('correo_notificacion')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pagos_facturas', function (Blueprint $table) {
            $table->dropColumn('referencia');
            $table->string('correo_notificacion', 150)->nullable()->change();
        });
    }
}
