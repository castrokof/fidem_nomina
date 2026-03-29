<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmasCiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firmas_ci', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consentimiento_id');
            $table->enum('tipo_firmante', ['paciente', 'acudiente', 'profesional']);
            $table->longText('firma_base64');
            $table->string('firmante_nombre', 200);
            $table->string('firmante_cedula', 20)->nullable();
            $table->string('firmante_relacion', 100)->nullable()->comment('padre/madre/tutor/cónyuge/hermano/otro');
            $table->string('ip_firma', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('firmado_at');
            $table->timestamps();

            // Foreign key
            $table->foreign('consentimiento_id')->references('id')->on('consentimientos_informados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firmas_ci');
    }
}
