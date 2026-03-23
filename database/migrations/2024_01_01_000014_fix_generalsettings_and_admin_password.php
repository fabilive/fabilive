<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix General Settings missing columns
        if (Schema::hasTable('generalsettings')) {
            Schema::table('generalsettings', function (Blueprint $table) {
                if (!Schema::hasColumn('generalsettings', 'user_loader')) {
                    $table->string('user_loader')->nullable();
                }
                if (!Schema::hasColumn('generalsettings', 'admin_loader')) {
                    $table->string('admin_loader')->nullable();
                }
                if (!Schema::hasColumn('generalsettings', 'is_affilate')) {
                    $table->tinyInteger('is_affilate')->default(0);
                }
            });
        }

        // 2. Reset Admin Password
        if (Schema::hasTable('admins')) {
            DB::table('admins')->updateOrInsert(
                ['email' => 'hello@fabilive.com'],
                [
                    'name' => 'Admin',
                    'username' => 'admin',
                    'password' => Hash::make('Fabi@123'),
                    'role_id' => 1,
                    'status' => 1
                ]
            );
        }
    }

    public function down(): void {}
};
