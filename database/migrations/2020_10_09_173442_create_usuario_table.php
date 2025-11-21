<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('papellido',100);
            $table->string('sapellido',100)->nullable();
            $table->string('pnombre',100);
            $table->string('snombre',100)->nullable();
            $table->string('tipo_documento',10);
            $table->string('documento',19);
            $table->string('usuario',20)->unique();
            $table->string('password',100);
            $table->string('remenber_token',100);
            $table->string('email',100)->unique();
            $table->string('celular',50)->nullable();
            $table->string('observacion',200)->nullable();
            $table->string('ips',50);
            $table->char('activo',1);
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
        Schema::dropIfExists('usuario');
    }
}
