<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // 1. Ensure blog_categories exists and has data
        if (Schema::hasTable('blog_categories')) {
            if (DB::table('blog_categories')->count() == 0) {
                DB::table('blog_categories')->insert([
                    'name' => 'News',
                    'slug' => 'news',
                    'status' => 1
                ]);
            }
        }

        // 2. Ensure blogs exists and has data
        if (Schema::hasTable('blogs')) {
            if (DB::table('blogs')->count() == 0) {
                $bcat = DB::table('blog_categories')->first();
                $bcat_id = $bcat ? $bcat->id : 1;

                DB::table('blogs')->insert([
                    'category_id' => $bcat_id,
                    'title' => 'Welcome to Fabilive Blog',
                    'slug' => 'welcome-to-fabilive-blog',
                    'details' => 'Welcome to our new website! We are happy to have you here.',
                    'photo' => 'blog_default.jpg', // Placeholder
                    'tags' => 'fabilive,welcome,ecommerce',
                    'views' => 0,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // 3. Ensure pagesettings has blog enabled
        if (Schema::hasTable('pagesettings')) {
            $ps = DB::table('pagesettings')->first();
            if ($ps) {
                DB::table('pagesettings')->where('id', $ps->id)->update([
                    'blog' => 1,
                    'faq' => 1,
                    'contact' => 1
                ]);
            }
        }
    }

    public function down()
    {
        // No down migration needed for restoration
    }
};
