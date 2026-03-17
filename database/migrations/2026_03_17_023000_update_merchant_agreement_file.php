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
        DB::table('agreements')
            ->where('type', 'Fabilive_Sub_merchant_Agreement')
            ->update(['image' => 'assets/images/submerchantagreement/1773710500SUB-MERCHANT-AGREEMENT.pdf']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No simple way to reverse without knowing the previous filename,
        // but typically we don't need to revert file updates.
    }
};
