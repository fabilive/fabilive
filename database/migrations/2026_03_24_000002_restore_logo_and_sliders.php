<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Restore Logo and Favicon in generalsettings
        if (Schema::hasTable('generalsettings')) {
            $gs = DB::table('generalsettings')->first();
            if ($gs) {
                DB::table('generalsettings')->where('id', $gs->id)->update([
                    'logo' => '1748411808Original-Logo001png.png',
                    'footer_logo' => '1580538630footer-logo.png',
                    'favicon' => '1580538630favicon.png',
                    'is_capcha' => 1,
                    'is_maintainance' => 0,
                    'capcha_site_key' => '6Lfb9fkaAAAAAE08o9-G0B-p2eL6xN8j3X9-xX_x',
                    'capcha_secret_key' => '6Lfb9fkaAAAAAIn-M8j3X9-xX_x',
                ]);
            } else {
                // Emergency Insert if table is empty
                DB::table('generalsettings')->insert([
                    'logo' => '1748411808Original-Logo001png.png',
                    'footer_logo' => '1580538630footer-logo.png',
                    'favicon' => '1580538630favicon.png',
                    'is_capcha' => 1,
                    'is_maintainance' => 0,
                    'capcha_site_key' => '6Lfb9fkaAAAAAE08o9-G0B-p2eL6xN8j3X9-xX_x',
                    'capcha_secret_key' => '6Lfb9fkaAAAAAIn-M8j3X9-xX_x',
                ]);
            }
        }

        // 2. Restore Sliders if empty
        if (Schema::hasTable('sliders')) {
            if (DB::table('sliders')->count() == 0) {
                DB::table('sliders')->insert([
                    [
                        'photo' => '1580538630slider1.jpg',
                        'title_text' => 'Welcome to Fabilive',
                        'details_text' => 'Discover our exclusive collection.',
                        'position' => 'left',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'photo' => '1580538630slider2.jpg',
                        'title_text' => 'Premium Quality',
                        'details_text' => 'We offer the best products for you.',
                        'position' => 'center',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'photo' => '1580538630slider3.jpg',
                        'title_text' => 'Fast Delivery',
                        'details_text' => 'Get your orders delivered instantly.',
                        'position' => 'right',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
            }
        }
    }

    public function down(): void
    {
    }
};
