<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminAndRiderToSupportConversations extends Migration
{
    public function up()
    {
        // Recover from a partially applied migration by dropping if exists
        try {
            Schema::table('support_conversations', function (Blueprint $table) {
                if (Schema::hasColumn('support_conversations', 'admin_id')) {
                    try { $table->dropForeign(['admin_id']); } catch (\Exception $e) {}
                    $table->dropColumn('admin_id');
                }
            });
        } catch (\Exception $e) {}
        
        try {
            Schema::table('support_conversations', function (Blueprint $table) {
                if (Schema::hasColumn('support_conversations', 'rider_id')) {
                    try { $table->dropForeign(['rider_id']); } catch (\Exception $e) {}
                    $table->dropColumn('rider_id');
                }
            });
        } catch (\Exception $e) {}

        Schema::table('support_conversations', function (Blueprint $table) {
            // Add columns for non-standard user roles
            $table->unsignedBigInteger('admin_id')->nullable()->after('requester_user_id');
            $table->unsignedBigInteger('rider_id')->nullable()->after('admin_id');

            // Add foreign keys for integrity (if tables exist)
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');
            
            // Make requester_user_id nullable (it should be already, but ensuring)
            $table->unsignedBigInteger('requester_user_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('support_conversations', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['rider_id']);
            $table->dropColumn(['admin_id', 'rider_id']);
        });
    }
}
