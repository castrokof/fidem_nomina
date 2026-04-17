<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFotoAndNoSabeFireToFirmasCi extends Migration
{
    public function up()
    {
        Schema::table('firmas_ci', function (Blueprint $table) {
            // firma_base64 pasa a nullable: el paciente puede entregar foto en vez de firma
            $table->longText('firma_base64')->nullable()->change();
            // Foto del paciente cuando no sabe firmar
            $table->longText('foto_base64')->nullable()->after('firma_base64');
            // Indica que el registro corresponde a identificación fotográfica
            $table->boolean('no_sabe_firmar')->default(false)->after('foto_base64');
        });
    }

    public function down()
    {
        Schema::table('firmas_ci', function (Blueprint $table) {
            $table->dropColumn(['foto_base64', 'no_sabe_firmar']);
            $table->longText('firma_base64')->nullable(false)->change();
        });
    }
}
