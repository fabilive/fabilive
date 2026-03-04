<?php

namespace Tests\Feature\Financial;

use App\Models\User;
use App\Models\Order;
use App\Models\WalletLedger;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ConcurrentSpendTest extends TestCase
{
    use DatabaseTransactions;

    public function test_wallet_cannot_spend_more_than_balance_sequentially()
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'testuser_'.uniqid().'@test.com';
        $user->password = bcrypt('password');
        $user->balance = 500;
        $user->save();
        
        $payload = [
            'wallet_price' => 400,
            // ... other required checkout fields
            'customer_email' => $user->email,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone,
            'customer_address' => 'Test Address',
            'customer_city' => 'Test City',
            'customer_zip' => '12345',
            'customer_country' => 'UK',
            'order_type' => 'new',
            'dp' => 0,
            'shipping' => 'shiptest',
            'pickup_location' => 'pickuptest',
            'totalQty' => 1,
            'pay_amount' => 400,
            'method' => 'Wallet',
            'txnid' => 'WLT-'.uniqid(),
            'currency_sign' => '$',
            'currency_name' => 'USD',
            'currency_value' => 1,
            'item_name' => 'Test Product',
            'item_number' => 'TP001',
            'cart' => json_encode(['items' => []]),
        ];

        // First attempt (Should succeed)
        $this->actingAs($user);
        $response1 = $this->post('/checkout/payment/wallet-submit', $payload);
        $response1->assertStatus(302); // Redirect back with success
        
        $this->assertEquals(100, $user->fresh()->balance);

        // Second attempt with same amount (Should fail)
        $response2 = $this->post('/checkout/payment/wallet-submit', $payload);
        // The controller should catch the insufficient balance and redirect back with error
        $response2->assertSessionHas('unsuccess', 'Insufficient Balance.');
        
        $this->assertEquals(100, $user->fresh()->balance);
    }
}
