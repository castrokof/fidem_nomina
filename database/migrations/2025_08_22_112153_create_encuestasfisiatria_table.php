<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncuestasfisiatriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encuestasfisiatria', function (Blueprint $table) {
            $table->bigIncrements('id'); 
             // Datos personales
            $table->string('surname',100);
            $table->string('ssurname',100)->nullable();
            $table->string('fname',100);
            $table->string('sname',100)->nullable();
            $table->string('type_document',10)->nullable();
            $table->string('document',19);
            $table->string('eapb',50);

            // Datos de la solicitud
            $table->date('fecha_solicitud');
            $table->string('profesional',100)->nullable(); // profesional asigna cita
            $table->string('dx',50);

            // Dispositivo de apoyo
            $table->string('dispositivo_silla',50);
            $table->string('dispositivo_apoyo',50);
            $table->string('other',50)->nullable();
            $table->string('solicitud_dispositivo')->nullable();

            // Antecedentes
            $table->string('antecedentes_dx_cancer',100);
            $table->string('antecedentes_toxina_espasticidad',100);

            // Camilla / Ambulancia
            $table->string('camilla_ambulancia',100);

            // Tipo de solicitud y observación
            $table->string('tipo_solicitud',100)->nullable();
            $table->longText('observacion')->nullable();

            // Justificación / motivo de consulta
            $table->longText('reason_consultation')->nullable();

            // Relación con usuario
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'fk_usuario_encuestasfisiatria')
                  ->references('id')->on('usuario')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');

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
        Schema::dropIfExists('encuestasfisiatria');
    }
}
