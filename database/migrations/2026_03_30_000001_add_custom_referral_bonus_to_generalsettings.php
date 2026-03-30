<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            if (!Schema::hasColumn('generalsettings', 'custom_referral_bonus')) {
                $table->unsignedInteger('custom_referral_bonus')->default(500)->after('referral_bonus');
            }
        });

        // Set default value for existing row
        DB::table('generalsettings')->whereId(1)->update([
            'custom_referral_bonus' => 500,
        ]);
    }

    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('custom_referral_bonus');
        });
    }
};
