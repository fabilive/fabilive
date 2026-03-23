<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('title')->nullable();
                $table->text('details')->nullable();
                $table->string('photo')->nullable();
            });
        }

        if (DB::table('services')->count() == 0) {
            DB::table('services')->insert([
                [
                    'title' => 'Fast Delivery',
                    'details' => 'Delivery within 24 hours',
                    'photo' => 'default_service.jpg'
                ],
                [
                    'title' => '24/7 Support',
                    'details' => 'Customer support ready',
                    'photo' => 'default_service.jpg'
                ],
                [
                    'title' => 'Safe Payment',
                    'details' => 'SSL secured checkout',
                    'photo' => 'default_service.jpg'
                ],
                [
                    'title' => 'Money Guarantee',
                    'details' => '30 days money back',
                    'photo' => 'default_service.jpg'
                ]
            ]);
        }
    }

    public function down(): void {}
};
