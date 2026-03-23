<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pagesettings')) {
            Schema::create('pagesettings', function (Blueprint $blueprint) {
                $blueprint->id();
                $blueprint->string('contact_email')->nullable();
                $blueprint->string('street')->nullable();
                $blueprint->string('phone')->nullable();
                $blueprint->string('fax')->nullable();
                $blueprint->string('email')->nullable();
                $blueprint->string('site')->nullable();
                $blueprint->string('best_seller_banner')->nullable();
                $blueprint->string('best_seller_banner_link')->nullable();
                $blueprint->string('big_save_banner')->nullable();
                $blueprint->string('big_save_banner_link')->nullable();
                $blueprint->string('best_seller_banner1')->nullable();
                $blueprint->string('best_seller_banner_link1')->nullable();
                $blueprint->string('big_save_banner1')->nullable();
                $blueprint->string('big_save_banner_link1')->nullable();
                $blueprint->text('partners')->nullable();
                $blueprint->text('bottom_small')->nullable();
                $blueprint->string('rightbanner1')->nullable();
                $blueprint->string('rightbanner2')->nullable();
                $blueprint->string('rightbannerlink1')->nullable();
                $blueprint->string('rightbannerlink2')->nullable();
                $blueprint->tinyInteger('home')->default(1);
                $blueprint->tinyInteger('blog')->default(1);
                $blueprint->tinyInteger('faq')->default(1);
                $blueprint->tinyInteger('contact')->default(1);
                $blueprint->tinyInteger('category')->default(1);
                $blueprint->tinyInteger('arrival_section')->default(1);
                $blueprint->tinyInteger('our_services')->default(1);
                $blueprint->tinyInteger('popular_products')->default(1);
                $blueprint->tinyInteger('third_left_banner')->default(1);
                $blueprint->tinyInteger('slider')->default(1);
                $blueprint->tinyInteger('flash_deal')->default(1);
                $blueprint->tinyInteger('deal_of_the_day')->default(1);
                $blueprint->tinyInteger('best_sellers')->default(1);
                $blueprint->tinyInteger('partner')->default(1);
                $blueprint->tinyInteger('top_big_trending')->default(1);
                $blueprint->tinyInteger('top_brand')->default(1);
                $blueprint->string('big_save_banner_subtitle')->nullable();
                $blueprint->string('big_save_banner_title')->nullable();
                $blueprint->text('big_save_banner_text')->nullable();
                $blueprint->string('top_banner')->nullable();
                $blueprint->string('large_banner')->nullable();
                $blueprint->string('best_selling')->nullable();
                $blueprint->string('bottom_banner')->nullable();
                $blueprint->tinyInteger('newsletter')->default(1);
            });
            
            DB::table('pagesettings')->insert(['home' => 1, 'blog' => 1, 'faq' => 1, 'contact' => 1]);
        }

        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->id();
                $table->string('language')->unique();
                $table->string('name')->nullable();
                $table->string('file')->nullable();
                $table->tinyInteger('rtl')->default(0);
                $table->tinyInteger('is_default')->default(0);
            });
            
            DB::table('languages')->insert([
                'language' => 'English',
                'name' => 'English',
                'file' => '1564414153.json',
                'is_default' => 1
            ]);
        }

        if (!Schema::hasTable('counters')) {
            Schema::create('counters', function (Blueprint $table) {
                $table->id();
                $table->integer('referral')->default(0);
                $table->integer('total_count')->default(0);
                $table->integer('todays_count')->default(0);
                $table->string('today')->nullable();
            });
            
            DB::table('counters')->insert(['total_count' => 0]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pagesettings');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('counters');
    }
};
