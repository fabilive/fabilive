<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminAndRiderToSupportConversations extends Migration
{
    public function up()
    {
        Schema::table('support_conversations', function (Blueprint $table) {
            // Add columns for non-standard user roles
            $table->unsignedInteger('admin_id')->nullable()->after('requester_user_id');
            $table->unsignedInteger('rider_id')->nullable()->after('admin_id');

            // Add foreign keys for integrity (if tables exist)
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');
            
            // Make requester_user_id nullable (it should be already, but ensuring)
            $table->unsignedInteger('requester_user_id')->nullable()->change();
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
