<?php

use Illuminate\Database\Migrations\Migration;
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
        DB::table('agreements')
            ->where('type', 'Fabilive_Sub_merchant_Agreement')
            ->update(['image' => 'assets/images/submerchantagreement/1773725000SubMerchantAgreement.pdf']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No revert necessary
    }
};
