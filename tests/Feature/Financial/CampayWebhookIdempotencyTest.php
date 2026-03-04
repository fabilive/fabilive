<?php

namespace Tests\Feature\Financial;

use App\Models\Deposit;
use App\Models\User;
use App\Models\WebhookEvent;
use App\Models\WalletLedger;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CampayWebhookIdempotencyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_campay_webhook_is_idempotent()
    {
        // 1. Arrange: Seed user + initial state
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'testuser_'.uniqid().'@test.com';
        $user->password = bcrypt('password');
        $user->balance = 0;
        $user->save();

        $txnid = 'CMPY-' . uniqid();
        $amount = 1000;

        // Create a pending deposit to match the txnid
        Deposit::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'method' => 'Campay',
            'txnid' => $txnid,
            'status' => 0, // pending
        ]);

        $payload = [
            'reference' => $txnid,
            'status' => 'successful',
            'amount' => $amount,
            'operator' => 'MTN'
        ];

        // 2. Act: Call webhook endpoint with same payload 5 times
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/campay/webhook', $payload);
            $response->assertStatus(200);
        }

        // 3. Assert:
        // - Only 1 webhook_event recorded as processed
        $this->assertEquals(1, WebhookEvent::where('event_id', $txnid)->where('status', 'processed')->count());

        // - Only 1 ledger credit created for this transaction
        $this->assertEquals(1, WalletLedger::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('reference', $txnid)
            ->count());

        // - Balance increased only once
        $this->assertEquals($amount, $user->fresh()->balance);
    }
}
