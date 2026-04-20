<?php

use Illuminate\Database\Migrations\Migration;

class AddAnuladoToConsentimientosEstadoEnum extends Migration
{
    public function up()
    {
        \DB::statement("ALTER TABLE consentimientos_informados MODIFY COLUMN estado ENUM('pendiente','en_proceso','firmado','cancelado','anulado') NOT NULL DEFAULT 'pendiente'");
    }

    public function down()
    {
        \DB::statement("ALTER TABLE consentimientos_informados MODIFY COLUMN estado ENUM('pendiente','en_proceso','firmado','cancelado') NOT NULL DEFAULT 'pendiente'");
    }
}
