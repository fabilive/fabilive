<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create seotools table if it doesn't exist
        if (!Schema::hasTable('seotools')) {
            Schema::create('seotools', function (Blueprint $table) {
                $table->id();
                $table->text('google_analytics')->nullable();
                $table->text('facebook_pixel')->nullable();
                $table->text('meta_keys')->nullable();
                $table->text('meta_description')->nullable();
            });
        }

        // 2. Ensure robust columns for socialsettings
        Schema::table('socialsettings', function (Blueprint $table) {
            $cols = [
                'facebook', 'twitter', 'gplus', 'linkedin', 'dribble', 'f_status', 't_status',
                'g_status', 'l_status', 'd_status', 'f_check', 'g_check', 'fclient_id',
                'fclient_secret', 'fredirect', 'gclient_id', 'gclient_secret', 'gredirect'
            ];
            foreach ($cols as $col) {
                if (!Schema::hasColumn('socialsettings', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });

        // 3. Ensure robust columns for fonts
        Schema::table('fonts', function (Blueprint $table) {
            if (!Schema::hasColumn('fonts', 'font_value')) {
                $table->string('font_value')->nullable();
            }
        });

        // 4. Insert default rows for all settings tables if empty
        $settingsTables = [
            'generalsettings' => ['title' => 'Fabilive', 'is_maintain' => 0],
            'pagesettings' => ['contact_success' => 'Thank you for contacting us.'],
            'seotools' => ['meta_keys' => 'fabilive, ecommerce'],
            'socialsettings' => ['facebook' => 'https://facebook.com'],
            'fonts' => ['font_family' => 'Open Sans', 'font_value' => 'Open+Sans', 'is_default' => 1]
        ];

        foreach ($settingsTables as $table => $data) {
            if (Schema::hasTable($table) && DB::table($table)->count() == 0) {
                DB::table($table)->insert($data);
            }
        }
    }

    public function down(): void
    {
        // Not dropping these as they are intended base tables
    }
};
