<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoABdpaliativosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bdpaliativos', function (Blueprint $table) {
            $table->string('estado_paci')->after('ips');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bdpaliativos', function (Blueprint $table) {
            $table->dropColumn('estado_paci');
            
        });
    }
}
