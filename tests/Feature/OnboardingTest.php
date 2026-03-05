<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Rider;
use App\Models\Generalsetting;
use App\Services\SellerOnboardingService;
use App\Services\RiderOnboardingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("SET SESSION sql_mode=''");
    }

    // ==================== SELLER ONBOARDING (A2) ====================

    public function test_seller_cannot_submit_without_tin()
    {
        $service = app(SellerOnboardingService::class);

        $user = User::factory()->create([
            'is_vendor' => 0,
            'taxpayer_card_copy' => null,
            'national_id_front_image' => 'some_id.jpg',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('TIN');

        $service->submitForApproval($user);
    }

    public function test_seller_cannot_submit_without_government_id()
    {
        $service = app(SellerOnboardingService::class);

        $user = User::factory()->create([
            'is_vendor' => 0,
            'taxpayer_card_copy' => 'tin.pdf',
            'national_id_front_image' => null,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Government ID');

        $service->submitForApproval($user);
    }

    public function test_seller_with_all_docs_submits_successfully()
    {
        $service = app(SellerOnboardingService::class);

        $user = User::factory()->create([
            'is_vendor' => 0,
            'taxpayer_card_copy' => 'tin.pdf',
            'national_id_front_image' => 'id_front.jpg',
        ]);

        $result = $service->submitForApproval($user);
        $this->assertTrue($result);

        $user->refresh();
        $this->assertEquals('pending_approval', $user->vendor_status);
        $this->assertEquals(1, $user->is_vendor);
    }

    public function test_admin_approval_activates_seller()
    {
        $service = app(SellerOnboardingService::class);

        $user = User::factory()->create([
            'is_vendor' => 1,
            'vendor_status' => 'pending_approval',
            'taxpayer_card_copy' => 'tin.pdf',
            'national_id_front_image' => 'id_front.jpg',
        ]);

        $result = $service->approve($user, 1);
        $this->assertTrue($result);

        $user->refresh();
        $this->assertEquals('approved', $user->vendor_status);
        $this->assertEquals(2, $user->is_vendor); // Approved vendor flag
        $this->assertNotNull($user->vendor_approved_at);
    }

    public function test_admin_rejection_blocks_seller()
    {
        $service = app(SellerOnboardingService::class);

        $user = User::factory()->create([
            'is_vendor' => 1,
            'vendor_status' => 'pending_approval',
        ]);

        $result = $service->reject($user, 'Invalid business documents', 1);
        $this->assertTrue($result);

        $user->refresh();
        $this->assertEquals('rejected', $user->vendor_status);
        $this->assertEquals(0, $user->is_vendor);
        $this->assertEquals('Invalid business documents', $user->vendor_rejection_reason);
    }

    // ==================== RIDER ONBOARDING (A3) ====================

    public function test_rider_cannot_submit_without_national_id()
    {
        $service = app(RiderOnboardingService::class);

        $rider = Rider::create([
            'name' => 'Test Rider',
            'email' => 'rider_test@example.com',
            'phone' => '237600000099',
            'password' => bcrypt('password'),
            'national_id_front_image' => null,
            'national_id_back_image' => null,
            'driver_license_individual' => 'license.jpg',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('National ID');

        $service->submitForApproval($rider);
    }

    public function test_rider_with_all_docs_submits_successfully()
    {
        $service = app(RiderOnboardingService::class);

        $rider = Rider::create([
            'name' => 'Test Rider',
            'email' => 'rider_ok@example.com',
            'phone' => '237600000098',
            'password' => bcrypt('password'),
            'national_id_front_image' => 'front.jpg',
            'national_id_back_image' => 'back.jpg',
            'driver_license_individual' => 'license.jpg',
        ]);

        $result = $service->submitForApproval($rider);
        $this->assertTrue($result);

        $rider->refresh();
        $this->assertEquals('pending_approval', $rider->onboarding_status);
    }

    public function test_admin_approves_rider()
    {
        $service = app(RiderOnboardingService::class);

        $rider = Rider::create([
            'name' => 'Test Rider',
            'email' => 'rider_approve@example.com',
            'phone' => '237600000097',
            'password' => bcrypt('password'),
            'national_id_front_image' => 'front.jpg',
            'national_id_back_image' => 'back.jpg',
            'driver_license_individual' => 'license.jpg',
            'onboarding_status' => 'pending_approval',
        ]);

        $result = $service->approve($rider, 1);
        $this->assertTrue($result);

        $rider->refresh();
        $this->assertEquals('approved', $rider->onboarding_status);
        $this->assertNotNull($rider->approved_at);
    }
}
