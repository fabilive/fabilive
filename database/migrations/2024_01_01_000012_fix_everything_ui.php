<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix General Settings (Logos, Icons, etc.)
        $settingsData = [
            'logo' => '1580538562logo.png',
            'favicon' => '1572146352favicon.png',
            'footer_logo' => '1580538630footer-logo.png',
            'invoice_logo' => '1580538562logo.png',
            'is_affilate' => 1,
            'is_capcha' => 0,
        ];

        // Only add loader fields if they exist
        if (Schema::hasColumn('generalsettings', 'user_loader')) {
            $settingsData['user_loader'] = 'spinner.gif';
        }
        if (Schema::hasColumn('generalsettings', 'admin_loader')) {
            $settingsData['admin_loader'] = 'spinner.gif';
        }

        DB::table('generalsettings')->update($settingsData);

        // 2. Fix Dropdowns (Header/Status)
        DB::table('pages')->update(['header' => 1]);
        DB::table('categories')->update(['status' => 1, 'is_featured' => 1]);
        DB::table('subcategories')->update(['status' => 1]);
        DB::table('childcategories')->update(['status' => 1]);
        DB::table('products')->update(['status' => 1]);

        // 3. Fix Placeholder images for sliders/arrivals to avoid broken images
        DB::table('sliders')->update(['photo' => 'noimage.png']);
        DB::table('arrival_sections')->update(['photo' => 'noimage.png']);

        // 4. Ensure Page Settings (ps) has features enabled
        if (Schema::hasTable('pagesettings')) {
            DB::table('pagesettings')->update([
                'home' => 1,
                'blog' => 1,
                'faq' => 1,
                'contact' => 1
            ]);
        }

        // 5. Set referral_name for existing users if missing
        $users = DB::table('users')->whereNull('referral_name')->get();
        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'referral_name' => strtolower(str_replace(' ', '', $user->name)) . $user->id
            ]);
        }
    }

    public function down(): void {}
};
