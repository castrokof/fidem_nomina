<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnulacionFieldsToMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicamentos_controlados_movimientos', function (Blueprint $table) {
            // Campo para marcar si el movimiento está anulado
            $table->boolean('anulado')->default(false)->after('saldo');

            // ID del movimiento que anuló este movimiento (si fue anulado)
            $table->unsignedInteger('anulado_por_movimiento_id')->nullable()->after('anulado');

            // Usuario que anuló el movimiento
            $table->unsignedInteger('anulado_por_user_id')->nullable()->after('anulado_por_movimiento_id');

            // Fecha y hora de anulación
            $table->timestamp('anulado_at')->nullable()->after('anulado_por_user_id');

            // Motivo de anulación
            $table->string('motivo_anulacion', 500)->nullable()->after('anulado_at');

            // Índices
            $table->index('anulado');
            $table->index('anulado_por_movimiento_id');
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
            $table->dropIndex(['anulado']);
            $table->dropIndex(['anulado_por_movimiento_id']);
            $table->dropColumn(['anulado', 'anulado_por_movimiento_id', 'anulado_por_user_id', 'anulado_at', 'motivo_anulacion']);
        });
    }
}
