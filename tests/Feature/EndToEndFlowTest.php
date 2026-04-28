<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\VendorOrder;
use App\Models\DeliveryJob;
use App\Models\DeliveryRider;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class EndToEndFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Basic setup for the platform to function
        Generalsetting::create([
            'title' => 'Fabilive',
            'logo' => 'logo.png',
            'is_smtp' => 0,
            'rider_percentage_commission' => 10,
            'is_reward' => 0
        ]);

        Currency::create([
            'name' => 'CFA',
            'sign' => 'XAF',
            'value' => 1,
            'is_default' => 1
        ]);

        Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => 1
        ]);
    }

    public function test_full_platform_lifecycle_flow()
    {
        // 1. Setup Users
        $admin = Admin::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'role_id' => 1]);
        $seller = User::factory()->create(['is_vendor' => 2, 'shop_name' => 'Test Shop']);
        
        // In Fabilive, riders are often in a separate 'riders' table but have a user association
        // Actually, looking at RiderController, it uses 'rider' guard.
        $rider = \App\Models\Rider::create([
            'name' => 'Rider One',
            'email' => 'rider@test.com',
            'password' => bcrypt('password'),
            'status' => 1
        ]);

        $buyer = User::factory()->create(['is_vendor' => 0]);

        // 2. Setup Product
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'price' => 1000,
            'category_id' => Category::first()->id,
            'status' => 1,
            'stock' => 10
        ]);

        // 3. Setup Coupon
        $coupon = Coupon::create([
            'code' => 'SAVE10',
            'type' => 0, // percentage
            'price' => 10,
            'times' => 10,
            'status' => 1
        ]);

        // 4. Simulate Checkout
        $order = Order::create([
            'order_number' => 'ORD-'.time(),
            'user_id' => $buyer->id,
            'totalQty' => 1,
            'pay_amount' => 900, // 1000 - 10%
            'method' => 'Cash On Delivery',
            'payment_status' => 'Pending',
            'status' => 'pending',
            'currency_sign' => 'XAF',
            'currency_value' => 1,
            'shipping_cost' => 500,
            'total_delivery_fee' => 500,
            'coupon_code' => 'SAVE10',
            'coupon_discount' => 100
        ]);

        VendorOrder::create([
            'order_id' => $order->id,
            'user_id' => $seller->id,
            'qty' => 1,
            'price' => 1000,
            'status' => 'pending',
            'order_number' => $order->order_number
        ]);

        // 5. Verify Visibility - Seller Dashboard
        $this->actingAs($seller)->get(route('vendor-order-index'))
            ->assertStatus(200)
            ->assertSee($order->order_number);

        // 6. Delivery Lifecycle - Create Job
        $job = DeliveryJob::create([
            'order_id' => $order->id,
            'status' => 'available',
            'rider_earnings' => 450, // 500 - 10% admin
            'total_distance' => 5.0
        ]);

        // 7. Rider Accepts Job
        $this->actingAs($rider, 'rider')->get(route('rider-delivery-accept', $job->id))
            ->assertStatus(302)
            ->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals('accepted', $job->status);
        $this->assertEquals($rider->id, $job->assigned_rider_id);

        // 8. Rider Completes Delivery
        // Simulate stop updates
        $order->status = 'delivered';
        $order->save();
        
        $job->status = 'delivered';
        $job->delivered_at = now();
        $job->save();

        // 9. Admin Verifies and Releases Escrow
        $order->admin_verified = true;
        $order->save();

        $escrowService = app(\App\Services\EscrowReleaseService::class);
        $result = $escrowService->releaseOrderEscrow($order);

        $this->assertTrue($result);

        // 10. Verify Balances
        $seller->refresh();
        $rider->refresh();

        // Seller should have 1000 (price) added to current_balance
        $this->assertGreaterThan(0, $seller->current_balance);
        $this->assertGreaterThan(0, $rider->balance);

        // 11. Verify Notifications
        $this->assertDatabaseHas('user_notifications', [
            'recipient_type' => 'user',
            'recipient_id' => $buyer->id
        ]);

    }
}
