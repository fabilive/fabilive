<?php

namespace App\Services\MbokoAI;

use Illuminate\Support\Str;

class EntityExtractor
{
    /**
     * Extract all recognized entities from a user message.
     *
     * @return array<string, mixed>
     */
    public function extract(string $message): array
    {
        $entities = [];

        // 1. Order ID (common formats: #12345, ORD-12345, order 12345, order number 12345)
        $orderId = $this->extractOrderId($message);
        if ($orderId) {
            $entities['order_id'] = $orderId;
        }

        // 2. Transaction reference
        $txnRef = $this->extractTransactionRef($message);
        if ($txnRef) {
            $entities['transaction_ref'] = $txnRef;
        }

        // 3. Coupon code
        $coupon = $this->extractCouponCode($message);
        if ($coupon) {
            $entities['coupon_code'] = $coupon;
        }

        // 4. Referral code
        $referral = $this->extractReferralCode($message);
        if ($referral) {
            $entities['referral_code'] = $referral;
        }

        // 5. Money amount
        $amount = $this->extractMoneyAmount($message);
        if ($amount !== null) {
            $entities['amount'] = $amount;
        }

        // 6. Email
        $email = $this->extractEmail($message);
        if ($email) {
            $entities['email'] = $email;
        }

        // 7. Phone number
        $phone = $this->extractPhone($message);
        if ($phone) {
            $entities['phone'] = $phone;
        }

        return $entities;
    }

    /**
     * Extract order ID from message.
     * Supports: #12345, ORD-12345, order 12345, order number 12345, or just a 5+ digit number in context.
     */
    public function extractOrderId(string $message): ?string
    {
        // Pattern: #12345 or ORD-12345 or ORDER-12345
        if (preg_match('/(?:#|ORD[\-_]?|ORDER[\-_]?)(\d{3,10})/i', $message, $m)) {
            return $m[1];
        }

        // Pattern: "order (number|id|no|#)? 12345"
        if (preg_match('/order\s*(?:number|id|no\.?|#)?\s*[:\s]?\s*(\d{3,10})/i', $message, $m)) {
            return $m[1];
        }

        // Pattern: standalone large number (5+ digits) likely is an order ID
        if (preg_match('/\b(\d{5,10})\b/', $message, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * Extract transaction reference.
     */
    protected function extractTransactionRef(string $message): ?string
    {
        // Pattern: TXN-xxx or REF-xxx or TRANS-xxx
        if (preg_match('/(?:TXN|REF|TRANS|PAY)[\-_]?([A-Z0-9]{4,20})/i', $message, $m)) {
            return $m[0];
        }

        // Pattern: "reference xxx" or "transaction xxx"
        if (preg_match('/(?:reference|transaction)\s*(?:number|id|no\.?|#|:)?\s*([A-Z0-9\-]{4,20})/i', $message, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * Extract coupon code.
     */
    protected function extractCouponCode(string $message): ?string
    {
        // Pattern: "coupon xxx" or "promo code xxx" or "discount code xxx"
        if (preg_match('/(?:coupon|promo\s*code|discount\s*code)\s*(?:is|:)?\s*([A-Z0-9\-_]{3,20})/i', $message, $m)) {
            return Str::upper($m[1]);
        }

        return null;
    }

    /**
     * Extract referral code.
     */
    protected function extractReferralCode(string $message): ?string
    {
        // Pattern: "referral code xxx" or "referral xxx" or "ref code xxx"
        if (preg_match('/(?:referral|ref)\s*(?:code|link)?\s*(?:is|:)?\s*([A-Z0-9\-_]{4,20})/i', $message, $m)) {
            return Str::upper($m[1]);
        }

        return null;
    }

    /**
     * Extract money amount.
     */
    protected function extractMoneyAmount(string $message): ?float
    {
        // Pattern: XAF 5000 or 5000 XAF or FCFA 5000 or 5,000
        if (preg_match('/(?:XAF|FCFA|CFA|₣)\s*([\d,]+(?:\.\d{1,2})?)/i', $message, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }

        // Pattern: 5000 XAF or 5000 FCFA
        if (preg_match('/([\d,]+(?:\.\d{1,2})?)\s*(?:XAF|FCFA|CFA|₣)/i', $message, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }

        return null;
    }

    /**
     * Extract email address.
     */
    protected function extractEmail(string $message): ?string
    {
        if (preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/', $message, $m)) {
            return $m[0];
        }

        return null;
    }

    /**
     * Extract phone number.
     */
    protected function extractPhone(string $message): ?string
    {
        // International format or local format
        if (preg_match('/(?:\+?\d{1,3}[\s\-]?)?(?:\(?\d{2,4}\)?[\s\-]?)?\d{6,9}/', $message, $m)) {
            $cleaned = preg_replace('/[\s\-\(\)]/', '', $m[0]);
            if (strlen($cleaned) >= 8 && strlen($cleaned) <= 15) {
                return $cleaned;
            }
        }

        return null;
    }
}
