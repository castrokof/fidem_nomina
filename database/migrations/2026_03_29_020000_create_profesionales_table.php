<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfesionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profesionales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id')->nullable()->unique()->comment('FK a Usuarios solo si tiene login');
            $table->unsignedBigInteger('especialidad_id')->nullable()->comment('FK a especialidades');
            $table->string('codigo_usuario', 50)->nullable()->unique()->comment('Relaciona con CODIGO_USUARIO de fac_m_citas (trim)');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('tipo_documento', 5)->default('CC');
            $table->string('numero_documento', 20)->nullable();
            $table->string('registro_medico', 50)->nullable();
            $table->string('tarjeta_profesional', 50)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->longText('firma_base64')->nullable()->comment('Firma a mano alzada precargada');
            $table->timestamp('firma_actualizada_at')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Foreign keys
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');
            $table->foreign('especialidad_id')->references('id')->on('especialidades')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profesionales');
    }
}
