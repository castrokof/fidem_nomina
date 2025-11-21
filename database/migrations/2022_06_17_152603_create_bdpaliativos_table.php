<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBdpaliativosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bdpaliativos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('surname',100);
            $table->string('ssurname',100)->nullable();
            $table->string('fname',100);
            $table->string('sname',100)->nullable();
            $table->string('type_document',10);
            $table->string('document',19);
            $table->date('date_birth')->nullable();
            $table->longText('diagnosis')->nullable();
            $table->string('municipality',50)->nullable();
            $table->string('address',100)->nullable();
            $table->string('celular',100)->nullable();
            $table->string('phone',100)->nullable();;
            $table->string('email',100)->nullable();
            $table->longText('observacion')->nullable();
            $table->date('date_in')->nullable();
            $table->string('dead',2)->nullable();
            $table->date('date_dead')->nullable();
            $table->string('state',50);
            $table->string('type',50);
            $table->string('future1')->nullable();
            $table->string('profesional')->nullable();
            $table->string('sex')->nullable();
            $table->string('diagn')->nullable();
            $table->string('ips')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_usuario_bdpaliativos')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('bdpaliativos');
    }
}
