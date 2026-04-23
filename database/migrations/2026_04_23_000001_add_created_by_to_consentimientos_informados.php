<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToConsentimientosInformados extends Migration
{
    public function up()
    {
        Schema::table('consentimientos_informados', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('ip_generacion');
            $table->string('created_by_nombre', 150)->nullable()->after('created_by');
        });
    }

    public function down()
    {
        Schema::table('consentimientos_informados', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'created_by_nombre']);
        });
    }
}
