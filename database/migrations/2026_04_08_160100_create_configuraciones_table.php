<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('clave', 100)->unique()->comment('Clave de configuración');
            $table->text('valor')->nullable()->comment('Valor de configuración');
            $table->string('tipo', 50)->default('texto')->comment('Tipo: texto, imagen, numero, boolean');
            $table->string('descripcion', 255)->nullable()->comment('Descripción de la configuración');
            $table->timestamps();
        });

        // Insertar configuración por defecto para el logo
        DB::table('configuraciones')->insert([
            'clave' => 'logo_fidem_path',
            'valor' => null,
            'tipo' => 'imagen',
            'descripcion' => 'Ruta de la imagen del logo de FIDEM para consentimientos informados',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuraciones');
    }
}
