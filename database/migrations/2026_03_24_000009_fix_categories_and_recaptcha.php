<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Map Category Images (Matched artifacts exactly)
        $mappings = [
            'electronics' => 'category_electronic_1774125726419.png',
            'fashion'    => 'category_fashion_1774125742762.png',
            'smartphone' => 'category_smartphone_1774125778584.png',
            'sport'      => 'category_sport_1774125795641.png',
            'jewelry'    => 'category_jewelry_1774125811375.png',
            'camera'     => 'category_camera_1774125762535.png',
            'surveillance' => 'category_surveillance_1774125828972.png',
        ];

        foreach ($mappings as $slug => $photo) {
            DB::table('categories')->where('slug', $slug)->update(['photo' => $photo]);
        }

        // 2. Force-seed General Settings & reCAPTCHA Activation
        // Using Google Recaptcha v2 test keys (always pass)
        $test_site_key = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
        $test_secret   = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFojJ4WifJWeE';

        $gs = DB::table('generalsettings')->first();
        if (!$gs) {
            DB::table('generalsettings')->insert([
                'title' => 'Fabilive',
                'is_capcha' => 1,
                'capcha_site_key' => $test_site_key,
                'capcha_secret_key' => $test_secret,
                'from_email' => 'support@fabilive.com',
                'from_name' => 'Fabilive',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('generalsettings')->where('id', $gs->id)->update([
                'is_capcha' => 1,
                'capcha_site_key' => $test_site_key,
                'capcha_secret_key' => $test_secret,
            ]);
        }

        // 3. Clear Cache to reflect new settings immediately
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    }

    public function down(): void {}
};
