<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateVendorOrdersStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE vendor_orders MODIFY COLUMN status ENUM('pending','processing','completed','declined','on delivery', 'ready') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE vendor_orders MODIFY COLUMN status ENUM('pending','processing','completed','declined','on delivery') NOT NULL DEFAULT 'pending'");
    }
}
