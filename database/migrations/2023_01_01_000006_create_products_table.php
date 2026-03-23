<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->default(0);
                $table->unsignedBigInteger('category_id')->nullable();
                $table->unsignedBigInteger('subcategory_id')->nullable();
                $table->unsignedBigInteger('childcategory_id')->nullable();
                $table->string('product_type')->nullable();
                $table->string('product_location')->nullable();
                $table->string('product_city')->nullable();
                $table->text('affiliate_link')->nullable();
                $table->string('sku')->nullable();
                $table->unsignedBigInteger('country_id')->nullable();
                $table->text('attributes')->nullable();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('photo')->nullable();
                $table->text('size')->nullable();
                $table->text('size_qty')->nullable();
                $table->text('size_price')->nullable();
                $table->text('color')->nullable();
                $table->text('details')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->decimal('previous_price', 15, 2)->nullable();
                $table->integer('stock')->nullable();
                $table->text('policy')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->integer('views')->default(0);
                $table->text('tags')->nullable();
                $table->tinyInteger('featured')->default(0);
                $table->tinyInteger('best')->default(0);
                $table->tinyInteger('top')->default(0);
                $table->tinyInteger('hot')->default(0);
                $table->tinyInteger('latest')->default(0);
                $table->tinyInteger('big')->default(0);
                $table->tinyInteger('trending')->default(0);
                $table->tinyInteger('sale')->default(0);
                $table->text('features')->nullable();
                $table->text('colors')->nullable();
                $table->string('product_condition')->nullable();
                $table->string('ship')->nullable();
                $table->text('meta_tag')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('youtube')->nullable();
                $table->string('type')->default('Physical');
                $table->string('file')->nullable();
                $table->text('license')->nullable();
                $table->text('license_qty')->nullable();
                $table->text('link')->nullable();
                $table->string('platform')->nullable();
                $table->string('region')->nullable();
                $table->string('licence_type')->nullable();
                $table->string('measure')->nullable();
                $table->string('discount_date')->nullable();
                $table->tinyInteger('is_discount')->default(0);
                $table->text('whole_sell_qty')->nullable();
                $table->text('whole_sell_discount')->nullable();
                $table->integer('catalog_id')->default(0);
                
                // Analytics/Counts
                $table->integer('flash_count')->default(0);
                $table->integer('hot_count')->default(0);
                $table->integer('new_count')->default(0);
                $table->integer('sale_count')->default(0);
                $table->integer('best_seller_count')->default(0);
                $table->integer('popular_count')->default(0);
                $table->integer('top_rated_count')->default(0);
                $table->integer('big_save_count')->default(0);
                $table->integer('trending_count')->default(0);
                $table->integer('page_count')->default(0);
                $table->integer('seller_product_count')->default(0);
                $table->integer('wishlist_count')->default(0);
                $table->integer('vendor_page_count')->default(0);
                $table->integer('min_price')->default(0);
                $table->integer('max_price')->default(0);
                $table->integer('product_page')->default(0);
                $table->integer('post_count')->default(0);
                
                $table->integer('minimum_qty')->default(1);
                $table->tinyInteger('preordered')->default(0);
                $table->text('color_all')->nullable();
                $table->text('size_all')->nullable();
                $table->tinyInteger('stock_check')->default(0);
                $table->decimal('delivery_fee', 15, 2)->default(0);
                $table->string('delivery_unit')->nullable();
                $table->string('product_servicearea')->nullable();
                $table->text('cross_products')->nullable();
                $table->string('3d_model')->nullable();
                
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
