<?php

namespace Tests\Feature\Payments;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WebhookIdempotencyTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function test_campay_webhook_is_idempotent()
    {
        // 1. Arrange: Create or get a user and a pending deposit
        $user = \App\Models\User::first();
        if (!$user) {
            $this->markTestSkipped('No user found in the database to test with.');
        } 
        $user->balance = 0;
        $user->save();

        $txnid = 'CMPY-' . time();
        $depositAmount = 500;

        // Skip events to avoid triggering mails if any model observers exist
        \App\Models\Deposit::flushEventListeners();

        $deposit = new \App\Models\Deposit();
        $deposit->user_id = $user->id;
        $deposit->amount = $depositAmount;
        $deposit->method = 'Campay';
        $deposit->txnid = $txnid;
        $deposit->status = 0; // pending
        $deposit->save();

        // 2. Act: Send identical webhook payloads 5 consecutive times
        $payload = [
            'reference' => $txnid,
            'status' => 'successful',
            'amount' => $depositAmount,
            'operator' => 'MTN'
        ];

        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/campay/webhook', $payload);
            $response->assertStatus(200);
        }

        // 3. Assert: The idempotency block ensures ONLY ONE deposit/ledger entry occurred
        // User balance Should exactly be 500
        $this->assertEquals($depositAmount, $user->fresh()->balance);
        
        // Deposit should be complete
        $this->assertEquals(1, $deposit->fresh()->status);

        // WebhookEvents should have exactly 1 record processed
        // Subsequent hits hit the "already processed" 200 return BEFORE saving to WebhookEvents
        $this->assertDatabaseCount('webhook_events', 1);

        // Wallet Ledger must only have exactly 1 deposit record
        $this->assertDatabaseCount('wallet_ledger', 1);
        
        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $user->id,
            'amount' => $depositAmount,
            'type' => 'deposit',
            'reference' => $txnid
        ]);
    }
}
