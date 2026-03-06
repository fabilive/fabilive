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
        if (!Schema::hasTable('ai_audit_logs')) {
            Schema::create('ai_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable();
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
        }

        // Scam signals for AI4
        if (!Schema::hasTable('scam_signals')) {
            Schema::create('scam_signals', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('flagged_user_id')->nullable();
                $table->unsignedBigInteger('flagged_listing_id')->nullable();
                $table->string('signal_type', 50); // duplicate_listing, suspicious_price, forbidden_words
                $table->float('probability')->default(0);
                $table->text('details')->nullable();
                $table->boolean('requires_review')->default(true);
                $table->string('action_taken')->default('none');
                $table->timestamps();

                $table->index('flagged_user_id');
                $table->index('flagged_listing_id');
            });
        }

        // Reputation scores for AI5
        if (!Schema::hasTable('reputation_scores')) {
            Schema::create('reputation_scores', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->unique();
                $table->integer('points')->default(100);
                $table->string('level_badge')->default('Newcomer');
                $table->float('trust_score')->default(5.0);
                $table->json('metrics')->nullable(); // detail breakdowns
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reputation_scores');
        Schema::dropIfExists('scam_signals');
        Schema::dropIfExists('ai_audit_logs');
    }
};
