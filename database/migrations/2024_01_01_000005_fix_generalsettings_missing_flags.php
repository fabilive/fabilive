<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $flags = [
                'is_popup', 'is_smtp', 'is_newsletter', 'is_cookie', 'is_loader',
                'is_admin_loader', 'is_user_loader', 'is_vendor_loader',
                'is_talkto', 'is_disqus', 'is_language', 'is_currency'
            ];

            foreach ($flags as $flag) {
                if (!Schema::hasColumn('generalsettings', $flag)) {
                    $table->tinyInteger($flag)->default(0);
                }
            }
        });

        // Update default values for the specific flags we know exist in the UI
        DB::table('generalsettings')->update([
            'is_popup' => 0,
            'is_smtp' => 0,
            'is_loader' => 1
        ]);
    }

    public function down(): void {}
};
