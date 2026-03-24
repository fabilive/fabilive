<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix Products Table (Ensure product_affilate exists)
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'product_affilate')) {
                    $table->integer('product_affilate')->default(0)->after('type');
                }
            });
        }

        // 2. Fix Attributes Table (Ensure all fields from Model exist)
        if (!Schema::hasTable('attributes')) {
            Schema::create('attributes', function (Blueprint $table) {
                $table->id();
                $table->string('attributable_type');
                $table->integer('attributable_id');
                $table->string('name');
                $table->string('input_name');
                $table->integer('price_status')->default(0);
                $table->integer('details_status')->default(0);
                $table->timestamps();
            });
        } else {
            Schema::table('attributes', function (Blueprint $table) {
                if (!Schema::hasColumn('attributes', 'attributable_type')) {
                    $table->string('attributable_type')->after('id');
                }
                if (!Schema::hasColumn('attributes', 'attributable_id')) {
                    $table->integer('attributable_id')->after('attributable_type');
                }
                if (!Schema::hasColumn('attributes', 'price_status')) {
                    $table->integer('price_status')->default(0)->after('input_name');
                }
                if (!Schema::hasColumn('attributes', 'details_status')) {
                    $table->integer('details_status')->default(0)->after('price_status');
                }
            });
        }

        // 3. Robust Content Seeding (Categories, Subcategories, Products)
        // Ensure Electronic category exists
        $e_id = DB::table('categories')->where('slug', 'electronics')->value('id');
        if (!$e_id) {
             $e_id = DB::table('categories')->insertGetId([
                'name' => 'Electronics',
                'slug' => 'electronics',
                'photo' => 'category_electronic_1774125726419.png',
                'status' => 1
            ]);
        }

        // Seed Subcategories if missing
        DB::table('subcategories')->updateOrInsert(
            ['slug' => 'computers', 'category_id' => $e_id],
            ['name' => 'Computers', 'status' => 1]
        );
        $s_id = DB::table('subcategories')->where('slug', 'computers')->value('id');

        // Seed Sample Product if missing
        DB::table('products')->updateOrInsert(
            ['slug' => 'premium-laptop-sample'],
            [
                'name' => 'Premium Laptop Sample',
                'category_id' => $e_id,
                'subcategory_id' => $s_id,
                'photo' => 'category_electronic_1774125726419.png',
                'thumbnail' => 'category_electronic_1774125726419.png',
                'price' => 1200,
                'status' => 1,
                'sku' => 'LAP-001',
                'product_type' => 'normal',
                'type' => 'Physical'
            ]
        );
    }

    public function down(): void {}
};
