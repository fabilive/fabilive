<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix Attributes Table (Ensure Polymorphic columns)
        if (!Schema::hasTable('attributes')) {
            Schema::create('attributes', function (Blueprint $table) {
                $table->id();
                $table->string('attributable_type');
                $table->integer('attributable_id');
                $table->string('name');
                $table->string('input_name');
                $table->timestamps();
            });
        } else {
            Schema::table('attributes', function (Blueprint $table) {
                if (!Schema::hasColumn('attributes', 'attributable_type')) {
                    $table->string('attributable_type')->after('id');
                }
                if (!Schema::hasColumn('attributes', 'attributable_id')) {
                    $table->integer('attributable_id')->after('attributable_type');
                }
            });
        }

        // 2. Clear Cache again to ensure GS and SEO are fresh
        try {
            DB::table('generalsettings')->update(['is_capcha' => 1]); // Ensure reCAPTCHA is on
        } catch (\Exception $e) {}
    }

    public function down(): void {}
};
