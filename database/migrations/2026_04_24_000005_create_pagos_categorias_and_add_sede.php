<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosCategoriasAndAddSede extends Migration
{
    public function up()
    {
        Schema::create('pagos_categorias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 80)->unique();
            $table->timestamps();
        });

        Schema::table('pagos_facturas', function (Blueprint $table) {
            $table->string('sede', 100)->nullable()->after('referencia');
        });
    }

    public function down()
    {
        Schema::table('pagos_facturas', function (Blueprint $table) {
            $table->dropColumn('sede');
        });
        Schema::dropIfExists('pagos_categorias');
    }
}
