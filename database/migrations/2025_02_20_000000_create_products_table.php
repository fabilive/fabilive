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
                $table->string('sku')->nullable();
                $table->enum('product_type', ['normal', 'affiliate'])->default('normal');
                $table->text('affiliate_link')->nullable();
                $table->unsignedBigInteger('user_id')->default(0);
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('subcategory_id')->nullable();
                $table->unsignedBigInteger('childcategory_id')->nullable();
                $table->text('attributes')->nullable();
                $table->text('name');
                $table->text('slug')->nullable();
                $table->longText('photo');
                $table->string('thumbnail')->nullable();
                $table->string('file')->nullable();
                $table->string('size')->nullable();
                $table->string('size_qty')->nullable();
                $table->string('size_price')->nullable();
                $table->text('color')->nullable();
                $table->double('price');
                $table->double('previous_price')->nullable();
                $table->text('details')->nullable();
                $table->integer('stock')->nullable();
                $table->text('policy')->nullable();
                $table->boolean('status')->default(true);
                $table->integer('views')->default(0);
                $table->string('tags')->nullable();
                $table->text('features')->nullable();
                $table->text('colors')->nullable();
                $table->boolean('product_condition')->default(false);
                $table->string('ship')->nullable();
                $table->boolean('is_meta')->default(false);
                $table->text('meta_tag')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('youtube')->nullable();
                $table->enum('type', ['Physical', 'Digital', 'License', 'Listing']);
                $table->text('license')->nullable();
                $table->text('license_qty')->nullable();
                $table->text('link')->nullable();
                $table->string('platform')->nullable();
                $table->string('region')->nullable();
                $table->string('licence_type')->nullable();
                $table->string('measure')->nullable();
                $table->integer('featured')->default(0);
                $table->integer('best')->default(0);
                $table->integer('top')->default(0);
                $table->integer('hot')->default(0);
                $table->integer('latest')->default(0);
                $table->integer('big')->default(0);
                $table->boolean('trending')->default(false);
                $table->boolean('sale')->default(false);
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
                $table->foreign('childcategory_id')->references('id')->on('childcategories')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
