<?php

namespace App\Services\MbokoAI;

class ResponseGenerator
{
    /**
     * Generate a response based on intent, entities, data lookup, and user role.
     *
     * @param  string  $intent  Detected intent key
     * @param  array   $entities  Extracted entities from user message
     * @param  array   $dataContext  Database lookup results (from SupportDataService)
     * @param  string  $role  User role: buyer, vendor, rider, admin
     * @param  string  $originalMessage  The original user message
     * @return array{response_text: string, escalate: bool, ask_order_id: bool}
     */
    public function generate(
        string $intent,
        array $entities,
        array $dataContext,
        string $role,
        string $originalMessage
    ): array {
        $escalate = false;
        $askOrderId = false;

        $response = match ($intent) {
            // ── GREETINGS ──
            'greeting' => $this->greetingResponse($role),
            'thanks' => $this->thanksResponse(),
            'who_are_you' => $this->whoAreYouResponse(),
            'help_general' => $this->helpResponse($role),

            // ── ORDER ──
            'order_status' => $this->orderStatusResponse($entities, $dataContext, $role, $askOrderId),
            'rider_assignment_issue' => $this->riderAssignmentResponse($entities, $dataContext, $role, $askOrderId, $escalate),
            'delivery_delay' => $this->deliveryDelayResponse($entities, $dataContext, $role, $askOrderId, $escalate),
            'delivery_completed' => $this->deliveryCompletedResponse($entities, $dataContext, $role),
            'delivery_not_completed' => $this->deliveryNotCompletedResponse($entities, $dataContext, $role, $escalate),

            // ── PAYMENT ──
            'payment_issue' => $this->paymentIssueResponse($entities, $dataContext, $role, $askOrderId, $escalate),

            // ── BUYER ──
            'buyer_checkout_issue' => $this->buyerCheckoutResponse($escalate),
            'coupon_referral_issue' => $this->couponReferralResponse($entities, $dataContext, $role),
            'product_issue' => $this->productIssueResponse($escalate),

            // ── SELLER ──
            'seller_order_issue' => $this->sellerOrderResponse($entities, $dataContext, $role, $askOrderId),
            'product_listing_issue' => $this->productListingResponse(),
            'commission_issue' => $this->commissionResponse($role),
            'seller_earnings' => $this->sellerEarningsResponse($dataContext),

            // ── RIDER ──
            'rider_delivery_jobs' => $this->riderJobsResponse($dataContext),
            'rider_delivery_status' => $this->riderDeliveryStatusResponse($entities, $dataContext, $askOrderId),

            // ── WALLET / FINANCE ──
            'wallet_balance_issue' => $this->walletResponse($dataContext, $role),
            'withdrawal_issue' => $this->withdrawalResponse($dataContext, $role),

            // ── ACCOUNT ──
            'account_login_issue' => $this->accountResponse(),

            // ── ADMIN ──
            'admin_approval_issue' => $this->adminApprovalResponse(),
            'admin_payout_management' => $this->adminPayoutResponse(),
            'admin_system_issue' => $this->adminSystemResponse($escalate),

            // ── NAVIGATION & HELP ──
            'how_to_find_info' => $this->howToFindInfoResponse($role),
            'live_support_request' => $this->liveSupport($escalate),

            // ── UNKNOWN ──
            default => $this->unknownResponse($escalate),
        };

        return [
            'response_text' => $response,
            'escalate' => $escalate,
            'ask_order_id' => $askOrderId,
        ];
    }

    // ── GREETING & GENERAL ──

    protected function greetingResponse(string $role): string
    {
        $roleLabel = match ($role) {
            'vendor' => 'seller',
            'rider' => 'delivery partner',
            'admin' => 'admin',
            default => 'valued customer',
        };

        $greetings = [
            "Hello! 👋 I'm MbokoAI, your Fabilive support assistant. I'm here to help you as a {$roleLabel}. What can I assist you with today?",
            "Hi there! 😊 Welcome to MbokoAI support. As a {$roleLabel}, I can help you with orders, payments, deliveries, and more. How can I help?",
            "Hey! 🙌 MbokoAI at your service. Tell me what you need help with, and I'll do my best to assist you!",
        ];

        return $greetings[array_rand($greetings)];
    }

    protected function thanksResponse(): string
    {
        $responses = [
            "You're welcome! 😊 Happy to help. Let me know if you need anything else.",
            "Glad I could help! 🙌 Don't hesitate to reach out anytime.",
            "You're very welcome! Fabilive is always here for you. 💚",
        ];

        return $responses[array_rand($responses)];
    }

    protected function whoAreYouResponse(): string
    {
        return "I am MbokoAI 🤖, the smart support assistant for Fabilive. I can help you track orders, check payments, manage your wallet, handle delivery questions, and much more. If I can't solve your issue, I'll connect you with a live human agent. Just ask!";
    }

    protected function helpResponse(string $role): string
    {
        $base = "I can help you with:\n";

        $topics = match ($role) {
            'buyer' => "• 📦 Track your orders\n• 💳 Check payment status\n• 🚚 Delivery updates\n• 🏷️ Coupon & referral issues\n• 💰 Wallet & balance\n• 🔐 Account issues",
            'vendor' => "• 📦 Manage orders\n• 🏪 Product listings\n• 💰 Earnings & commissions\n• 💸 Withdrawals\n• 🚚 Delivery tracking\n• 🏷️ Referrals\n• 🔐 Account issues",
            'rider' => "• 🚚 Your delivery jobs\n• 📦 Order pickups & deliveries\n• 💰 Earnings & balance\n• 💸 Withdrawals\n• 🔐 Account issues",
            'admin' => "• 📦 Order lookups\n• ✅ Approvals & reviews\n• 💸 Payout management\n• 🔧 System issues\n• 🚚 Delivery oversight",
            default => "• General support topics",
        };

        return $base . $topics . "\n\nJust describe your issue or ask a question!";
    }

    // ── ORDER STATUS ──

    protected function orderStatusResponse(array $entities, array $data, string $role, bool &$askOrderId): string
    {
        if (empty($data['order'])) {
            if (!empty($entities['order_id'])) {
                if (isset($data['order_not_found']) && $data['order_not_found']) {
                    return "❌ I couldn't find order #{$entities['order_id']}. Please double-check the order number and try again.";
                }
                if (isset($data['not_authorized']) && $data['not_authorized']) {
                    return "🔒 You don't have permission to view this order. Make sure the order belongs to your account.";
                }
            }
            $askOrderId = true;
            return "I'd love to help you check your order status! 📦 Could you please share your order number? (e.g., #12345)";
        }

        $summary = $data['order_summary'];
        $response = "📦 **Order #{$summary['order_number']}**\n";
        $response .= "• Status: {$summary['status']}\n";
        $response .= "• Payment: {$summary['payment_status']}\n";
        $response .= "• Total: {$summary['total']}\n";
        $response .= "• Date: {$summary['date']}\n";

        if (!empty($summary['delivery'])) {
            $d = $summary['delivery'];
            $response .= "\n🚚 **Delivery:**\n";
            if ($d['rider_assigned']) {
                $response .= "• Rider: {$d['rider_name']}\n";
                $response .= "• Delivery Status: {$d['status']}\n";
                if ($d['delivered_at']) {
                    $response .= "• Delivered: {$d['delivered_at']}\n";
                }
            } else {
                $response .= "• ⚠️ No rider assigned yet. The system is looking for available riders.\n";
            }
        }

        return $response;
    }

    // ── RIDER ASSIGNMENT ──

    protected function riderAssignmentResponse(array $entities, array $data, string $role, bool &$askOrderId, bool &$escalate): string
    {
        if (empty($entities['order_id']) && empty($data['rider_info'])) {
            $askOrderId = true;
            return "I can check the rider assignment for your order. 🚚 Please share your order number so I can look it up.";
        }

        if (!empty($data['rider_info'])) {
            $info = $data['rider_info'];

            if (!$info['has_delivery_job']) {
                $escalate = true;
                return "⚠️ No delivery job has been created for this order yet. This might mean the order is still being processed. If you've been waiting a while, I recommend requesting live support so our team can look into this.";
            }

            if (!$info['rider_assigned']) {
                $escalate = true;
                return "⚠️ Your order has a delivery job, but **no rider has been assigned yet**. Our system is searching for available riders in your area. If the wait has been long, please click 'Request Live Support' so our team can prioritize this.";
            }

            return "✅ Great news! A rider is assigned to your order.\n• Rider: {$info['rider_name']}\n• Status: {$info['delivery_status']}\n" .
                ($info['accepted_at'] ? "• Accepted: {$info['accepted_at']}\n" : "") .
                ($info['picked_up_at'] ? "• Picked up: {$info['picked_up_at']}\n" : "");
        }

        $askOrderId = true;
        return "Please share your order number so I can check the rider assignment. 🚚";
    }

    // ── DELIVERY DELAY ──

    protected function deliveryDelayResponse(array $entities, array $data, string $role, bool &$askOrderId, bool &$escalate): string
    {
        if (!empty($data['delivery_status'])) {
            $ds = $data['delivery_status'];

            if ($ds['status'] === 'no_delivery_created') {
                $escalate = true;
                return "⚠️ No delivery has been created for this order yet. This is unusual. Let me connect you with a live agent who can check this right away.";
            }

            if ($ds['status'] === 'pending') {
                return "🕐 Your delivery is still pending — it hasn't been picked up yet. " .
                    ($ds['rider_name'] ? "Rider {$ds['rider_name']} is assigned and should pick up soon." : "We're still looking for an available rider.");
            }

            if ($ds['status'] === 'accepted' || $ds['status'] === 'picked_up') {
                return "🚚 Your delivery is in progress! Status: **{$ds['status']}**. " .
                    ($ds['rider_name'] ? "Rider: {$ds['rider_name']}." : "") .
                    " Please allow some time for the rider to reach you.";
            }

            return "Your delivery status is: **{$ds['status']}**. {$ds['details']}";
        }

        if (empty($entities['order_id'])) {
            $askOrderId = true;
            return "I'm sorry about the delay! 😟 Can you share your order number so I can check the delivery status?";
        }

        $escalate = true;
        return "I understand the delivery seems delayed. Let me connect you with a live agent who can provide real-time tracking.";
    }

    // ── DELIVERY COMPLETED / NOT COMPLETED ──

    protected function deliveryCompletedResponse(array $entities, array $data, string $role): string
    {
        return "Thank you for confirming the delivery! ✅ If you're happy with your order, we'd love to hear your feedback. If there's anything wrong with the items, please let me know and I can help.";
    }

    protected function deliveryNotCompletedResponse(array $entities, array $data, string $role, bool &$escalate): string
    {
        $escalate = true;
        return "I'm sorry to hear that! 😟 If your order was marked as delivered but you haven't received it, this needs urgent attention. Please click **'Request Live Support'** so our team can investigate immediately.";
    }

    // ── PAYMENT ──

    protected function paymentIssueResponse(array $entities, array $data, string $role, bool &$askOrderId, bool &$escalate): string
    {
        if (!empty($data['payment_status'])) {
            $ps = $data['payment_status'];

            if ($ps['status'] === 'Completed' || $ps['status'] === 'completed') {
                return "✅ Your payment for this order is confirmed!\n• Method: {$ps['method']}\n• Amount: {$ps['amount']}\n• Transaction ID: {$ps['transaction_id']}";
            }

            if ($ps['status'] === 'Pending' || $ps['status'] === 'pending') {
                $escalate = true;
                return "⏳ Your payment is still **pending**. This can happen if the payment gateway is processing. If money was deducted from your account, please click 'Request Live Support' with your transaction details so we can verify.";
            }

            return "Your payment status is: **{$ps['status']}**\n• Method: {$ps['method']}\n• Amount: {$ps['amount']}";
        }

        if (empty($entities['order_id'])) {
            $askOrderId = true;
            return "I can help check your payment status. 💳 Please share your order number or transaction reference.";
        }

        $escalate = true;
        return "Payment issues require careful investigation. Please click 'Request Live Support' so our team can verify your transaction.";
    }

    // ── BUYER ──

    protected function buyerCheckoutResponse(bool &$escalate): string
    {
        return "I'm sorry you're having trouble checking out! Here are some things to try:\n\n" .
            "1️⃣ **Clear your browser cache** and try again\n" .
            "2️⃣ **Check your internet connection**\n" .
            "3️⃣ Make sure your **delivery address** is complete\n" .
            "4️⃣ Try a **different payment method**\n" .
            "5️⃣ If using a coupon, make sure it's **valid and not expired**\n\n" .
            "If the problem persists, please describe what happens when you try to checkout, and I'll help further.";
    }

    protected function couponReferralResponse(array $entities, array $data, string $role): string
    {
        // If referral data was fetched
        if (!empty($data['referral_status'])) {
            $ref = $data['referral_status'];
            if ($ref['has_referrals']) {
                $codeList = array_map(fn($c) => "• Code: **{$c['code']}** — {$c['usages']}/{$c['max_usages']} uses" . ($c['active'] ? ' ✅' : ' ❌ Inactive'), $ref['codes']);
                return "🏷️ Your referral codes:\n" . implode("\n", $codeList) . "\n\nTotal referrals: {$ref['total_usages']}";
            }
            return "You don't have any referral codes yet. You can generate one from your dashboard.";
        }

        return "For coupon issues:\n• Make sure the coupon code is typed correctly\n• Check if the coupon has expired\n• Some coupons have a minimum order amount\n\nFor referral questions, you can check your referral code and status from your dashboard. Need more help? Just ask!";
    }

    protected function productIssueResponse(bool &$escalate): string
    {
        $escalate = true;
        return "I'm sorry about the product issue! 😟 Product complaints need human review. Please describe the problem, and click **'Request Live Support'** so our team can help you with a return, replacement, or refund.";
    }

    // ── SELLER ──

    protected function sellerOrderResponse(array $entities, array $data, string $role, bool &$askOrderId): string
    {
        if (!empty($data['order'])) {
            return $this->orderStatusResponse($entities, $data, $role, $askOrderId);
        }

        if (empty($entities['order_id'])) {
            $askOrderId = true;
            return "I can help you check on an order. 📦 Please share the order number.";
        }

        return "I couldn't find that order linked to your seller account. Please verify the order number.";
    }

    protected function productListingResponse(): string
    {
        return "For product listing help:\n\n" .
            "📝 **Adding a product:**\n• Go to Seller Dashboard → Products → Add Product\n• Fill in title, description, price, and upload clear images\n• Select the correct category\n\n" .
            "⚠️ **Product not showing?**\n• Make sure the product status is 'Active'\n• New products may need admin approval\n• Check if the product has proper images\n\n" .
            "Need more specific help? Tell me what's happening!";
    }

    protected function commissionResponse(string $role): string
    {
        return "Commission is calculated on each sale as a percentage. Here's what to know:\n\n" .
            "• Commission rates are set by the platform admin\n" .
            "• It's deducted from each order's sale price\n" .
            "• You can see commission breakdowns in your Seller Dashboard → Earnings\n\n" .
            "For specific commission questions or disputes, please request live support.";
    }

    protected function sellerEarningsResponse(array $data): string
    {
        if (!empty($data['seller_earnings']) && $data['seller_earnings']['found']) {
            $e = $data['seller_earnings'];
            return "💰 **Your Earnings Summary:**\n" .
                "• Shop: {$e['shop_name']}\n" .
                "• Current Balance: XAF " . number_format($e['current_balance'], 0) . "\n" .
                "• Total Sales: XAF " . number_format($e['total_sales'], 0) . "\n" .
                "• Total Orders: {$e['total_orders']}\n\n" .
                "To withdraw, go to Seller Dashboard → Withdraw.";
        }

        return "I can show your earnings summary. Please make sure you're logged in as a seller with an approved shop.";
    }

    // ── RIDER ──

    protected function riderJobsResponse(array $data): string
    {
        if (!empty($data['rider_jobs']) && $data['rider_jobs']['found']) {
            $jobs = $data['rider_jobs'];
            $response = "🚚 **Your Delivery Summary:**\n";
            $response .= "• Status: " . ($jobs['is_available'] ? '🟢 Available' : '🔴 Unavailable') . "\n";
            $response .= "• Total Delivered: {$jobs['total_delivered']}\n";
            $response .= "• Balance: XAF " . number_format($jobs['balance'], 0) . "\n";

            if (!empty($jobs['active_jobs'])) {
                $response .= "\n📋 **Active Jobs:**\n";
                foreach ($jobs['active_jobs'] as $job) {
                    $response .= "• Order #{$job['order_number']} — Status: {$job['status']} — Fee: XAF " . number_format($job['delivery_fee'], 0) . "\n";
                }
            } else {
                $response .= "\n✨ No active delivery jobs right now.";
            }

            return $response;
        }

        return "I can check your delivery jobs. Please make sure you're logged in as a rider.";
    }

    protected function riderDeliveryStatusResponse(array $entities, array $data, bool &$askOrderId): string
    {
        if (!empty($data['delivery_status'])) {
            $ds = $data['delivery_status'];
            return "🚚 Delivery status for this order: **{$ds['status']}**\n{$ds['details']}\n\nTo update the status, use your Rider Dashboard or the delivery app.";
        }

        $askOrderId = true;
        return "Please share the order number so I can check the delivery status.";
    }

    // ── WALLET ──

    protected function walletResponse(array $data, string $role): string
    {
        if (!empty($data['wallet']) && $data['wallet']['found']) {
            $w = $data['wallet'];
            $response = "💰 **Wallet Summary:**\n";
            $response .= "• Balance: XAF " . number_format($w['balance'], 0) . "\n";

            if (isset($w['referral_locked']) && $w['referral_locked'] > 0) {
                $response .= "• Referral Locked: XAF " . number_format($w['referral_locked'], 0) . "\n";
            }

            if (!empty($w['recent_transactions'])) {
                $response .= "\n📊 **Recent Transactions:**\n";
                foreach (array_slice($w['recent_transactions'], 0, 3) as $t) {
                    $response .= "• {$t['type']}: XAF " . number_format($t['amount'], 0) . " ({$t['date']})\n";
                }
            }

            return $response;
        }

        return "I can check your wallet balance. Please make sure you're logged in. You can also view your wallet in your dashboard.";
    }

    // ── WITHDRAWAL ──

    protected function withdrawalResponse(array $data, string $role): string
    {
        if (!empty($data['withdrawal_status'])) {
            $ws = $data['withdrawal_status'];

            if (!$ws['has_withdrawals']) {
                return "You don't have any withdrawal requests yet. To request a withdrawal:\n\n" .
                    "1️⃣ Go to your Dashboard → Withdraw\n" .
                    "2️⃣ Enter the amount and select your payment method\n" .
                    "3️⃣ Submit the request\n\n" .
                    "Withdrawals are processed by our admin team.";
            }

            $response = "💸 **Recent Withdrawal Requests:**\n";
            foreach ($ws['recent'] as $w) {
                $status_icon = match ($w['status']) {
                    'completed', 'approved' => '✅',
                    'pending' => '⏳',
                    'rejected' => '❌',
                    default => '📋',
                };
                $response .= "• {$status_icon} XAF " . number_format($w['amount'], 0) . " via {$w['method']} — {$w['status']} ({$w['date']})\n";
            }

            return $response;
        }

        return "To check your withdrawal status or request a new one, go to your Dashboard → Withdraw section. Need specific help? Share more details!";
    }

    // ── ACCOUNT ──

    protected function accountResponse(): string
    {
        return "For account issues, here's what you can do:\n\n" .
            "🔑 **Forgot Password:** Click 'Forgot Password' on the login page to reset\n" .
            "👤 **Update Profile:** Go to Dashboard → Profile Settings\n" .
            "📧 **Email Verification:** Check your inbox (and spam folder) for the verification email\n" .
            "🔒 **Account Locked:** If your account is locked, please request live support\n\n" .
            "What specific account issue are you facing?";
    }

    // ── ADMIN ──

    protected function adminApprovalResponse(): string
    {
        return "For approval management:\n\n" .
            "• **Seller Approvals:** Admin Panel → Vendors → Pending\n" .
            "• **Rider Approvals:** Admin Panel → Riders → Applications\n" .
            "• **Product Approvals:** Admin Panel → Products → Pending Review\n\n" .
            "Need help with a specific approval? Share the details.";
    }

    protected function adminPayoutResponse(): string
    {
        return "For payout management:\n\n" .
            "• **Pending Payouts:** Admin Panel → Withdrawals → Pending\n" .
            "• Review each request and approve or reject\n" .
            "• Completed payouts are tracked in the withdrawal history\n\n" .
            "Need help processing a specific payout?";
    }

    protected function adminSystemResponse(bool &$escalate): string
    {
        $escalate = true;
        return "System issues should be reported to the technical team. Please describe the issue in detail, and click 'Request Live Support' for immediate assistance.";
    }

    // ── LIVE SUPPORT ──

    protected function liveSupport(bool &$escalate): string
    {
        $escalate = true;
        return "Of course! I'll connect you with a live agent right away. 🧑‍💼 Please click the **'Request Live Support'** button below and a team member will join your chat shortly.";
    }

    protected function howToFindInfoResponse(string $role): string
    {
        return match ($role) {
            'vendor' => "🔍 **Vendor Dashboard Navigation:**\n\n" .
                "• **Order Number:** Click on **Orders** in your side menu, then select **All Orders**. You will see your order IDs (e.g., #12345) in the list.\n" .
                "• **Transaction Reference:** Go to **Withdrawals** → **History**. You can also find your payment records in the **Wallet** section.\n" .
                "• **Product Details:** Go to **Products** → **All Products**.",
            'buyer' => "🔍 **Customer Dashboard Navigation:**\n\n" .
                "• **Order Number:** Click on **My Orders** in your dashboard. You can also find it in your order confirmation email.\n" .
                "• **Transaction ID:** Go to **Wallet** → **Transactions** to see all your payment references.\n" .
                "• **Tracking:** Look for the 'Track' button under your order in 'My Orders'.",
            'rider' => "🔍 **Rider App Navigation:**\n\n" .
                "• **Order ID:** Look under the **My Delivery Jobs** section.\n" .
                "• **Payments:** Check your **Wallet** history inside the app.",
            default => "You can find your order number in your dashboard under 'My Orders' or in your confirmation email."
        };
    }

    // ── UNKNOWN ──

    protected function unknownResponse(bool &$escalate): string
    {
        $escalate = true;
        return "I'm not sure I understood that completely. 🤔 Could you rephrase your question? Here are some things I can help with:\n\n" .
            "• Order tracking & Finding IDs\n• Payment & Withdrawal issues\n• Delivery questions\n• Wallet & Earnings\n• Navigation help\n\n" .
            "Or click **'Request Live Support'** to chat with a human agent.";
    }
}
