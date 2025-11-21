<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObspaliativosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obspaliativos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('observacion');
            $table->date('date_hospi')->nullable();
            $table->string('type_obs');
            $table->string('subtype_obs')->nullable();
            $table->string('future1')->nullable();
            $table->string('future2')->nullable();
            $table->string('future3')->nullable();
            $table->unsignedBigInteger('pac_id');
            $table->foreign('pac_id', 'fk_obspaliativos_bdpaliativos')->references('id')->on('bdpaliativos')->onDelete('restrict');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_usuario_obspaliativos')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();

    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obspaliativos');
    }
}
