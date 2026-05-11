<?php

namespace App\Services;

use App\Models\SupportBotRule;
use App\Models\SupportFaq;
use App\Services\MbokoAI\EntityExtractor;
use App\Services\MbokoAI\IntentDetector;
use App\Services\MbokoAI\ResponseGenerator;
use App\Services\MbokoAI\SupportDataService;
use Illuminate\Support\Str;

class SupportBotService
{
    protected IntentDetector $intentDetector;
    protected EntityExtractor $entityExtractor;
    protected SupportDataService $dataService;
    protected ResponseGenerator $responseGenerator;

    public function __construct()
    {
        $this->intentDetector = new IntentDetector();
        $this->entityExtractor = new EntityExtractor();
        $this->dataService = new SupportDataService();
        $this->responseGenerator = new ResponseGenerator();
    }

    /**
     * Process a message through the smart MbokoAI pipeline.
     *
     * @param  string  $message  User's message text
     * @param  string  $context  Context: buyer, vendor, rider, admin
     * @param  int|null  $userId  Authenticated user ID
     * @param  string|null  $sessionOrderId  Previously mentioned order ID in session
     * @return array|null  {response_text, escalate, intent, entities, confidence, session_order_id}
     */
    public function processMessage(string $message, string $context, ?int $userId = null, ?string $sessionOrderId = null): ?array
    {
        $messageLower = Str::lower(trim($message));

        // Map context to role
        $role = $this->mapContextToRole($context);

        // ── STEP 1: Detect Intent ──
        $intentResult = $this->intentDetector->detect($message);
        $intent = $intentResult['intent'];
        $confidence = $intentResult['confidence'];

        // Check if intent is allowed for this role
        $allowedIntents = $this->intentDetector->getIntentsForRole($role);
        if ($intent !== 'unknown' && !in_array($intent, $allowedIntents)) {
            // Intent not applicable for this role, treat as unknown
            $intent = 'unknown';
            $confidence = 0.0;
        }

        // ── STEP 2: Extract Entities ──
        $entities = $this->entityExtractor->extract($message);

        // Use session order ID if no new one detected
        if (empty($entities['order_id']) && $sessionOrderId) {
            $entities['order_id'] = $sessionOrderId;
        }

        // ── STEP 3: Fetch Data from Database ──
        $dataContext = $this->fetchDataForIntent($intent, $entities, $userId, $role);

        // ── STEP 4: Try Legacy Bot Rules (for custom admin-configured responses) ──
        if ($confidence < 0.3) {
            $legacyResult = $this->tryLegacyRules($message, $messageLower, $context);
            if ($legacyResult) {
                return array_merge($legacyResult, [
                    'intent' => 'legacy_rule',
                    'entities' => $entities,
                    'confidence' => 0.5,
                    'session_order_id' => $entities['order_id'] ?? $sessionOrderId,
                ]);
            }
        }

        // ── STEP 5: Generate Response ──
        $response = $this->responseGenerator->generate($intent, $entities, $dataContext, $role, $message);

        // Update session order ID if new one was found
        $newSessionOrderId = $entities['order_id'] ?? $sessionOrderId;

        return [
            'response_text' => $response['response_text'],
            'escalate' => $response['escalate'] ?? false,
            'intent' => $intent,
            'entities' => $entities,
            'confidence' => $confidence,
            'session_order_id' => $newSessionOrderId,
            'suggested_faq' => null,
        ];
    }

    /**
     * Fetch relevant data from the database based on intent and entities.
     */
    protected function fetchDataForIntent(string $intent, array $entities, ?int $userId, string $role): array
    {
        $data = [];

        if (!$userId) {
            return $data;
        }

        // Order-related intents
        $orderIntents = [
            'order_status', 'rider_assignment_issue', 'delivery_delay',
            'delivery_completed', 'delivery_not_completed', 'payment_issue',
            'seller_order_issue',
        ];

        if (in_array($intent, $orderIntents) && !empty($entities['order_id'])) {
            $orderId = (int) $entities['order_id'];

            // Get order with auth check
            $orderResult = $this->dataService->getOrderById($orderId, $userId, $role);

            if (!$orderResult['found']) {
                $data['order_not_found'] = true;
            } elseif (!$orderResult['authorized']) {
                $data['not_authorized'] = true;
            } else {
                $data['order'] = $orderResult['order'];
                $data['order_summary'] = $orderResult['summary'];
            }

            // Rider info
            if ($intent === 'rider_assignment_issue' || $intent === 'delivery_delay') {
                $data['rider_info'] = $this->dataService->getRiderForOrder($orderId);
            }

            // Delivery status
            if (in_array($intent, ['delivery_delay', 'delivery_completed', 'delivery_not_completed'])) {
                $data['delivery_status'] = $this->dataService->getDeliveryStatus($orderId);
            }

            // Payment status
            if ($intent === 'payment_issue') {
                $data['payment_status'] = $this->dataService->getPaymentStatus($orderId, $role);
            }
        }

        // Wallet
        if ($intent === 'wallet_balance_issue') {
            $data['wallet'] = $this->dataService->getWalletSummary($userId, $role);
        }

        // Withdrawal
        if ($intent === 'withdrawal_issue') {
            $data['withdrawal_status'] = $this->dataService->getWithdrawalStatus($userId, $role);
        }

        // Referral / Coupon
        if ($intent === 'coupon_referral_issue') {
            $data['referral_status'] = $this->dataService->getReferralStatus($userId, $role);
        }

        // Rider jobs
        if ($intent === 'rider_delivery_jobs' && $role === 'rider') {
            $data['rider_jobs'] = $this->dataService->getRiderJobsSummary($userId);
        }

        // Rider delivery status update
        if ($intent === 'rider_delivery_status' && !empty($entities['order_id'])) {
            $data['delivery_status'] = $this->dataService->getDeliveryStatus((int) $entities['order_id']);
        }

        // Seller earnings
        if ($intent === 'seller_earnings' && $role === 'vendor') {
            $data['seller_earnings'] = $this->dataService->getSellerEarnings($userId);
        }

        return $data;
    }

    /**
     * Try matching against legacy admin-configured bot rules in the database.
     */
    protected function tryLegacyRules(string $message, string $messageLower, string $context): ?array
    {
        $rules = SupportBotRule::where(function ($query) use ($context) {
            $query->where('context', $context)->orWhere('context', 'both');
        })
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($rules as $rule) {
            if ($this->ruleMatches($rule, $message, $messageLower)) {
                $faqHtml = null;
                if ($rule->suggested_faq_id) {
                    $faq = SupportFaq::find($rule->suggested_faq_id);
                    if ($faq && $faq->is_active) {
                        $faqHtml = $faq->answer_html;
                    }
                }

                return [
                    'response_text' => $rule->response_text,
                    'suggested_faq' => $faqHtml,
                    'rule_id' => $rule->id,
                    'escalate' => false,
                ];
            }
        }

        return null;
    }

    /**
     * Determine if a legacy rule matches the message.
     */
    private function ruleMatches(SupportBotRule $rule, string $message, string $messageLower): bool
    {
        $pattern = $rule->pattern_value;

        switch ($rule->pattern_type) {
            case 'keyword':
                $words = explode(',', $pattern);
                foreach ($words as $word) {
                    $word = trim(Str::lower($word));
                    if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $message)) {
                        return true;
                    }
                }
                break;

            case 'regex':
                try {
                    $regex = $pattern;
                    if (!Str::startsWith($regex, '/')) {
                        $regex = '/' . $regex . '/i';
                    }
                    if (preg_match($regex, $message)) {
                        return true;
                    }
                } catch (\Exception $e) {
                    return false;
                }
                break;

            case 'contains':
            default:
                $parts = explode(',', $pattern);
                $messageWords = explode(' ', preg_replace('/[^\w\s]/', '', $messageLower));

                foreach ($parts as $part) {
                    $part = trim(Str::lower($part));
                    if (Str::contains($messageLower, $part)) {
                        return true;
                    }

                    foreach ($messageWords as $mWord) {
                        if (strlen($mWord) > 3 && strlen($part) > 3) {
                            if (levenshtein($mWord, $part) <= 1) {
                                return true;
                            }
                        }
                    }
                }
                break;
        }

        return false;
    }

    /**
     * Map context string to a normalized role.
     */
    protected function mapContextToRole(string $context): string
    {
        return match (Str::lower($context)) {
            'vendor', 'seller' => 'vendor',
            'rider', 'delivery' => 'rider',
            'admin' => 'admin',
            default => 'buyer',
        };
    }
}
