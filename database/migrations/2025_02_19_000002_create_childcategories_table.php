<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('childcategories')) {
            Schema::create('childcategories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subcategory_id');
                $table->string('name');
                $table->string('slug');
                $table->boolean('status')->default(true);
                $table->timestamps();

                $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('childcategories');
    }
};
