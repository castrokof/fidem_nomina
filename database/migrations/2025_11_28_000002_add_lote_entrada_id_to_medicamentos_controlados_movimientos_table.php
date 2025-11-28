<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoteEntradaIdToMedicamentosControladosMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicamentos_controlados_movimientos', function (Blueprint $table) {
            // Agregar campo para referenciar el lote de entrada usado en cada salida
            $table->unsignedInteger('lote_entrada_id')->nullable()->after('medicamento_controlado_id');

            // Agregar índice para mejorar performance de consultas
            $table->index('lote_entrada_id', 'idx_mov_lote_entrada_id');
        });

        // Agregar foreign key después de crear el campo
        Schema::table('medicamentos_controlados_movimientos', function (Blueprint $table) {
            $table->foreign('lote_entrada_id', 'fk_mov_lote_entrada')
                ->references('id')->on('medicamentos_controlados_movimientos')
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
        Schema::table('medicamentos_controlados_movimientos', function (Blueprint $table) {
            $table->dropForeign('fk_mov_lote_entrada');
            $table->dropIndex('idx_mov_lote_entrada_id');
            $table->dropColumn('lote_entrada_id');
        });
    }
}
