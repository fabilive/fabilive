<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'referral_name')) {
                    $table->string('referral_name')->nullable();
                }
                if (!Schema::hasColumn('users', 'reff')) {
                    $table->unsignedBigInteger('reff')->default(0);
                }
            });
        }
    }

    public function down(): void {}
};
