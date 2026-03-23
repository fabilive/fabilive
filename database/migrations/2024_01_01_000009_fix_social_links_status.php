<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('social_links')) {
            Schema::table('social_links', function (Blueprint $table) {
                if (!Schema::hasColumn('social_links', 'status')) {
                    $table->tinyInteger('status')->default(1);
                }
            });
        }

        if (DB::table('social_links')->where('user_id', 0)->count() == 0) {
            DB::table('social_links')->insert([
                [
                    'user_id' => 0,
                    'link' => 'https://facebook.com',
                    'icon' => 'fab fa-facebook-f',
                    'status' => 1
                ],
                [
                    'user_id' => 0,
                    'link' => 'https://twitter.com',
                    'icon' => 'fab fa-twitter',
                    'status' => 1
                ],
                [
                    'user_id' => 0,
                    'link' => 'https://linkedin.com',
                    'icon' => 'fab fa-linkedin-in',
                    'status' => 1
                ]
            ]);
        }
    }

    public function down(): void {}
};
