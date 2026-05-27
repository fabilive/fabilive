<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->id();
                $table->string('subject')->nullable();
                $table->integer('sent_user')->nullable();
                $table->integer('recieved_user')->nullable();
                $table->text('message')->nullable();
                $table->timestamps();
            });
        }
        
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (!Schema::hasColumn('messages', 'conversation_id')) {
                    $table->integer('conversation_id')->nullable();
                }
                if (!Schema::hasColumn('messages', 'sent_user')) {
                    $table->integer('sent_user')->nullable();
                }
                if (!Schema::hasColumn('messages', 'recieved_user')) {
                    $table->integer('recieved_user')->nullable();
                }
            });
        } else {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->integer('conversation_id')->nullable();
                $table->text('message')->nullable();
                $table->integer('sent_user')->nullable();
                $table->integer('recieved_user')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
        // We will not drop messages table completely to preserve other system's chats,
        // but typically you would drop the columns added.
    }
};
