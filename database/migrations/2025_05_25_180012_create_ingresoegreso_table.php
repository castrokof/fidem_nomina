<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresoegresoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresoegresos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('fidemcontigos_id')->constrained('fidemcontigos')->onDelete('cascade');
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->timestamp('fecha')->nullable();
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('ingresoegresos');
    }
}
