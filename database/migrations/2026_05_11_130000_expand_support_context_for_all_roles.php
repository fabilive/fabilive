<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Expand support_conversations.context to support rider + admin roles.
     * Add detected_role column for auto-detection tracking.
     */
    public function up(): void
    {
        // MySQL ENUM needs ALTER to add new values
        // Use raw SQL for safe ENUM expansion
        if (Schema::hasTable('support_conversations')) {
            DB::statement("ALTER TABLE `support_conversations` MODIFY COLUMN `context` VARCHAR(20) NOT NULL DEFAULT 'buyer'");
        }

        // Also expand FAQ categories and bot rules context to support all roles
        if (Schema::hasTable('support_faq_categories')) {
            DB::statement("ALTER TABLE `support_faq_categories` MODIFY COLUMN `context` VARCHAR(20) NOT NULL DEFAULT 'both'");
        }

        if (Schema::hasTable('support_faqs')) {
            DB::statement("ALTER TABLE `support_faqs` MODIFY COLUMN `context` VARCHAR(20) NOT NULL DEFAULT 'both'");
        }

        if (Schema::hasTable('support_bot_rules')) {
            DB::statement("ALTER TABLE `support_bot_rules` MODIFY COLUMN `context` VARCHAR(20) NOT NULL DEFAULT 'both'");
        }

        // Add detected_role to conversations for audit trail
        if (Schema::hasTable('support_conversations') && !Schema::hasColumn('support_conversations', 'detected_role')) {
            Schema::table('support_conversations', function (Blueprint $table) {
                $table->string('detected_role', 20)->nullable()->after('context');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('support_conversations') && Schema::hasColumn('support_conversations', 'detected_role')) {
            Schema::table('support_conversations', function (Blueprint $table) {
                $table->dropColumn('detected_role');
            });
        }
    }
};
