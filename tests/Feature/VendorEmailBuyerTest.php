<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\VendorOrder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VendorEmailBuyerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_vendor_can_email_buyer_of_their_order_and_is_rate_limited()
    {
        $vendor = User::factory()->create(['is_vendor' => 2]);
        $buyer_email = 'buyer123@example.com';

        // 1. Cannot email if no matching order
        $response = $this->actingAs($vendor, 'web')->postJson(route('vendor-order-emailsub'), [
            'to' => $buyer_email,
            'subject' => 'Hello',
            'message' => 'Thank you for your order'
        ]);
        
        $this->assertEquals(0, $response->json()); // Unauthorized return

        // Create an order for this vendor
        $order = Order::factory()->create(['customer_email' => $buyer_email]);
        VendorOrder::create([
            'user_id' => $vendor->id,
            'order_id' => $order->id,
            'qty' => 1,
            'price' => 10,
            'order_number' => $order->order_number
        ]);

        // 2. Can email now, should return 1
        $response = $this->actingAs($vendor, 'web')->postJson(route('vendor-order-emailsub'), [
            'to' => $buyer_email,
            'subject' => 'Hello',
            'message' => 'Thank you for your order'
        ]);
        
        $this->assertEquals(1, $response->json());
        $this->assertDatabaseHas('buyer_seller_email_logs', [
            'vendor_id' => $vendor->id,
            'buyer_email' => $buyer_email,
            'subject' => 'Hello',
        ]);

        // 3. Test Rate Limiting (5 allowed per 10 minutes)
        // We already sent 1. Let's send 4 more successfully.
        for ($i = 0; $i < 4; $i++) {
            $this->actingAs($vendor, 'web')->postJson(route('vendor-order-emailsub'), [
                'to' => $buyer_email,
                'subject' => 'Hello ' . $i,
                'message' => 'Thank you for your order'
            ]);
            $this->assertEquals(1, $response->json());
        }

        // 6th attempt should fail due to rate limit
        $response = $this->actingAs($vendor, 'web')->postJson(route('vendor-order-emailsub'), [
            'to' => $buyer_email,
            'subject' => 'Too many',
            'message' => 'Thank you for your order'
        ]);
        
        $this->assertEquals(0, $response->json());
    }
}
