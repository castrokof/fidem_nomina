<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiTokenToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('usuario', function (Blueprint $table) {
            // Solo agregar si no existe
            if (!Schema::hasColumn('usuario', 'api_token')) {
                $table->string('api_token', 60)
                      ->unique()
                      ->nullable()
                      ->after('password');
            }
        });
    }

    public function down()
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
}
