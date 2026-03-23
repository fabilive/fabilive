<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add vendor onboarding status + rejection reason
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'vendor_status')) {
                    $table->enum('vendor_status', ['pending_docs', 'pending_approval', 'approved', 'rejected'])
                        ->default('pending_docs')
                        ;
                }
                if (!Schema::hasColumn('users', 'vendor_rejection_reason')) {
                    $table->text('vendor_rejection_reason')->nullable();
                }
                if (!Schema::hasColumn('users', 'vendor_approved_at')) {
                    $table->timestamp('vendor_approved_at')->nullable();
                }
            });
        }

        // Add rider onboarding status (A3 prep)
        if (Schema::hasTable('riders')) {
            Schema::table('riders', function (Blueprint $table) {
                if (!Schema::hasColumn('riders', 'onboarding_status')) {
                    $table->enum('onboarding_status', ['pending_docs', 'pending_approval', 'approved', 'rejected'])
                        ->default('pending_docs');
                }
                if (!Schema::hasColumn('riders', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable();
                }
                if (!Schema::hasColumn('riders', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'vendor_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['vendor_status', 'vendor_rejection_reason', 'vendor_approved_at']);
            });
        }

        if (Schema::hasColumn('riders', 'onboarding_status')) {
            Schema::table('riders', function (Blueprint $table) {
                $table->dropColumn(['onboarding_status', 'rejection_reason', 'approved_at']);
            });
        }
    }
};
