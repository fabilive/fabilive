<?php

namespace Tests\Feature\Financial;

use App\Models\User;
use App\Models\Withdraw;
use App\Models\PayoutRequest;
use App\Models\WalletLedger;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WithdrawalReversalTest extends TestCase
{
    use DatabaseTransactions;

    public function test_withdrawal_rejection_restores_balance_and_creates_ledger_reversal()
    {
        // 1. Arrange: User with balance and a pending withdrawal
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'testuser_'.uniqid().'@test.com';
        $user->password = bcrypt('password');
        $user->balance = 1000;
        $user->save();
        $withdrawAmount = 500;
        $fee = 10;
        
        $withdraw = Withdraw::create([
            'user_id' => $user->id,
            'amount' => $withdrawAmount,
            'fee' => $fee,
            'method' => 'Bank',
            'status' => 'pending',
            'type' => 'user'
        ]);

        // Reduce balance as if store() was called
        $user->balance -= ($withdrawAmount + $fee);
        $user->save();

        // 2. Act: Admin rejects the withdrawal
        // Use the route that UserController@reject handles
        $admin = \App\Models\Admin::first();
        if (!$admin) {
            $admin = new \App\Models\Admin();
            $admin->name = 'Admin';
            $admin->email = 'admin@test.com';
            $admin->password = bcrypt('password');
            $admin->save();
        }
        $this->actingAs($admin, 'admin');
        $response = $this->get(route('admin-withdraw-reject', $withdraw->id));
        $response->assertStatus(200);

        // 3. Assert:
        // - Balance is restored (incl fee)
        $this->assertEquals(1000, $user->fresh()->balance);

        // - Withdraw status is rejected
        $this->assertEquals('rejected', $withdraw->fresh()->status);

        // - Ledger entry for reversal exists
        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $user->id,
            'amount' => $withdrawAmount + $fee,
            'type' => 'withdrawal_reversal',
            'reference' => 'WDR-' . $withdraw->id,
            'status' => 'completed'
        ]);
    }
}
