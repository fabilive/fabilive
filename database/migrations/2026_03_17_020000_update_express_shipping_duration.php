<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('shippings')
            ->where('title', 'like', '%Express%')
            ->update(['subtitle' => 'within 24 hours']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('shippings')
            ->where('title', 'like', '%Express%')
            ->update(['subtitle' => '5-6 days']);
    }
};
