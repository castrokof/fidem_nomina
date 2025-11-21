<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicamentosControladosMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicamentos_controlados_movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('medicamento_controlado_id');
            $table->date('fecha');
            $table->enum('tipo_movimiento', ['entrada', 'salida']);

            // Campos para ENTRADA
            $table->string('proveedor', 200)->nullable();
            $table->string('numero_factura', 100)->nullable();

            // Campos para SALIDA
            $table->string('nombre_paciente', 200)->nullable();
            $table->string('cedula_paciente', 50)->nullable();
            $table->string('numero_formula_control', 100)->nullable();
            $table->string('foto_formula', 255)->nullable();

            // Cantidades
            $table->integer('entrada')->default(0);
            $table->integer('salida')->default(0);
            $table->integer('saldo');

            // Auditoría
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('medicamento_controlado_id', 'fk_movmedicamento_medicamento')
                ->references('id')->on('medicamentos_controlados')
                ->onDelete('restrict')->onUpdate('restrict');

            $table->foreign('user_id', 'fk_movmedicamento_user')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicamentos_controlados_movimientos');
    }
}
