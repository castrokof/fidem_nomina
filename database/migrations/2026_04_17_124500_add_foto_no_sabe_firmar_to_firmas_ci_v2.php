<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoNoSabeFirmarToFirmasCiV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('firmas_ci', function (Blueprint $table) {
        $table->longText('firma_base64')->nullable()->change();
        $table->longText('foto_base64')->nullable()->after('firma_base64');
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
