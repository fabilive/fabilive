<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('distance_fees')) {
            Schema::create('distance_fees', function (Blueprint $table) {
                $table->id();
                $table->double('distance_start_range')->default(0);
                $table->double('distance_end_range')->default(0);
                $table->double('fee')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('distance_fees');
    }
};
