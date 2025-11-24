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

            // Campos de anulación
            $table->boolean('anulado')->default(false);
            $table->unsignedInteger('anulado_por_movimiento_id')->nullable();
            $table->unsignedInteger('anulado_por_user_id')->nullable();
            $table->timestamp('anulado_at')->nullable();
            $table->string('motivo_anulacion', 200)->nullable();

            // Índices con nombres personalizados cortos
            $table->index('medicamento_controlado_id', 'idx_mov_medicamento_id');
            $table->index('user_id', 'idx_mov_user_id');
            $table->index('anulado', 'idx_mov_anulado');
            $table->index('anulado_por_movimiento_id', 'idx_mov_anulado_por_mov');
        });

        // Agregar foreign keys después de crear la tabla
        Schema::table('medicamentos_controlados_movimientos', function (Blueprint $table) {
            $table->foreign('medicamento_controlado_id', 'fk_mov_medicamento')
                ->references('id')->on('medicamentos_controlados')
                ->onDelete('restrict')->onUpdate('restrict');

            $table->foreign('user_id', 'fk_mov_user')
                ->references('id')->on('usuario')
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