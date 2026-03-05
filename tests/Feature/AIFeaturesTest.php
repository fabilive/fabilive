<?php

namespace Tests\Feature;

use App\Services\AI\AIService;
use App\Services\AI\AntiScamService;
use App\Services\AI\ReputationService;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AIFeaturesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("SET SESSION sql_mode=''");
    }

    // ========== AI0: Architecture ==========

    public function test_ai_config_loaded()
    {
        $this->assertNotNull(config('ai.provider'));
        $this->assertNotNull(config('ai.rate_limits'));
        $this->assertNotNull(config('ai.features'));
        $this->assertIsArray(config('ai.providers'));
    }

    public function test_ai_feature_flags_check()
    {
        $service = app(AIService::class);
        // By default, features are disabled per config
        $this->assertFalse($service->isFeatureEnabled('multilingual_assistant'));
    }

    public function test_ai_audit_log_table_exists()
    {
        $this->assertTrue(
            \Schema::hasTable('ai_audit_logs'),
            'ai_audit_logs table should exist'
        );
        $this->assertTrue(\Schema::hasColumn('ai_audit_logs', 'feature'));
        $this->assertTrue(\Schema::hasColumn('ai_audit_logs', 'provider'));
        $this->assertTrue(\Schema::hasColumn('ai_audit_logs', 'cost_usd'));
    }

    // ========== AI4: Anti-Scam ==========

    public function test_anti_scam_detects_suspicious_phone()
    {
        $service = new AntiScamService();

        $result = $service->analyze(['phone' => '000000000']);

        $this->assertGreaterThan(0, $result['risk_score']);
        $this->assertNotEmpty($result['signals']);
        $this->assertEquals('suspicious_phone', $result['signals'][0]['signal_type']);
    }

    public function test_anti_scam_detects_off_platform_payment()
    {
        $service = new AntiScamService();

        $result = $service->analyze([
            'description' => 'Great product! Please send money directly via Western Union.',
        ]);

        $this->assertGreaterThan(0, $result['risk_score']);
        $signals = collect($result['signals']);
        $this->assertTrue(
            $signals->contains('reason_code', 'off_platform_payment'),
            'Should detect off-platform payment keyword'
        );
    }

    public function test_anti_scam_clean_listing_passes()
    {
        $service = new AntiScamService();

        $result = $service->analyze([
            'phone' => '237677123456',
            'description' => 'Brand new Samsung Galaxy phone, original box, free delivery in Douala.',
            'price' => 150000,
            'category' => 'Electronics',
        ]);

        $this->assertEquals('allow', $result['recommendation']);
    }

    public function test_anti_scam_records_signals()
    {
        $service = new AntiScamService();
        $user = User::factory()->create();

        $result = $service->analyze(['phone' => '000000000']);
        $service->recordSignals($result['signals'], $user->id);

        $this->assertDatabaseHas('scam_signals', [
            'flagged_user_id' => $user->id,
            'signal_type' => 'suspicious_phone',
        ]);
    }

    // ========== AI5: Reputation ==========

    public function test_reputation_badges_table_exists()
    {
        $this->assertTrue(\Schema::hasTable('seller_badges'));
        $this->assertTrue(\Schema::hasColumn('seller_badges', 'badge_type'));
        $this->assertTrue(\Schema::hasColumn('seller_badges', 'score'));
    }

    public function test_reputation_returns_empty_for_new_seller()
    {
        $service = new ReputationService();
        $seller = User::factory()->create(['is_vendor' => 2]);

        $badges = $service->getActiveBadges($seller->id);
        $this->assertIsArray($badges);
        $this->assertEmpty($badges);
    }
}
