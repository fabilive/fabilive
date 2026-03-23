<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin_languages')) {
            Schema::create('admin_languages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('tag')->nullable();
                $table->tinyInteger('is_default')->default(0);
            });
        }

        if (DB::table('admin_languages')->count() == 0) {
            DB::table('admin_languages')->insert([
                [
                    'name' => 'English',
                    'tag' => 'en',
                    'is_default' => 1
                ]
            ]);
        }
    }

    public function down(): void {}
};
