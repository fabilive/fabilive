<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('photo')->nullable();
                $table->string('image')->nullable();
                $table->tinyInteger('is_featured')->default(0);
                $table->tinyInteger('status')->default(1);
            });
        }

        if (!Schema::hasTable('subcategories')) {
            Schema::create('subcategories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->string('name');
                $table->string('slug');
                $table->tinyInteger('status')->default(1);
            });
        }

        if (!Schema::hasTable('childcategories')) {
            Schema::create('childcategories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subcategory_id');
                $table->string('name');
                $table->string('slug');
                $table->tinyInteger('status')->default(1);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('childcategories');
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('categories');
    }
};
