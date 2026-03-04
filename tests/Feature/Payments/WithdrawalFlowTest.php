<?php

namespace Tests\Feature\Payments;

use App\Models\User;
use App\Models\Withdraw;
use App\Models\WalletLedger;
use App\Models\Generalsetting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class WithdrawalFlowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("SET SESSION sql_mode=''");
        
        // Ensure Generalsetting exists
        $gs = Generalsetting::first();
        if (!$gs) {
            $gs = new Generalsetting();
        }
        $gs->withdraw_fee = 10;
        $gs->withdraw_charge = 5; // 5%
        $gs->save();
    }

    public function test_user_can_request_withdrawal()
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'user' . uniqid() . '@example.com';
        $user->password = bcrypt('password');
        $user->balance = 1000;
        $user->email_verified = 'Yes';
        $user->save();

        $response = $this->actingAs($user)->post(route('user-wwt-store'), [
            'methods' => 'Campay',
            'amount' => 500,
            'acc_email' => $user->email,
            'acc_name' => $user->name,
            'reference' => 'TEST-WITHDRAW-1'
        ]);

        $response->assertStatus(200);

        // Assert balance deducted
        $user->refresh();
        $this->assertEquals(500, $user->balance);

        // Assert Withdraw record
        $withdraw = Withdraw::where('user_id', $user->id)->first();
        $this->assertNotNull($withdraw);
        $this->assertEquals(465, (float)$withdraw->amount); // 500 - (25 + 10) = 465
        $this->assertEquals(35, (float)$withdraw->fee);
        
        // Assert Ledger
        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $user->id,
            'amount' => 500, // Original deduction
            'type' => 'withdrawal_pending'
        ]);
    }

    public function test_admin_can_reject_user_withdrawal()
    {
        $user = new User();
        $user->name = 'Restoration User';
        $user->email = 'user' . uniqid() . '@example.com';
        $user->password = bcrypt('password');
        $user->balance = 500; // Balance after deduction
        $user->email_verified = 'Yes';
        $user->save();

        $withdraw = new Withdraw();
        $withdraw->user_id = $user->id;
        $withdraw->method = 'Campay';
        $withdraw->amount = 465;
        $withdraw->fee = 35;
        $withdraw->type = 'user';
        $withdraw->status = 'pending';
        $withdraw->save();

        // Create an admin user
        $admin = \App\Models\Admin::first();
        if (!$admin) {
            $admin = new \App\Models\Admin();
            $admin->name = 'Admin';
            $admin->email = 'admin@example.com';
            $admin->password = bcrypt('password');
            $admin->save();
        }

        $response = $this->actingAs($admin, 'admin')->get(route('admin-withdraw-reject', $withdraw->id));
        $response->assertStatus(200);

        // Assert balance restored
        $user->refresh();
        $this->assertEquals(1000, (float)$user->balance); // 500 + 465 + 35

        // Assert status updated
        $withdraw->refresh();
        $this->assertEquals('rejected', $withdraw->status);

        // Assert Ledger Reversal
        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $user->id,
            'amount' => 500,
            'type' => 'withdrawal_reversal'
        ]);
    }
}
