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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'photo')) $table->string('photo')->nullable();
            if (!Schema::hasColumn('users', 'zip')) $table->string('zip')->nullable();
            if (!Schema::hasColumn('users', 'city_id')) $table->unsignedBigInteger('city_id')->nullable();
            if (!Schema::hasColumn('users', 'state_id')) $table->unsignedBigInteger('state_id')->nullable();
            if (!Schema::hasColumn('users', 'country')) $table->string('country')->nullable();
            if (!Schema::hasColumn('users', 'country_id')) $table->unsignedBigInteger('country_id')->nullable();
            if (!Schema::hasColumn('users', 'address')) $table->string('address')->nullable();
            if (!Schema::hasColumn('users', 'phone')) $table->string('phone')->nullable();
            if (!Schema::hasColumn('users', 'fax')) $table->string('fax')->nullable();
            if (!Schema::hasColumn('users', 'affilate_code')) $table->string('affilate_code')->nullable();
            if (!Schema::hasColumn('users', 'verification_link')) $table->string('verification_link')->nullable();
            if (!Schema::hasColumn('users', 'shop_name')) $table->string('shop_name')->nullable();
            if (!Schema::hasColumn('users', 'owner_name')) $table->string('owner_name')->nullable();
            if (!Schema::hasColumn('users', 'shop_number')) $table->string('shop_number')->nullable();
            if (!Schema::hasColumn('users', 'shop_address')) $table->string('shop_address')->nullable();
            if (!Schema::hasColumn('users', 'reg_number')) $table->string('reg_number')->nullable();
            if (!Schema::hasColumn('users', 'shop_message')) $table->text('shop_message')->nullable();
            if (!Schema::hasColumn('users', 'is_vendor')) $table->integer('is_vendor')->default(0);
            if (!Schema::hasColumn('users', 'shop_details')) $table->text('shop_details')->nullable();
            if (!Schema::hasColumn('users', 'shop_image')) $table->string('shop_image')->nullable();
            if (!Schema::hasColumn('users', 'shipping_cost')) $table->decimal('shipping_cost', 15, 4)->default(0);
            if (!Schema::hasColumn('users', 'date')) $table->date('date')->nullable();
            if (!Schema::hasColumn('users', 'mail_sent')) $table->tinyInteger('mail_sent')->default(0);
            if (!Schema::hasColumn('users', 'email_verified')) $table->string('email_verified')->default('No');
            if (!Schema::hasColumn('users', 'current_balance')) $table->decimal('current_balance', 15, 4)->default(0);
            if (!Schema::hasColumn('users', 'email_token')) $table->string('email_token')->nullable();
            if (!Schema::hasColumn('users', 'reward')) $table->integer('reward')->default(0);
            if (!Schema::hasColumn('users', 'national_id_front_image')) $table->string('national_id_front_image')->nullable();
            if (!Schema::hasColumn('users', 'national_id_back_image')) $table->string('national_id_back_image')->nullable();
            if (!Schema::hasColumn('users', 'license_image')) $table->string('license_image')->nullable();
            if (!Schema::hasColumn('users', 'vendor_status')) $table->string('vendor_status')->default('pending');
            if (!Schema::hasColumn('users', 'vendor_rejection_reason')) $table->text('vendor_rejection_reason')->nullable();
            if (!Schema::hasColumn('users', 'vendor_approved_at')) $table->timestamp('vendor_approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not dropping base columns
    }
};
