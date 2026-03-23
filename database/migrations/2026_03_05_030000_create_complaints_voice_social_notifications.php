<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A4: Buyer complaint tickets
        if (!Schema::hasTable('complaints')) {
            Schema::create('complaints', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('order_id')->nullable();
                $table->string('subject');
                $table->text('description');
                $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
                $table->text('admin_response')->nullable();
                $table->unsignedBigInteger('assigned_admin_id')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('order_id');
                $table->index('status');
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            });
        }

        // A5: Voice notes support — add type and voice fields to messages
        if (Schema::hasTable('messages') && !Schema::hasColumn('messages', 'type')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->enum('type', ['text', 'voice', 'image'])->default('text');
                $table->string('voice_url')->nullable();
                $table->unsignedSmallInteger('voice_duration')->nullable();
            });
        }

        // A6: Seller social — likes table
        if (!Schema::hasTable('seller_likes')) {
            Schema::create('seller_likes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('vendor_id');
                $table->timestamps();

                $table->unique(['user_id', 'vendor_id']);
                $table->index('vendor_id');
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // A7: Notification preferences
        if (!Schema::hasTable('notification_preferences')) {
            Schema::create('notification_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('notification_type', 50); 
                $table->boolean('email_enabled')->default(true);
                $table->boolean('push_enabled')->default(true);
                $table->boolean('in_app_enabled')->default(true);
                $table->string('quiet_hours_start', 5)->nullable(); // HH:MM
                $table->string('quiet_hours_end', 5)->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'notification_type']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('notification_logs')) {
            Schema::create('notification_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('notification_type', 50);
                $table->string('channel', 20); // email, push, in_app
                $table->timestamp('sent_at');
                $table->timestamps();

                $table->index(['user_id', 'notification_type', 'sent_at']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('seller_likes');
        Schema::dropIfExists('complaints');

        if (Schema::hasColumn('messages', 'type')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropColumn(['type', 'voice_url', 'voice_duration']);
            });
        }
    }
};
