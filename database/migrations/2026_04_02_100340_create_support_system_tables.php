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
        // 1. support_agents
        if (!Schema::hasTable('support_agents')) {
            Schema::create('support_agents', function (Blueprint $table) {
                // Link to 'admins' table
                $table->id();
                $table->unsignedBigInteger('admin_id')->unique();
                $table->boolean('is_online')->default(false);
                $table->integer('max_active_chats')->default(5);
                $table->timestamp('last_seen_at')->nullable();
                $table->timestamps();

                $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            });
        }

        // 2. support_faq_categories
        if (!Schema::hasTable('support_faq_categories')) {
            Schema::create('support_faq_categories', function (Blueprint $table) {
                $table->id();
                $table->enum('context', ['buyer', 'vendor', 'both'])->default('both');
                $table->string('name');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. support_faqs
        if (!Schema::hasTable('support_faqs')) {
            Schema::create('support_faqs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->enum('context', ['buyer', 'vendor', 'both'])->default('both');
                $table->text('question');
                $table->longText('answer_html');
                $table->json('keywords')->nullable(); // For search optimization
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('support_faq_categories')->onDelete('set null');
            });
        }

        // 4. support_bot_rules
        if (!Schema::hasTable('support_bot_rules')) {
            Schema::create('support_bot_rules', function (Blueprint $table) {
                $table->id();
                $table->enum('context', ['buyer', 'vendor', 'both'])->default('both');
                $table->enum('pattern_type', ['keyword', 'regex', 'contains'])->default('contains');
                $table->string('pattern_value');
                $table->text('response_text');
                $table->unsignedBigInteger('suggested_faq_id')->nullable();
                $table->integer('priority')->default(0); // Higher triggers first
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('suggested_faq_id')->references('id')->on('support_faqs')->onDelete('set null');
                $table->index(['context', 'priority']);
            });
        }

        // 5. support_conversations
        if (!Schema::hasTable('support_conversations')) {
            Schema::create('support_conversations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('requester_user_id'); // Link to users table
                $table->enum('context', ['buyer', 'vendor']);
                $table->string('status', 30)->default('bot_active'); // bot_active, waiting_agent, assigned, ended, rated
                $table->unsignedBigInteger('assigned_agent_admin_id')->nullable(); // Link to admins table
                
                $table->timestamp('started_at')->useCurrent();
                $table->timestamp('assigned_at')->nullable();
                $table->timestamp('ended_at')->nullable();
                $table->string('ended_by', 30)->nullable(); // user, agent, system
                $table->boolean('rating_required')->default(true);
                $table->timestamps();

                $table->foreign('requester_user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('assigned_agent_admin_id')->references('id')->on('admins')->onDelete('set null');
                
                $table->index(['status', 'context']);
                $table->index('assigned_agent_admin_id');
            });
        }

        // 6. support_messages
        if (!Schema::hasTable('support_messages')) {
            Schema::create('support_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->enum('sender_type', ['user', 'agent', 'bot', 'system'])->default('user');
                $table->unsignedBigInteger('sender_id')->nullable(); // user_id or admin_id depending on type
                $table->enum('type', ['text', 'image', 'file', 'voice'])->default('text');
                $table->text('body_text')->nullable();
                
                $table->string('attachment_url')->nullable();
                $table->string('attachment_mime', 100)->nullable();
                $table->unsignedBigInteger('attachment_size')->nullable();
                $table->unsignedSmallInteger('voice_duration')->nullable();
                
                $table->timestamps();

                $table->foreign('conversation_id')->references('id')->on('support_conversations')->onDelete('cascade');
                $table->index(['conversation_id', 'created_at']);
            });
        }

        // 7. support_ratings
        if (!Schema::hasTable('support_ratings')) {
            Schema::create('support_ratings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->unsignedBigInteger('agent_admin_id');
                $table->unsignedBigInteger('rater_user_id');
                $table->tinyInteger('rating')->unsigned(); // 1 to 5
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->foreign('conversation_id')->references('id')->on('support_conversations')->onDelete('cascade');
                $table->foreign('agent_admin_id')->references('id')->on('admins')->onDelete('cascade');
                $table->foreign('rater_user_id')->references('id')->on('users')->onDelete('cascade');
                
                $table->unique('conversation_id'); // Prevent double rating
            });
        }

        // 8. support_conversation_events
        if (!Schema::hasTable('support_conversation_events')) {
            Schema::create('support_conversation_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->string('actor_type', 30); // user, agent, system
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('event', 50); // joined, assigned, ended, rated
                $table->json('meta_json')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('conversation_id')->references('id')->on('support_conversations')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_conversation_events');
        Schema::dropIfExists('support_ratings');
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_conversations');
        Schema::dropIfExists('support_bot_rules');
        Schema::dropIfExists('support_faqs');
        Schema::dropIfExists('support_faq_categories');
        Schema::dropIfExists('support_agents');
    }
};
