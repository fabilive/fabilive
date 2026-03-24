<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payment_gateways')) {
            Schema::create('payment_gateways', function (Blueprint $table) {
                $table->id();
                $table->string('subtitle')->nullable();
                $table->string('title')->nullable();
                $table->string('details')->nullable();
                $table->string('name')->nullable();
                $table->string('keyword')->nullable();
                $table->text('information')->nullable();
                $table->tinyInteger('type')->default(1);
                $table->tinyInteger('checkout')->default(1);
                $table->string('currency_id')->nullable();
                $table->timestamps();
            });
        }

        // Seed default gateways if missing
        if (Schema::hasTable('payment_gateways')) {
            if (DB::table('payment_gateways')->where('keyword', 'cod')->count() == 0) {
                DB::table('payment_gateways')->insert([
                    'title' => 'Cash On Delivery',
                    'details' => 'Pay when you receive your order',
                    'subtitle' => 'Cash On Delivery',
                    'name' => 'cod',
                    'keyword' => 'cod',
                    'type' => 1,
                    'checkout' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            if (DB::table('payment_gateways')->where('keyword', 'campay')->count() == 0) {
                DB::table('payment_gateways')->insert([
                    'title' => 'Campay',
                    'details' => 'Pay via Campay',
                    'subtitle' => 'Campay',
                    'name' => 'Campay',
                    'keyword' => 'campay',
                    'type' => 1,
                    'information' => json_encode([
                        'username' => 'xaIMiuua3afoJs6-KjBf7eaaI15lVhZ2IDEUk0SazL45EWfhRcLJd-7Dey39w5VTRyQnOZFN4y1JnjOmtNQYQw',
                        'password' => 'm318T-MdK7sJokNwsBFyvLGmuSxwOhEWiQoIAffSNX3QXuqSVZvWpNSk0QbrdY1MDtMR1egPPHJmjLI6O7uGpA',
                        'permanent_token' => 'fd3c20a1ff48e47cf92407c0bde3e27a53063d13',
                        'base_url' => 'https://www.campay.net/api',
                        'text' => 'Pay via Campay'
                    ]),
                    'currency_id' => '["1"]',
                    'checkout' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function down(): void
    {
        // Schema::dropIfExists('payment_gateways');
    }
};
