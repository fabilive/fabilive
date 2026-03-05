<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ReferralCode;
use App\Models\ReferralUsage;
use App\Models\WalletLedger;
use App\Models\Generalsetting;
use App\Services\ReferralService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReferralSystemTest extends TestCase
{
    use DatabaseTransactions;

    protected ReferralService $referralService;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("SET SESSION sql_mode=''");
        $this->referralService = app(ReferralService::class);

        // Ensure generalsettings has referral config
        $gs = Generalsetting::first();
        if ($gs && !isset($gs->referral_system_active)) {
            // Columns might not exist yet in test DB; skip if so
        }
    }

    public function test_generate_unique_referral_code()
    {
        $user = User::factory()->create();
        $code = $this->referralService->generateCode($user, 'buyer');

        $this->assertNotNull($code);
        $this->assertEquals($user->id, $code->user_id);
        $this->assertEquals('buyer', $code->owner_role);
        $this->assertTrue($code->active);
        $this->assertDatabaseHas('referral_codes', ['code' => $code->code]);
    }

    public function test_referral_awards_both_parties()
    {
        // Set up referrer
        $referrer = User::factory()->create(['current_balance' => 0]);
        $referralCode = $this->referralService->generateCode($referrer, 'buyer');

        // New user uses the code
        $newUser = User::factory()->create([
            'current_balance' => 0,
            'phone' => '237600000001',
            'email' => 'newuser@test.com',
        ]);

        $usage = $this->referralService->applyReferral($referralCode->code, $newUser, 'buyer');

        // Both should be awarded
        $this->assertEquals('awarded', $usage->status);
        $this->assertGreaterThan(0, $usage->referrer_bonus);
        $this->assertGreaterThan(0, $usage->referred_bonus);

        // Referrer balance should increase
        $referrer->refresh();
        $this->assertGreaterThan(0, $referrer->current_balance);

        // Referred user balance should increase
        $newUser->refresh();
        $this->assertGreaterThan(0, $newUser->current_balance);

        // Wallet ledger entries should exist for both
        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $referrer->id,
            'type' => 'referral_bonus',
        ]);
        $this->assertDatabaseHas('wallet_ledger', [
            'user_id' => $newUser->id,
            'type' => 'referral_bonus',
        ]);
    }

    public function test_self_referral_blocked()
    {
        $user = User::factory()->create();
        $code = $this->referralService->generateCode($user, 'buyer');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('You cannot use your own referral code');

        $this->referralService->applyReferral($code->code, $user, 'buyer');
    }

    public function test_duplicate_phone_referral_blocked()
    {
        $referrer = User::factory()->create();
        $code = $this->referralService->generateCode($referrer, 'buyer');

        // First user with phone
        $user1 = User::factory()->create([
            'phone' => '237699999999',
            'email' => 'user1@test.com',
        ]);
        $this->referralService->applyReferral($code->code, $user1, 'buyer');

        // Second user with SAME phone should be blocked
        $user2 = User::factory()->create([
            'phone' => '237699999999',
            'email' => 'user2@test.com',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('already been claimed with this phone number');

        $this->referralService->applyReferral($code->code, $user2, 'buyer');
    }

    public function test_duplicate_email_referral_blocked()
    {
        $referrer = User::factory()->create();
        $code = $this->referralService->generateCode($referrer, 'buyer');

        $user1 = User::factory()->create([
            'phone' => '237600000002',
            'email' => 'same@test.com',
        ]);
        $this->referralService->applyReferral($code->code, $user1, 'buyer');

        $user2 = User::factory()->create([
            'phone' => '237600000003',
            'email' => 'same@test.com',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('already been claimed with this email');

        $this->referralService->applyReferral($code->code, $user2, 'buyer');
    }

    public function test_invalid_code_rejected()
    {
        $user = User::factory()->create();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid referral code');

        $this->referralService->applyReferral('NONEXISTENT123', $user, 'buyer');
    }

    public function test_referral_stats()
    {
        $referrer = User::factory()->create(['current_balance' => 0]);
        $code = $this->referralService->generateCode($referrer, 'buyer');

        $user1 = User::factory()->create(['phone' => '237600000010', 'email' => 'r1@test.com', 'current_balance' => 0]);
        $this->referralService->applyReferral($code->code, $user1, 'buyer');

        $stats = $this->referralService->getStats($referrer);

        $this->assertEquals($code->code, $stats['code']);
        $this->assertEquals(1, $stats['total_referrals']);
        $this->assertGreaterThan(0, $stats['total_earned']);
    }
}
