<?php

namespace Tests\Feature\Payments;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EscrowReleaseGateTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        \Illuminate\Support\Facades\DB::statement("SET SESSION sql_mode=''");
        $gs = \App\Models\Generalsetting::first();
        if (!$gs) {
            $gs = new \App\Models\Generalsetting();
        }
        $gs->percentage_commission = 10;
        $gs->fixed_commission = 0;
        $gs->rider_percentage_commission = 5;
        $gs->save();
    }

    public function test_escrow_cannot_be_released_if_order_not_completed_or_delivered()
    {
        $order = new \App\Models\Order();
        $order->order_number = 'TEST-ESCROW-1';
        $order->status = 'pending';
        $order->payment_status = 'completed';
        $order->escrow_status = 'pending';
        $order->pay_amount = 1000;
        
        $service = new \App\Services\EscrowReleaseService();
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Order is not in a completed or delivered state");
        
        $service->releaseOrderEscrow($order);
    }

    public function test_escrow_cannot_be_released_if_payment_not_completed()
    {
        $order = new \App\Models\Order();
        $order->order_number = 'TEST-ESCROW-2';
        $order->status = 'delivered';
        $order->payment_status = 'pending';
        $order->escrow_status = 'pending';
        $order->pay_amount = 1000;
        
        $service = new \App\Services\EscrowReleaseService();
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Order payment status must be completed before releasing escrow.");
        
        $service->releaseOrderEscrow($order);
    }

    public function test_escrow_cannot_be_released_if_already_released()
    {
        $order = new \App\Models\Order();
        $order->status = 'delivered';
        $order->payment_status = 'completed';
        $order->escrow_status = 'released';
        
        $service = new \App\Services\EscrowReleaseService();
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Escrow for this order has already been released.");
        
        $service->releaseOrderEscrow($order);
    }

    public function test_escrow_release_splits_funds_correctly()
    {
        $vendor = new \App\Models\User();
        $vendor->name = 'Test Vendor';
        $vendor->email = 'vendor@test.com';
        $vendor->phone = '1234567890';
        $vendor->password = bcrypt('password');
        $vendor->balance = 0;
        $vendor->save();

        $rider = new \App\Models\User();
        $rider->name = 'Test Rider';
        $rider->email = 'rider@test.com';
        $rider->phone = '0987654321';
        $rider->password = bcrypt('password');
        $rider->balance = 0;
        $rider->save();

        $order = new \App\Models\Order();
        $order->order_number = 'TEST-ESCROW-SUCCESS';
        $order->status = 'completed';
        $order->payment_status = 'completed';
        $order->escrow_status = 'pending';
        $order->pay_amount = 1000;
        $order->total_delivery_fee = 200;
        $order->cart = '{}';
        $order->method = 'system';
        $order->currency_sign = '$';
        $order->currency_name = 'USD';
        $order->currency_value = 1;
        $order->customer_email = 'customer@test.com';
        $order->customer_name = 'Test Customer';
        $order->customer_country = 'UK';
        $order->customer_phone = '1234567890';
        $order->customer_address = 'Test Address';
        $order->customer_city = 'Test City';
        $order->customer_zip = '12345';
        $order->save();

        // Create Delivery Rider Association
        $deliveryRider = collect(); // Mock object, wait, we can just use DB
        $dr = new \App\Models\DeliveryRider();
        $dr->order_id = $order->id;
        $dr->rider_id = $rider->id;
        $dr->vendor_id = $vendor->id;
        $dr->phone_number = '0987654321';
        $dr->save();
        $vendorOrder = new \App\Models\VendorOrder();
        $vendorOrder->user_id = $vendor->id;
        $vendorOrder->order_id = $order->id;
        $vendorOrder->order_number = $order->order_number;
        $vendorOrder->qty = 1;
        $vendorOrder->price = 1000;
        $vendorOrder->save();

        $order = $order->fresh();

        $service = new \App\Services\EscrowReleaseService();
        $result = $service->releaseOrderEscrow($order);

        $this->assertTrue($result);

        // Assert Vendor Balance (1000 - 10% commission = 900)
        $this->assertEquals(900, $vendor->fresh()->balance);

        // Assert Rider Balance (200 - 5% commission = 190)
        $this->assertEquals(190, $rider->fresh()->balance);

        // Assert Order updated
        $this->assertEquals('released', $order->fresh()->escrow_status);

        // Assert Wallet Ledger Entries
        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $vendor->id,
            'amount' => 900,
            'type' => 'escrow_release'
        ]);

        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $rider->id,
            'amount' => 190,
            'type' => 'escrow_release'
        ]);
    }
}
