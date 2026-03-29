<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToPaliativos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('estados', function (Blueprint $table) {
            $table->index(['documento', 'id_estado'], 'idx_documento_estado');
        });

        Schema::table('bdpaliativos', function (Blueprint $table) {
            $table->index('document',    'idx_document');
            $table->index('state',       'idx_state');
            $table->index('profesional', 'idx_profesional');
            $table->index('future1',     'idx_future1');
        });
    }

    public function down(): void
    {
        Schema::table('estados', function (Blueprint $table) {
            $table->dropIndex('idx_documento_estado');
        });

        Schema::table('bdpaliativos', function (Blueprint $table) {
            $table->dropIndex('idx_document');
            $table->dropIndex('idx_state');
            $table->dropIndex('idx_profesional');
            $table->dropIndex('idx_future1');
        });
    }
}
