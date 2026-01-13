<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('participant_id');
            $table->enum('participant_type', ['user', 'patient'])->default('user');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');

            // Índices
            $table->index(['chat_id', 'participant_id', 'participant_type'], 'chat_participant_idx');
            $table->index('participant_type');

            // Evitar duplicados
            $table->unique(['chat_id', 'participant_id', 'participant_type'], 'chat_participant_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_participants');
    }
}
