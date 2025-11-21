<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUseIdToObspsicologia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('obspsicologia', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->after('evo_id');
            $table->foreign('user_id', 'fk_usuario_obspsicologia')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('obspsicologia', function (Blueprint $table) {
            Schema::dropColumn('usuario_id');
        });
    }
}
