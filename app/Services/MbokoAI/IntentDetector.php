<?php

namespace App\Services\MbokoAI;

use Illuminate\Support\Str;

class IntentDetector
{
    /**
     * Master intent map: intent_key => array of keyword/phrase triggers.
     * Each intent can be triggered by many phrase variations.
     */
    protected array $intentMap = [
        // ── ORDER & DELIVERY ──
        'order_status' => [
            'order status', 'where is my order', 'track order', 'track my order',
            'order update', 'my order', 'check order', 'order progress',
            'what happened to my order', 'order tracking', 'order info',
            'when will my order arrive', 'order details', 'has my order shipped',
        ],
        'rider_assignment_issue' => [
            'no rider', 'rider not connected', 'rider not assigned', 'rider missing',
            'delivery agent missing', 'no delivery agent', 'nobody is delivering',
            'who is my rider', 'rider not found', 'assign rider', 'need a rider',
            'my order has no rider', 'delivery person missing', 'waiting for rider',
        ],
        'delivery_delay' => [
            'delivery delay', 'delivery late', 'delayed delivery', 'order is late',
            'taking too long', 'still waiting', 'when will it arrive', 'not delivered yet',
            'delivery taking long', 'where is the rider', 'slow delivery',
        ],
        'delivery_completed' => [
            'delivered', 'delivery done', 'order received', 'got my order',
            'delivery confirmed', 'package arrived', 'delivery complete',
            'item arrived', 'received my package', 'delivery successful',
        ],
        'delivery_not_completed' => [
            'not delivered', 'delivery failed', 'never received', 'package not here',
            'order not received', 'did not get my order', 'delivery incomplete',
            'rider says delivered but', 'marked delivered but not', 'false delivery',
        ],

        // ── PAYMENT ──
        'payment_issue' => [
            'payment failed', 'payment issue', 'payment problem', 'money deducted',
            'paid but not confirmed', 'payment not received', 'double charged',
            'refund', 'money not returned', 'payment declined', 'transaction failed',
            'charged twice', 'payment pending', 'i paid but', 'payment error',
            'my payment', 'check payment', 'payment status',
        ],

        // ── BUYER SPECIFIC ──
        'buyer_checkout_issue' => [
            'checkout problem', 'can\'t place order', 'cannot checkout', 'cart error',
            'checkout error', 'unable to order', 'order not going through',
            'cannot buy', 'add to cart not working', 'checkout stuck',
            'place order issue', 'buy issue', 'checkout failed',
        ],
        'coupon_referral_issue' => [
            'coupon not working', 'coupon not applying', 'promo code', 'discount code',
            'referral code', 'referral not working', 'coupon expired', 'invalid coupon',
            'referral bonus', 'referral reward', 'coupon issue', 'my referral',
            'referral link', 'share referral', 'referral status',
        ],
        'product_issue' => [
            'product problem', 'wrong product', 'damaged product', 'broken item',
            'product not as described', 'defective', 'wrong item', 'quality issue',
            'product complaint', 'bad product', 'item broken', 'product missing',
        ],

        // ── SELLER / VENDOR SPECIFIC ──
        'seller_order_issue' => [
            'seller order', 'vendor order', 'my store order', 'shop order',
            'order from buyer', 'incoming order', 'new order received',
            'process order', 'manage order', 'seller order status',
            'fulfill order', 'order fulfillment', 'prepare order',
        ],
        'product_listing_issue' => [
            'list product', 'add product', 'upload product', 'product listing',
            'create listing', 'edit product', 'product not showing',
            'product approval', 'product rejected', 'listing issue',
            'cannot upload', 'image upload', 'product image',
        ],
        'commission_issue' => [
            'commission', 'platform fee', 'commission rate', 'how much commission',
            'commission deducted', 'commission too high', 'sales commission',
            'earning deduction', 'fees charged', 'commission calculation',
        ],
        'seller_earnings' => [
            'my earnings', 'total earnings', 'sales earnings', 'how much i earned',
            'income', 'revenue', 'my sales', 'seller balance', 'vendor earnings',
            'earning history', 'check earnings',
        ],

        // ── RIDER SPECIFIC ──
        'rider_delivery_jobs' => [
            'my deliveries', 'assigned orders', 'delivery jobs', 'pending deliveries',
            'my jobs', 'available orders', 'delivery assignment', 'pickup order',
            'rider orders', 'delivery list', 'current delivery',
        ],
        'rider_delivery_status' => [
            'update delivery status', 'mark delivered', 'confirm pickup',
            'delivery proof', 'upload proof', 'proof of delivery',
            'mark as picked up', 'delivery update', 'complete delivery',
        ],

        // ── WALLET & FINANCE ──
        'wallet_balance_issue' => [
            'wallet', 'my balance', 'wallet balance', 'check balance',
            'wallet issue', 'balance wrong', 'wallet not updated',
            'wallet history', 'wallet transactions', 'add money to wallet',
            'fund wallet', 'deposit', 'wallet deposit',
        ],
        'withdrawal_issue' => [
            'withdrawal', 'withdraw money', 'payout', 'cash out', 'withdraw',
            'withdrawal pending', 'withdrawal failed', 'request payout',
            'withdrawal status', 'money not received', 'withdrawal rejected',
            'how to withdraw', 'withdrawal method',
        ],

        // ── ACCOUNT ──
        'account_login_issue' => [
            'login problem', 'can\'t login', 'password reset', 'forgot password',
            'account locked', 'cannot sign in', 'login issue', 'sign in problem',
            'change password', 'update profile', 'account settings',
            'account issue', 'my account', 'profile update', 'verify account',
        ],

        // ── ADMIN SPECIFIC ──
        'admin_approval_issue' => [
            'approve seller', 'approve vendor', 'pending approval', 'approve rider',
            'review application', 'verification pending', 'admin review',
            'approve account', 'approval status', 'reject application',
        ],
        'admin_payout_management' => [
            'process payout', 'pending payouts', 'payout management',
            'approve withdrawal', 'reject withdrawal', 'payout queue',
        ],
        'admin_system_issue' => [
            'system error', 'platform issue', 'system alert', 'server problem',
            'system down', 'maintenance', 'bug report', 'technical issue',
        ],

        // ── CHAT & SUPPORT ──
        'live_support_request' => [
            'talk to human', 'real person', 'live agent', 'live support',
            'human support', 'speak to someone', 'talk to agent',
            'customer service', 'representative', 'escalate',
            'connect to agent', 'need human help', 'not a bot',
        ],
        'greeting' => [
            'hello', 'hi', 'hey', 'good morning', 'good afternoon',
            'good evening', 'howdy', 'greetings', 'yo', 'sup',
            'bonjour', 'salut', 'mboko',
        ],
        'thanks' => [
            'thanks', 'thank you', 'merci', 'appreciate', 'grateful',
            'that helped', 'problem solved', 'issue resolved', 'great help',
        ],
        'help_general' => [
            'help', 'what can you do', 'how does this work', 'how to use',
            'guide me', 'instructions', 'tutorial', 'faq', 'assist me',
            'i need help', 'can you help', 'support', 'help me',
        ],
        'who_are_you' => [
            'who are you', 'what are you', 'your name', 'are you a bot',
            'are you real', 'are you human', 'about you', 'what is mboko',
        ],
    ];

    /**
     * Detect the intent from a user message.
     *
     * @return array{intent: string, confidence: float, matched_phrase: string|null}
     */
    public function detect(string $message): array
    {
        $messageLower = Str::lower(trim($message));
        $messageWords = preg_split('/[\s,.\-!?]+/', $messageLower);
        $messageWords = array_filter($messageWords);

        $bestIntent = 'unknown';
        $bestScore = 0.0;
        $bestPhrase = null;

        foreach ($this->intentMap as $intent => $phrases) {
            foreach ($phrases as $phrase) {
                $phraseLower = Str::lower($phrase);
                $score = $this->calculateMatchScore($messageLower, $messageWords, $phraseLower);

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestIntent = $intent;
                    $bestPhrase = $phrase;
                }
            }
        }

        // Normalize confidence to 0-1 range
        $confidence = min(1.0, $bestScore);

        return [
            'intent' => $confidence >= 0.3 ? $bestIntent : 'unknown',
            'confidence' => round($confidence, 3),
            'matched_phrase' => $confidence >= 0.3 ? $bestPhrase : null,
        ];
    }

    /**
     * Calculate how well a message matches a phrase trigger.
     */
    protected function calculateMatchScore(string $messageLower, array $messageWords, string $phrase): float
    {
        // 1. Exact match (highest confidence)
        if ($messageLower === $phrase) {
            return 1.0;
        }

        // 2. Message contains the exact phrase
        if (Str::contains($messageLower, $phrase)) {
            $phraseLen = strlen($phrase);
            $messageLen = strlen($messageLower);
            // Longer phrase match = higher score
            return 0.7 + (0.3 * ($phraseLen / max($messageLen, 1)));
        }

        // 3. Word-level matching (phrase words found in message)
        $phraseWords = preg_split('/[\s,.\-!?]+/', $phrase);
        $phraseWords = array_filter($phraseWords);
        $matchCount = 0;
        $totalPhraseWords = count($phraseWords);

        foreach ($phraseWords as $pw) {
            foreach ($messageWords as $mw) {
                if ($mw === $pw) {
                    $matchCount++;
                    break;
                }
                // Fuzzy matching for typos (levenshtein)
                if (strlen($mw) > 3 && strlen($pw) > 3 && levenshtein($mw, $pw) <= 1) {
                    $matchCount += 0.8;
                    break;
                }
            }
        }

        if ($totalPhraseWords > 0 && $matchCount > 0) {
            $ratio = $matchCount / $totalPhraseWords;
            // Multi-word phrases get a bonus if all words match
            if ($totalPhraseWords >= 2 && $ratio >= 0.8) {
                return 0.5 + (0.3 * $ratio);
            }
            // Single-word match
            if ($totalPhraseWords === 1 && $ratio >= 1.0) {
                return 0.5;
            }
            return 0.3 * $ratio;
        }

        return 0.0;
    }

    /**
     * Get intents filtered by role permissions.
     */
    public function getIntentsForRole(string $role): array
    {
        $roleIntents = [
            'buyer' => [
                'order_status', 'rider_assignment_issue', 'delivery_delay',
                'delivery_completed', 'delivery_not_completed', 'payment_issue',
                'buyer_checkout_issue', 'coupon_referral_issue', 'product_issue',
                'wallet_balance_issue', 'account_login_issue',
                'live_support_request', 'greeting', 'thanks', 'help_general', 'who_are_you',
            ],
            'vendor' => [
                'order_status', 'seller_order_issue', 'product_listing_issue',
                'commission_issue', 'seller_earnings', 'rider_assignment_issue',
                'delivery_delay', 'delivery_completed', 'delivery_not_completed',
                'payment_issue', 'wallet_balance_issue', 'withdrawal_issue',
                'coupon_referral_issue', 'account_login_issue',
                'live_support_request', 'greeting', 'thanks', 'help_general', 'who_are_you',
            ],
            'rider' => [
                'rider_delivery_jobs', 'rider_delivery_status', 'delivery_delay',
                'delivery_completed', 'delivery_not_completed', 'rider_assignment_issue',
                'wallet_balance_issue', 'withdrawal_issue', 'account_login_issue',
                'coupon_referral_issue',
                'live_support_request', 'greeting', 'thanks', 'help_general', 'who_are_you',
            ],
            'admin' => [
                // Admin can access all intents
                'order_status', 'rider_assignment_issue', 'delivery_delay',
                'delivery_completed', 'delivery_not_completed', 'payment_issue',
                'buyer_checkout_issue', 'coupon_referral_issue', 'product_issue',
                'seller_order_issue', 'product_listing_issue', 'commission_issue',
                'seller_earnings', 'rider_delivery_jobs', 'rider_delivery_status',
                'wallet_balance_issue', 'withdrawal_issue', 'account_login_issue',
                'admin_approval_issue', 'admin_payout_management', 'admin_system_issue',
                'live_support_request', 'greeting', 'thanks', 'help_general', 'who_are_you',
            ],
        ];

        return $roleIntents[$role] ?? $roleIntents['buyer'];
    }
}
