<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados_novedades', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('papellido',100);
            $table->string('sapellido',100)->nullable();
            $table->string('pnombre',100);
            $table->string('snombre',100)->nullable();
            $table->string('tipo_documento',10);
            $table->string('documento',19);
            $table->string('email',100)->unique();
            $table->string('celular',50)->nullable();
            $table->string('observacion',200)->nullable();
            $table->string('ips',50);
            $table->string('position');
            $table->string('eps')->nullable();
            $table->string('arl')->nullable();
            $table->string('afp')->nullable();
            $table->string('fc')->nullable();
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
            $table->char('activo',1);
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id', 'fk_empleados_empleadosNovedades')->references('id')->on('empleados')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_usuario_empleadosNovedades')->references('id')->on('usuario')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('empleados_novedades');
    }
}
