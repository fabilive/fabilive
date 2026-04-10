<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateVendorPricingAndCurrencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Set Flat Rate Fee of 500 for Vendors
        DB::table('generalsettings')->update([
            'fixed_commission' => 500,
            'percentage_commission' => 0
        ]);

        // 2. Normalize CFA and XFA Currencies (Value = 1.0)
        DB::table('currencies')
            ->whereIn('name', ['CFA', 'XFA'])
            ->orWhereIn('sign', ['CFA', 'XFA'])
            ->update(['value' => 1.0]);

        // Ensure at least one default currency has value 1.0 if CFA/XFA are used
        DB::table('currencies')
            ->where('is_default', 1)
            ->update(['value' => 1.0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optional: revert to 0 if needed, but usually we don't revert pricing logic easily
        DB::table('generalsettings')->update([
            'fixed_commission' => 0
        ]);
    }
}
