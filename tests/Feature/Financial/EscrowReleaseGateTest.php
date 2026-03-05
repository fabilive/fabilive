<?php

namespace Tests\Feature\Financial;

use App\Models\Order;
use App\Models\User;
use App\Models\Generalsetting;
use App\Models\WalletLedger;
use App\Models\DeliveryRider;
use App\Models\VendorOrder;
use App\Services\EscrowReleaseService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EscrowReleaseGateTest extends TestCase
{
    use DatabaseTransactions;

    protected $gs;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->gs = Generalsetting::first();
        if (!$this->gs) {
            $this->gs = Generalsetting::create();
        }
        
        $this->gs->update([
            'percentage_commission' => 10,
            'fixed_commission' => 5,
            'rider_percentage_commission' => 20
        ]);
    }

    public function test_escrow_cannot_be_released_without_admin_verification()
    {
        $order = new Order();
        $order->order_number = 'ORD-' . uniqid();
        $order->status = 'delivered';
        $order->payment_status = 'completed';
        $order->escrow_status = 'pending';
        $order->admin_verified = false;
        $order->pay_amount = 1000;
        $order->customer_email = 'customer@test.com';
        $order->customer_name = 'Test Customer';
        $order->customer_phone = '1234567890';
        $order->customer_address = 'Test Address';
        $order->customer_city = 'Test City';
        $order->customer_zip = '12345';
        $order->customer_country = 'UK';
        $order->method = 'system';
        $order->currency_sign = '$';
        $order->currency_name = 'USD';
        $order->currency_value = 1;
        $order->cart = json_encode(['items' => []]);
        $order->save();

        $service = new EscrowReleaseService();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Escrow release requires explicit Admin verification");

        $service->releaseOrderEscrow($order);
    }

    public function test_escrow_release_succeeds_after_admin_verify_and_splits_correctly()
    {
        $vendor = new User();
        $vendor->name = 'Test Vendor';
        $vendor->email = 'vendor_'.uniqid().'@test.com';
        $vendor->password = bcrypt('password');
        $vendor->balance = 0;
        $vendor->save();

        $rider = new User();
        $rider->name = 'Test Rider';
        $rider->email = 'rider_'.uniqid().'@test.com';
        $rider->password = bcrypt('password');
        $rider->balance = 0;
        $rider->save();

        $orderCharge = 1000;
        $deliveryFee = 200;

        $order = new Order();
        $order->order_number = 'ORD-' . uniqid();
        $order->status = 'delivered';
        $order->payment_status = 'completed';
        $order->escrow_status = 'pending';
        $order->admin_verified = true;
        $order->pay_amount = $orderCharge;
        $order->total_delivery_fee = $deliveryFee;
        $order->customer_email = 'customer@test.com';
        $order->customer_name = 'Test Customer';
        $order->customer_phone = '1234567890';
        $order->customer_address = 'Test Address';
        $order->customer_city = 'Test City';
        $order->customer_zip = '12345';
        $order->customer_country = 'UK';
        $order->method = 'system';
        $order->currency_sign = '$';
        $order->currency_name = 'USD';
        $order->currency_value = 1;
        $order->cart = json_encode(['items' => []]);
        $order->save();

        // Setup Vendor Order
        VendorOrder::create([
            'user_id' => $vendor->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'price' => $orderCharge,
            'qty' => 1
        ]);

        // Setup Delivery Rider
        DeliveryRider::create([
            'order_id' => $order->id,
            'rider_id' => $rider->id,
            'phone_number' => $rider->phone
        ]);

        $service = new EscrowReleaseService();
        $service->releaseOrderEscrow($order);

        // Assert Splits
        // Vendor: 1000 - (10% + 5 fixed) = 1000 - (100 + 5) = 895
        $this->assertEquals(895, $vendor->fresh()->balance);

        // Rider: 200 - (20% of 200) = 200 - 40 = 160
        $this->assertEquals(160, $rider->fresh()->balance);

        // Assert Order Status
        $this->assertEquals('released', $order->fresh()->escrow_status);

        // Assert Ledger Entries
        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $vendor->id,
            'amount' => 895,
            'type' => 'escrow_release',
            'order_id' => $order->id
        ]);

        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $rider->id,
            'amount' => 160,
            'type' => 'escrow_release',
            'order_id' => $order->id
        ]);
    }
}
