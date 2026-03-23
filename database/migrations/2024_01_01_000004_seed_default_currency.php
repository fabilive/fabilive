<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('currencies')) {
            if (DB::table('currencies')->count() == 0) {
                DB::table('currencies')->insert([
                    'name' => 'USD',
                    'sign' => '$',
                    'value' => 1.0,
                    'is_default' => 1
                ]);
            } elseif (DB::table('currencies')->where('is_default', 1)->count() == 0) {
                DB::table('currencies')->limit(1)->update(['is_default' => 1]);
            }
        }
    }

    public function down(): void {}
};
