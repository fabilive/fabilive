<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Rider;
use App\Models\Order;
use App\Models\VendorOrder;
use App\Models\Product;
use App\Models\DeliveryJob;
use App\Models\DeliveryJobStop;
use App\Models\ManageAgreement;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Agreements
        $agreementTypes = [
            'Selfi_Instructions',
            'Fabilive_Delivery_Company_Agreement',
            'Fabilive_Delivery_Individual_Agreement'
        ];

        foreach ($agreementTypes as $type) {
            DB::table('agreements')->updateOrInsert(['type' => $type], [
                'type' => $type,
                'image' => 'assets/images/noimage.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Create Test Accounts
        $buyer = User::updateOrCreate(['email' => 'buyer@test.com'], [
            'name' => 'Test Buyer',
            'email' => 'buyer@test.com',
            'password' => Hash::make('password'),
            'is_vendor' => 0,
            'phone' => '670000000',
            'address' => 'Limbe Center',
            'city_id' => 1,
            'email_verified' => 'Yes',
            'is_verified' => 1
        ]);

        $seller = User::updateOrCreate(['email' => 'seller@test.com'], [
            'name' => 'Test Seller',
            'email' => 'seller@test.com',
            'password' => Hash::make('password'),
            'is_vendor' => 2,
            'shop_name' => 'Test Shop Limbe',
            'owner_name' => 'Test Seller',
            'shop_number' => 'SH001',
            'shop_address' => 'Market Road, Limbe',
            'city_id' => 1,
            'phone' => '671111111',
            'email_verified' => 'Yes',
            'is_verified' => 1,
            'vendor_status' => 'approved'
        ]);

        $rider = Rider::updateOrCreate(['email' => 'rider@test.com'], [
            'name' => 'Test Rider',
            'email' => 'rider@test.com',
            'password' => Hash::make('password'),
            'phone' => '672222222',
            'onboarding_status' => 'approved',
            'rider_status' => 'accepted',
            'is_verified' => 1,
            'email_verify' => 'Yes'
        ]);

        // 3. Create a Test Order
        $product = Product::where('user_id', $seller->id)->first();
        if (!$product) {
            $product = Product::create([
                'user_id' => $seller->id,
                'category_id' => 1,
                'name' => 'Test Product',
                'slug' => Str::slug('Test Product ' . Str::random(5)),
                'photo' => 'dummy.jpg',
                'thumbnail' => 'dummy.jpg',
                'sku' => Str::random(10),
                'price' => 1000,
                'status' => 1,
                'type' => 'Physical'
            ]);
        }

        $orderNumber = Str::random(10);
        $order = Order::create([
            'user_id' => $buyer->id,
            'order_number' => $orderNumber,
            'customer_email' => $buyer->email,
            'customer_name' => $buyer->name,
            'customer_phone' => $buyer->phone,
            'customer_address' => $buyer->address,
            'customer_country' => 'Cameroon',
            'customer_city' => 'Limbe',
            'customer_zip' => '0000',
            'shipping_name' => $buyer->name,
            'shipping_email' => $buyer->email,
            'shipping_phone' => $buyer->phone,
            'shipping_address' => $buyer->address,
            'shipping_country' => 'Cameroon',
            'shipping_city' => 'Limbe',
            'shipping_zip' => '0000',
            'totalQty' => 1,
            'pay_amount' => 1500, // 1000 product + 500 delivery
            'method' => 'Cash On Delivery',
            'payment_status' => 'Pending',
            'status' => 'pending',
            'currency_sign' => 'FCFA',
            'currency_value' => 1,
            'shipping_cost' => 500,
            'service_area_id' => 1,
            'currency_name' => 'XAF',
            'tax' => 0,
            'cart' => json_encode([
                'items' => [
                    $product->id . Str::random(2) => [
                        'qty' => 1,
                        'size' => '',
                        'color' => '',
                        'price' => 1000,
                        'item' => $product->toArray(),
                        'user_id' => $seller->id
                    ]
                ]
            ])
        ]);

        $vendorOrder = VendorOrder::create([
            'order_id' => $order->id,
            'user_id' => $seller->id,
            'qty' => 1,
            'price' => 1000,
            'order_number' => $order->order_number,
            'status' => 'pending'
        ]);

        // 4. Create Delivery Job
        $deliveryJob = DeliveryJob::create([
            'order_id' => $order->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
            'delivery_fee_total' => 500,
            'rider_earnings' => 400, // 80%
            'service_area_id' => 1
        ]);

        // Pickup Stop (Seller)
        DeliveryJobStop::create([
            'delivery_job_id' => $deliveryJob->id,
            'type' => 'pickup',
            'seller_id' => $seller->id,
            'location_text' => $seller->shop_address,
            'status' => 'pending',
            'sequence' => 1
        ]);

        // Delivery Stop (Buyer)
        DeliveryJobStop::create([
            'delivery_job_id' => $deliveryJob->id,
            'type' => 'delivery',
            'seller_id' => null,
            'location_text' => $buyer->address,
            'status' => 'pending',
            'sequence' => 2
        ]);
    }
}
