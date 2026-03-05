<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Complaint;
use App\Models\SellerLike;
use App\Models\NotificationPreference;
use App\Services\SmartNotificationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PlatformFeaturesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("SET SESSION sql_mode=''");
    }

    // ========== A4: Complaints ==========

    public function test_buyer_can_create_complaint()
    {
        $buyer = User::factory()->create();

        $complaint = Complaint::create([
            'user_id' => $buyer->id,
            'subject' => 'Order not received',
            'description' => 'My order was marked delivered but I never received it.',
            'priority' => 'high',
        ]);

        $this->assertDatabaseHas('complaints', [
            'user_id' => $buyer->id,
            'status' => 'open',
            'priority' => 'high',
        ]);
        $this->assertTrue($complaint->isOpen());
    }

    public function test_admin_can_resolve_complaint()
    {
        $buyer = User::factory()->create();
        $complaint = Complaint::create([
            'user_id' => $buyer->id,
            'subject' => 'Damaged item',
            'description' => 'Product arrived damaged.',
        ]);

        $complaint->resolve('We have issued a refund to your wallet.', 1);

        $complaint->refresh();
        $this->assertEquals('resolved', $complaint->status);
        $this->assertNotNull($complaint->resolved_at);
        $this->assertEquals('We have issued a refund to your wallet.', $complaint->admin_response);
        $this->assertFalse($complaint->isOpen());
    }

    // ========== A5: Voice Notes ==========

    public function test_message_can_be_voice_type()
    {
        // Voice note fields should exist on messages table
        $this->assertTrue(
            \Schema::hasColumn('messages', 'type'),
            'Messages table should have a type column'
        );
        $this->assertTrue(
            \Schema::hasColumn('messages', 'voice_url'),
            'Messages table should have a voice_url column'
        );
        $this->assertTrue(
            \Schema::hasColumn('messages', 'voice_duration'),
            'Messages table should have a voice_duration column'
        );
    }

    // ========== A6: Seller Social ==========

    public function test_buyer_can_like_seller()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create(['is_vendor' => 2]);

        $like = SellerLike::create([
            'user_id' => $buyer->id,
            'vendor_id' => $seller->id,
        ]);

        $this->assertDatabaseHas('seller_likes', [
            'user_id' => $buyer->id,
            'vendor_id' => $seller->id,
        ]);
    }

    public function test_duplicate_like_blocked()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create(['is_vendor' => 2]);

        SellerLike::create([
            'user_id' => $buyer->id,
            'vendor_id' => $seller->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        SellerLike::create([
            'user_id' => $buyer->id,
            'vendor_id' => $seller->id,
        ]);
    }

    // ========== A7: Smart Notifications ==========

    public function test_notification_respects_preferences()
    {
        $user = User::factory()->create();
        $service = app(SmartNotificationService::class);

        // Disable email for price_dropped
        $service->setPreference($user->id, 'price_dropped', [
            'email_enabled' => false,
            'in_app_enabled' => true,
        ]);

        // Email should be blocked
        $this->assertFalse($service->shouldSend($user->id, 'price_dropped', 'email'));
        // In-app should be allowed
        $this->assertTrue($service->shouldSend($user->id, 'price_dropped', 'in_app'));
    }

    public function test_notification_frequency_cap()
    {
        $user = User::factory()->create();
        $service = app(SmartNotificationService::class);

        // Send 5 notifications (at the cap)
        for ($i = 0; $i < 5; $i++) {
            DB::table('notification_logs')->insert([
                'user_id' => $user->id,
                'notification_type' => 'new_listing_nearby',
                'channel' => 'in_app',
                'sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 6th should be blocked
        $this->assertFalse($service->shouldSend($user->id, 'new_listing_nearby', 'in_app'));
    }

    public function test_notification_quiet_hours()
    {
        $user = User::factory()->create();
        $service = app(SmartNotificationService::class);

        // Set quiet hours to current time
        $now = now()->format('H:i');
        $end = now()->addHour()->format('H:i');

        $service->setPreference($user->id, 'buyer_replied', [
            'quiet_hours_start' => $now,
            'quiet_hours_end' => $end,
        ]);

        $this->assertFalse($service->shouldSend($user->id, 'buyer_replied', 'in_app'));
    }
}
