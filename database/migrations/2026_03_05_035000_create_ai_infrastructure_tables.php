<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('feature', 50); // assistant, listing_gen, photo_enhance, anti_scam, reputation
            $table->string('provider', 20); // openai, gemini, anthropic
            $table->string('model', 50)->nullable();
            $table->string('input_hash', 64)->nullable(); // SHA-256 of input for dedup
            $table->unsignedInteger('input_tokens')->nullable();
            $table->unsignedInteger('output_tokens')->nullable();
            $table->decimal('cost_usd', 8, 6)->nullable();
            $table->enum('status', ['success', 'error', 'rate_limited', 'blocked'])->default('success');
            $table->text('error_message')->nullable();
            $table->unsignedSmallInteger('response_time_ms')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('feature');
            $table->index(['user_id', 'feature', 'created_at']);
        });

        // Scam signals for AI4
        Schema::create('scam_signals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flagged_user_id')->nullable();
            $table->unsignedBigInteger('flagged_listing_id')->nullable();
            $table->string('signal_type', 50); // suspicious_phone, reused_image, pricing_outlier, off_platform_payment
            $table->string('reason_code', 100);
            $table->text('details')->nullable();
            $table->decimal('risk_score', 5, 2)->default(0);
            $table->enum('review_status', ['pending', 'dismissed', 'confirmed', 'actioned'])->default('pending');
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->timestamps();

            $table->index('flagged_user_id');
            $table->index('flagged_listing_id');
            $table->index('review_status');
        });

        // Reputation badges for AI5
        Schema::create('seller_badges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('badge_type', 50); // fast_responder, honest_pricing, trusted
            $table->decimal('score', 5, 2)->default(0);
            $table->boolean('active')->default(false);
            $table->timestamp('earned_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'badge_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_badges');
        Schema::dropIfExists('scam_signals');
        Schema::dropIfExists('ai_audit_logs');
    }
};
