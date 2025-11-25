<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominaNovedadesAplicadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomina_novedades_aplicadas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('nominaliquid_id');
            $table->unsignedBigInteger('empleado_novedad_id');
            $table->string('tipo_novedad', 50);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->integer('dias_aplicados')->default(0);
            $table->decimal('valor_aplicado', 10, 2)->default(0);
            $table->string('tipo_afectacion', 20); // 'descuento', 'bono', 'neutro'
            $table->text('observacion')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('nominaliquid_id', 'fk_nomina_novedades_nominaliquid')
                ->references('id')
                ->on('nominaliquids')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('empleado_novedad_id', 'fk_nomina_novedades_empleado_novedad')
                ->references('id')
                ->on('empleados_novedades')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nomina_novedades_aplicadas');
    }
}
