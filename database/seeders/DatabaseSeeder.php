<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\User;
use App\Models\Rider;
use App\Models\ServiceArea;
use App\Models\Category;
use App\Models\Product;
use App\Models\Generalsetting;
use App\Models\Currency;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // General Setting
        Generalsetting::updateOrCreate(['id' => 1], [
            'id' => 1,
            'title' => 'Fabilive Local',
            'delivery_base_fee' => 500,
            'delivery_stopover_fee' => 200,
            'rider_percentage_commission' => 80,
            'currency_format' => 1,
            'withdraw_fee' => 0,
            'withdraw_charge' => 0,
        ]);

        Currency::updateOrCreate(['name' => 'XAF'], [
            'name' => 'XAF',
            'sign' => 'FCFA',
            'value' => 1,
            'is_default' => 1,
        ]);

        // Admin
        Admin::updateOrCreate(['email' => 'admin@fabilive.test'], [
            'name' => 'Admin',
            'email' => 'admin@fabilive.test',
            'phone' => '0000000000',
            'password' => Hash::make('password'),
            'role_id' => 0,
            'shop_name' => 'Admin',
        ]);

        // Service Area
        $serviceArea = ServiceArea::updateOrCreate(['name' => 'Limbe'], [
            'name' => 'Limbe',
            'base_fee' => 500,
            'stopover_fee' => 200,
            'status' => 1,
        ]);
        $serviceAreaId = $serviceArea->id;

        // Buyer
        User::updateOrCreate(['email' => 'buyer@fabilive.test'], [
            'name' => 'Local Buyer',
            'email' => 'buyer@fabilive.test',
            'password' => Hash::make('password'),
            'is_vendor' => 0,
            'phone' => '1234567890',
            'address' => '123 Buyer St, Limbe',
            'city_id' => $serviceAreaId,
            'email_verified' => 'Yes',
        ]);

        // Seller 1
        $seller1 = User::updateOrCreate(['email' => 'seller1@fabilive.test'], [
            'name' => 'Seller One',
            'email' => 'seller1@fabilive.test',
            'password' => Hash::make('password'),
            'is_vendor' => 2,
            'shop_name' => 'Super Store Limbe',
            'owner_name' => 'Seller One',
            'shop_number' => '11111111',
            'shop_address' => 'Store 1, Limbe',
            'city_id' => $serviceAreaId,
            'phone' => '1112223333',
            'email_verified' => 'Yes',
        ]);
        $seller1Id = $seller1->id;

        // Seller 2
        $seller2 = User::updateOrCreate(['email' => 'seller2@fabilive.test'], [
            'name' => 'Seller Two',
            'email' => 'seller2@fabilive.test',
            'password' => Hash::make('password'),
            'is_vendor' => 2,
            'shop_name' => 'Digital & Physical Shop',
            'owner_name' => 'Seller Two',
            'shop_number' => '22222222',
            'shop_address' => 'Store 2, Limbe',
            'city_id' => $serviceAreaId,
            'phone' => '4445556666',
            'email_verified' => 'Yes',
        ]);
        $seller2Id = $seller2->id;

        // Rider
        $rider = Rider::updateOrCreate(['email' => 'rider@fabilive.test'], [
            'name' => 'Fast Rider',
            'email' => 'rider@fabilive.test',
            'password' => Hash::make('password'),
            'phone' => '33333333',
            'address' => 'Rider Lane, Limbe',
            'status' => 1,
            'rider_status' => 'accepted',
            'is_verified' => 1,
            'email_verify' => 'Yes',
        ]);
        $riderId = $rider->id;


        
        DB::table('rider_service_areas')->updateOrInsert(
            ['rider_id' => $riderId, 'city_id' => $serviceAreaId],
            ['rider_id' => $riderId, 'city_id' => $serviceAreaId]
        );

        // Category
        $cat = Category::updateOrCreate(['slug' => 'electronics'], [
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => 1,
        ]);
        $catId = $cat->id;

        // Products for Seller 1
        Product::updateOrCreate(['slug' => Str::slug('Physical Sneaker Local')], [
            'user_id' => $seller1Id,
            'category_id' => $catId,
            'name' => 'Physical Sneaker Local',
            'slug' => Str::slug('Physical Sneaker Local'),
            'photo' => 'dummy.jpg',
            'thumbnail' => 'dummy.jpg',
            'sku' => Str::random(10),
            'price' => 5000,
            'status' => 1,
            'type' => 'Physical',
        ]);

        Product::updateOrCreate(['slug' => Str::slug('Physical Laptop Local')], [
            'user_id' => $seller1Id,
            'category_id' => $catId,
            'name' => 'Physical Laptop Local',
            'slug' => Str::slug('Physical Laptop Local'),
            'photo' => 'dummy.jpg',
            'thumbnail' => 'dummy.jpg',
            'sku' => Str::random(10),
            'price' => 150000,
            'status' => 1,
            'type' => 'Physical',
        ]);

        // Products for Seller 2 (1 Physical, 1 Digital)
        Product::updateOrCreate(['slug' => Str::slug('Physical Phone Local')], [
            'user_id' => $seller2Id,
            'category_id' => $catId,
            'name' => 'Physical Phone Local',
            'slug' => Str::slug('Physical Phone Local'),
            'photo' => 'dummy.jpg',
            'thumbnail' => 'dummy.jpg',
            'sku' => Str::random(10),
            'price' => 80000,
            'status' => 1,
            'type' => 'Physical',
        ]);

        Product::updateOrCreate(['slug' => Str::slug('Digital eBook Local')], [
            'user_id' => $seller2Id,
            'category_id' => $catId,
            'name' => 'Digital eBook Local',
            'slug' => Str::slug('Digital eBook Local'),
            'photo' => 'dummy.jpg',
            'thumbnail' => 'dummy.jpg',
            'sku' => Str::random(10),
            'price' => 2000,
            'status' => 1,
            'type' => 'Digital',
            'file' => 'dummy_file.zip',
        ]);
    }
}
