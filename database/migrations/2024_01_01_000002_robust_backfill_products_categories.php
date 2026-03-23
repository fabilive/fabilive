<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ensure PRODUCTS columns
        Schema::table('products', function (Blueprint $table) {
            $productColumns = [
                'user_id' => 'unsignedBigInteger',
                'category_id' => 'unsignedBigInteger',
                'subcategory_id' => 'unsignedBigInteger',
                'childcategory_id' => 'unsignedBigInteger',
                'product_type' => 'string',
                'product_location' => 'string',
                'product_city' => 'string',
                'affiliate_link' => 'text',
                'sku' => 'string',
                'country_id' => 'unsignedBigInteger',
                'attributes' => 'text',
                'photo' => 'string',
                'size' => 'text',
                'size_qty' => 'text',
                'size_price' => 'text',
                'color' => 'text',
                'details' => 'text',
                'price' => 'decimal:15,2',
                'previous_price' => 'decimal:15,2',
                'stock' => 'integer',
                'policy' => 'text',
                'status' => 'tinyInteger',
                'views' => 'integer',
                'tags' => 'text',
                'featured' => 'tinyInteger',
                'best' => 'tinyInteger',
                'top' => 'tinyInteger',
                'hot' => 'tinyInteger',
                'latest' => 'tinyInteger',
                'big' => 'tinyInteger',
                'trending' => 'tinyInteger',
                'sale' => 'tinyInteger',
                'features' => 'text',
                'colors' => 'text',
                'product_condition' => 'string',
                'ship' => 'string',
                'meta_tag' => 'text',
                'meta_description' => 'text',
                'youtube' => 'string',
                'type' => 'string',
                'file' => 'string',
                'license' => 'text',
                'license_qty' => 'text',
                'link' => 'text',
                'platform' => 'string',
                'region' => 'string',
                'licence_type' => 'string',
                'measure' => 'string',
                'discount_date' => 'string',
                'is_discount' => 'tinyInteger',
                'whole_sell_qty' => 'text',
                'whole_sell_discount' => 'text',
                'catalog_id' => 'integer',
                'flash_count' => 'integer',
                'hot_count' => 'integer',
                'new_count' => 'integer',
                'sale_count' => 'integer',
                'best_seller_count' => 'integer',
                'popular_count' => 'integer',
                'top_rated_count' => 'integer',
                'big_save_count' => 'integer',
                'trending_count' => 'integer',
                'page_count' => 'integer',
                'seller_product_count' => 'integer',
                'wishlist_count' => 'integer',
                'vendor_page_count' => 'integer',
                'min_price' => 'integer',
                'max_price' => 'integer',
                'product_page' => 'integer',
                'post_count' => 'integer',
                'minimum_qty' => 'integer',
                'preordered' => 'tinyInteger',
                'color_all' => 'text',
                'size_all' => 'text',
                'stock_check' => 'tinyInteger',
                'delivery_fee' => 'decimal:15,2',
                'delivery_unit' => 'string',
                'product_servicearea' => 'string',
                'cross_products' => 'text',
                '3d_model' => 'string'
            ];

            foreach ($productColumns as $column => $type) {
                if (!Schema::hasColumn('products', $column)) {
                    if (str_contains($type, 'decimal')) {
                        $parts = explode(':', $type);
                        $precision = 15;
                        $scale = 2;
                        if (isset($parts[1])) {
                             $subp = explode(',', $parts[1]);
                             $precision = (int)$subp[0];
                             $scale = (int)$subp[1];
                        }
                        $table->decimal($column, $precision, $scale)->default(0);
                    } elseif ($type == 'unsignedBigInteger') {
                        $table->unsignedBigInteger($column)->nullable();
                    } elseif ($type == 'tinyInteger') {
                        $table->tinyInteger($column)->default(0);
                    } elseif ($type == 'integer') {
                        $table->integer($column)->default(0);
                    } elseif ($type == 'text') {
                        $table->text($column)->nullable();
                    } else {
                        $table->string($column)->nullable();
                    }
                }
            }
        });

        // 2. Ensure CATEGORIES columns
        Schema::table('categories', function (Blueprint $table) {
            $catColumns = ['photo', 'image', 'is_featured', 'status'];
            foreach ($catColumns as $col) {
                if (!Schema::hasColumn('categories', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });
    }

    public function down(): void {}
};
