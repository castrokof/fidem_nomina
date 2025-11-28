<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empleado_id');
            $table->bigInteger('salary')->nullable()->comment('Salario fijo mensual');
            $table->bigInteger('salary_ps')->nullable()->comment('Salario prestación de servicios');
            $table->date('fecha_inicio')->comment('Fecha de inicio de vigencia del salario');
            $table->date('fecha_fin')->nullable()->comment('Fecha de fin de vigencia del salario (null = actual)');
            $table->unsignedInteger('created_by')->nullable()->comment('Usuario que registró el cambio');
            $table->string('motivo', 500)->nullable()->comment('Motivo del cambio salarial');
            $table->boolean('activo')->default(true)->comment('Indica si este registro es el salario vigente');
            $table->timestamps();

            // Foreign keys
            $table->foreign('empleado_id', 'fk_salary_history_empleado')
                  ->references('id')
                  ->on('empleados')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('created_by', 'fk_salary_history_user')
                  ->references('id')
                  ->on('usuario')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            // Índices para optimizar consultas
            $table->index('empleado_id');
            $table->index('fecha_inicio');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_history');
    }
}
