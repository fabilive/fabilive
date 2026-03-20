<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('delivery_fee')) {
            Schema::create('delivery_fee', function (Blueprint $table) {
                $table->id();
                $table->string('weight')->nullable();
                $table->double('start_range')->default(0);
                $table->double('end_range')->default(0);
                $table->double('fee')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_fee');
    }
};
