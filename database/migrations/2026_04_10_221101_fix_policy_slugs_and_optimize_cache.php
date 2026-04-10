<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Fix Slugs (Replace spaces with hyphens for better URL compatibility)
        $pages = DB::table('pages')->get(['id', 'slug']);
        
        foreach ($pages as $page) {
            if (str_contains($page->slug, ' ')) {
                $newSlug = str_replace(' ', '-', $page->slug);
                DB::table('pages')->where('id', $page->id)->update(['slug' => $newSlug]);
            }
        }

        // 2. Ensure Display flags are definitely set
        DB::table('pages')->where('id', '>', 0)->update([
            'header' => 1,
            'footer' => 1
        ]);

        // 3. Clear ALL levels of cache
        Cache::flush();
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse logic needed
    }
};
