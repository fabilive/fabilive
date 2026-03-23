<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pages')) {
            Schema::table('pages', function (Blueprint $table) {
                if (!Schema::hasColumn('pages', 'footer')) {
                    $table->tinyInteger('footer')->default(0);
                }
                if (!Schema::hasColumn('pages', 'photo')) {
                    $table->string('photo')->nullable();
                }
            });
        }

        if (DB::table('pages')->count() == 0) {
            DB::table('pages')->insert([
                [
                    'title' => 'Terms & Conditions',
                    'slug' => 'terms-and-conditions',
                    'details' => 'Place your terms and conditions here.',
                    'footer' => 1
                ],
                [
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy-policy',
                    'details' => 'Place your privacy policy here.',
                    'footer' => 1
                ]
            ]);
        }
    }

    public function down(): void {}
};
