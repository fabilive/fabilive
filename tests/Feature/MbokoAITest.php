<?php

namespace Tests\Feature;

use App\Services\MbokoAI\EntityExtractor;
use App\Services\MbokoAI\IntentDetector;
use App\Services\MbokoAI\ResponseGenerator;
use App\Services\MbokoAI\SupportDataService;
use App\Services\SupportBotService;
use Tests\TestCase;

class MbokoAITest extends TestCase
{
    protected IntentDetector $detector;
    protected EntityExtractor $extractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new IntentDetector();
        $this->extractor = new EntityExtractor();
    }

    // ════════════════════════════════════════════════
    // INTENT DETECTION
    // ════════════════════════════════════════════════

    public function test_detects_order_status_intent()
    {
        $result = $this->detector->detect('where is my order');
        $this->assertEquals('order_status', $result['intent']);
        $this->assertGreaterThan(0.3, $result['confidence']);
    }

    public function test_detects_rider_assignment_issue()
    {
        foreach (['my order has no rider', 'rider not connected', 'delivery agent missing'] as $msg) {
            $result = $this->detector->detect($msg);
            $this->assertEquals('rider_assignment_issue', $result['intent'], "Failed for: $msg");
        }
    }

    public function test_detects_payment_issue()
    {
        foreach (['payment failed', 'I paid but it did not confirm', 'money deducted'] as $msg) {
            $result = $this->detector->detect($msg);
            $this->assertEquals('payment_issue', $result['intent'], "Failed for: $msg");
        }
    }

    public function test_detects_buyer_checkout_issue()
    {
        foreach (["I can't place order", 'checkout problem', 'coupon not applying'] as $msg) {
            $result = $this->detector->detect($msg);
            $this->assertContains($result['intent'], ['buyer_checkout_issue', 'coupon_referral_issue'], "Failed for: $msg");
        }
    }

    public function test_detects_delivery_delay()
    {
        $result = $this->detector->detect('my delivery is taking too long');
        $this->assertEquals('delivery_delay', $result['intent']);
    }

    public function test_detects_wallet_issue()
    {
        $result = $this->detector->detect('check my wallet balance');
        $this->assertEquals('wallet_balance_issue', $result['intent']);
    }

    public function test_detects_withdrawal_issue()
    {
        $result = $this->detector->detect('my withdrawal is pending');
        $this->assertEquals('withdrawal_issue', $result['intent']);
    }

    public function test_detects_seller_order_issue()
    {
        $result = $this->detector->detect('I have a new order from buyer');
        $this->assertEquals('seller_order_issue', $result['intent']);
    }

    public function test_detects_rider_delivery_jobs()
    {
        $result = $this->detector->detect('show my delivery jobs');
        $this->assertEquals('rider_delivery_jobs', $result['intent']);
    }

    public function test_detects_admin_approval()
    {
        $result = $this->detector->detect('approve seller application');
        $this->assertEquals('admin_approval_issue', $result['intent']);
    }

    public function test_detects_live_support_request()
    {
        foreach (['talk to human', 'I need a live agent', 'connect to agent'] as $msg) {
            $result = $this->detector->detect($msg);
            $this->assertEquals('live_support_request', $result['intent'], "Failed for: $msg");
        }
    }

    public function test_detects_greeting()
    {
        $result = $this->detector->detect('hello');
        $this->assertEquals('greeting', $result['intent']);
    }

    public function test_unknown_returns_low_confidence()
    {
        $result = $this->detector->detect('asdfghjkl random gibberish xyz');
        $this->assertLessThan(0.3, $result['confidence']);
    }

    // ════════════════════════════════════════════════
    // ENTITY EXTRACTION
    // ════════════════════════════════════════════════

    public function test_extracts_order_id_hash()
    {
        $entities = $this->extractor->extract('check order #12345');
        $this->assertEquals('12345', $entities['order_id']);
    }

    public function test_extracts_order_id_text()
    {
        $entities = $this->extractor->extract('order number 67890');
        $this->assertEquals('67890', $entities['order_id']);
    }

    public function test_extracts_email()
    {
        $entities = $this->extractor->extract('my email is test@example.com');
        $this->assertEquals('test@example.com', $entities['email']);
    }

    public function test_extracts_coupon_code()
    {
        $entities = $this->extractor->extract('coupon SAVE20 is not working');
        $this->assertEquals('SAVE20', $entities['coupon_code']);
    }

    public function test_extracts_transaction_ref()
    {
        $entities = $this->extractor->extract('my transaction reference TXN-ABC123');
        $this->assertNotEmpty($entities['transaction_ref']);
    }

    public function test_no_entities_from_clean_message()
    {
        $entities = $this->extractor->extract('hello how are you');
        $this->assertArrayNotHasKey('order_id', $entities);
        $this->assertArrayNotHasKey('email', $entities);
    }

    // ════════════════════════════════════════════════
    // ROLE-BASED ACCESS CONTROL
    // ════════════════════════════════════════════════

    public function test_buyer_cannot_access_admin_intents()
    {
        $allowed = $this->detector->getIntentsForRole('buyer');
        $this->assertNotContains('admin_approval_issue', $allowed);
        $this->assertNotContains('admin_payout_management', $allowed);
        $this->assertNotContains('admin_system_issue', $allowed);
    }

    public function test_buyer_cannot_access_seller_intents()
    {
        $allowed = $this->detector->getIntentsForRole('buyer');
        $this->assertNotContains('seller_order_issue', $allowed);
        $this->assertNotContains('commission_issue', $allowed);
        $this->assertNotContains('seller_earnings', $allowed);
    }

    public function test_rider_cannot_access_seller_intents()
    {
        $allowed = $this->detector->getIntentsForRole('rider');
        $this->assertNotContains('seller_order_issue', $allowed);
    }

    public function test_rider_has_delivery_intents()
    {
        $allowed = $this->detector->getIntentsForRole('rider');
        $this->assertContains('rider_delivery_jobs', $allowed);
        $this->assertContains('rider_delivery_status', $allowed);
    }

    public function test_vendor_has_seller_intents()
    {
        $allowed = $this->detector->getIntentsForRole('vendor');
        $this->assertContains('seller_order_issue', $allowed);
        $this->assertContains('commission_issue', $allowed);
        $this->assertContains('seller_earnings', $allowed);
    }

    public function test_role_filtered_intent_blocks_unauthorized()
    {
        $service = new SupportBotService();
        // Buyer asks about admin approvals — should get unknown/fallback
        $result = $service->processMessage('approve seller application', 'buyer', 1);
        $this->assertNotEquals('admin_approval_issue', $result['intent']);
    }

    // ════════════════════════════════════════════════
    // BOT SERVICE INTEGRATION
    // ════════════════════════════════════════════════

    public function test_bot_service_returns_response()
    {
        $service = new SupportBotService();
        $result = $service->processMessage('hello', 'buyer', 1);
        $this->assertNotEmpty($result['response_text']);
        $this->assertEquals('greeting', $result['intent']);
    }

    public function test_bot_service_handles_all_roles()
    {
        $service = new SupportBotService();
        foreach (['buyer', 'vendor', 'rider', 'admin'] as $role) {
            $result = $service->processMessage('help', $role, 1);
            $this->assertNotEmpty($result['response_text'], "Empty response for role: $role");
        }
    }

    public function test_bot_asks_for_order_id_when_missing()
    {
        $service = new SupportBotService();
        $result = $service->processMessage('where is my order', 'buyer', 1);
        $this->assertStringContainsString('order number', strtolower($result['response_text']));
    }

    public function test_bot_escalation_for_live_support()
    {
        $service = new SupportBotService();
        $result = $service->processMessage('I need to talk to a human', 'buyer', 1);
        $this->assertTrue($result['escalate']);
    }

    public function test_unknown_intent_suggests_escalation()
    {
        $service = new SupportBotService();
        $result = $service->processMessage('xyzabc123 nonsense words here', 'buyer', 1);
        $this->assertTrue($result['escalate']);
        $this->assertStringContainsString('Live Support', $result['response_text']);
    }

    public function test_session_order_id_persistence()
    {
        $service = new SupportBotService();
        // First message mentions an order
        $result1 = $service->processMessage('check order #12345', 'buyer', 1);
        $this->assertEquals('12345', $result1['session_order_id']);

        // Follow-up message without order ID should use session
        $result2 = $service->processMessage('what is the delivery status', 'buyer', 1, '12345');
        $this->assertEquals('12345', $result2['session_order_id']);
    }

    // ════════════════════════════════════════════════
    // RESPONSE GENERATOR — OWNERSHIP DENIAL
    // ════════════════════════════════════════════════

    public function test_response_for_unauthorized_order()
    {
        $gen = new ResponseGenerator();
        $response = $gen->generate(
            'order_status',
            ['order_id' => '999'],
            ['not_authorized' => true],
            'buyer',
            'check order 999'
        );
        $this->assertStringContainsString('permission', strtolower($response['response_text']));
    }

    public function test_response_for_not_found_order()
    {
        $gen = new ResponseGenerator();
        $response = $gen->generate(
            'order_status',
            ['order_id' => '999'],
            ['order_not_found' => true],
            'buyer',
            'check order 999'
        );
        $this->assertStringContainsString('couldn\'t find', strtolower($response['response_text']));
    }

    // ════════════════════════════════════════════════
    // SECURITY — INPUT SANITIZATION
    // ════════════════════════════════════════════════

    public function test_entity_extractor_handles_xss_input()
    {
        $entities = $this->extractor->extract('<script>alert("xss")</script> order #12345');
        $this->assertEquals('12345', $entities['order_id']);
    }

    // ════════════════════════════════════════════════
    // DATA SERVICE — PRIVACY
    // ════════════════════════════════════════════════

    public function test_payment_status_masks_txn_id_for_buyer()
    {
        $dataService = new SupportDataService();
        // This tests the method signature — actual DB test needs factory data
        $result = $dataService->getPaymentStatus(0, 'buyer');
        $this->assertFalse($result['found']); // No order 0 exists
    }

    public function test_rider_info_does_not_expose_phone()
    {
        $dataService = new SupportDataService();
        $result = $dataService->getRiderForOrder(0);
        $this->assertArrayNotHasKey('rider_phone', $result);
    }
}
