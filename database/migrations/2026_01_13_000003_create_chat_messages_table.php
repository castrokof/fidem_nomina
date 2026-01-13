<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->enum('sender_type', ['user', 'patient', 'ai'])->default('user');
            $table->text('message');
            $table->text('ai_response')->nullable();
            $table->boolean('is_ai_message')->default(false);
            $table->unsignedBigInteger('parent_message_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('parent_message_id')->references('id')->on('chat_messages')->onDelete('set null');

            // Índices
            $table->index('chat_id');
            $table->index(['chat_id', 'created_at']);
            $table->index('sender_type');
            $table->index('is_ai_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}
