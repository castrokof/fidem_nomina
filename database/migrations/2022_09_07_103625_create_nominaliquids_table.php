<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominaliquidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominaliquids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date_hour_initial_turn');
            $table->dateTime('date_hour_end_turn');
            $table->string('working_type', 20);
            $table->string('day_work');
            $table->decimal('hours',10,1);
            $table->string('position');
            $table->string('eps')->nullable();
            $table->string('arl')->nullable();
            $table->string('afp')->nullable();
            $table->string('fc')->nullable();
            $table->string('quincena', 50)->nullable();
            $table->bigInteger('salary')->nullable();
            $table->bigInteger('salary_ps')->nullable();
            $table->Integer('value_hour')->nullable();
            $table->Integer('value_patient_attended')->nullable();
            $table->Integer('value_add_security_social')->nullable();
            $table->Integer('value_transporte')->nullable();
            $table->Integer('value_salary_add')->nullable();
            $table->string('name_bank', 255)->nullable();
            $table->string('account', 255)->nullable();
            $table->string('type_account', 255)->nullable();
            $table->string('type_contrat', 255)->nullable();
            $table->string('type_salary', 255)->nullable();
            $table->date('date_in');
            $table->date('date_out')->nullable();
            $table->date('date_incontrat')->nullable();
            $table->date('date_endcontrat')->nullable();
            $table->text('observation')->nullable();
            $table->string('supervisor', 50)->nullable();
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id', 'fk_empleados_nominaliquids')->references('id')->on('empleados')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_usuario_nominaliquids')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('nominaliquids');
    }
}
