<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsicologicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psicologica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('surname',100);
            $table->string('ssurname',100)->nullable();
            $table->string('fname',100);
            $table->string('sname',100)->nullable();
            $table->string('type_document',10);
            $table->string('document',19);
            $table->date('date_birth');
            $table->string('municipality',50);
            $table->string('other',50)->nullable();
            $table->string('address',100);
            $table->string('celular',100);
            $table->string('phone',100);
            $table->string('email',100)->nullable();
            $table->string('sex',100);
            $table->string('eapb',50);
            $table->longText('reason_consultation');
            $table->string('consultation',50);
            $table->string('diagnosis',10);
            $table->string('future1')->nullable();
            $table->string('future2')->nullable();
            $table->string('future3')->nullable();
            $table->string('future4')->nullable();
            $table->string('future5')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_usuario_linepsicologica')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('psicologica');
    }
}
