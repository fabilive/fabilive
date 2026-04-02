<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SupportFaqCategory;
use App\Models\SupportFaq;
use App\Models\SupportBotRule;

class SupportSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // 1. FAQ CATEGORIES
        // ==========================================
        $catBuyerOrders = SupportFaqCategory::firstOrCreate(
            ['name' => 'Orders & Delivery'],
            ['context' => 'buyer', 'sort_order' => 1]
        );
        $catBuyerReferrals = SupportFaqCategory::firstOrCreate(
            ['name' => 'Referrals & Bonuses'],
            ['context' => 'buyer', 'sort_order' => 2]
        );
        $catVendorSetup = SupportFaqCategory::firstOrCreate(
            ['name' => 'Vendor Onboarding & KYC'],
            ['context' => 'vendor', 'sort_order' => 1]
        );
        $catVendorFinance = SupportFaqCategory::firstOrCreate(
            ['name' => 'Wallet & Payouts'],
            ['context' => 'vendor', 'sort_order' => 2]
        );
        $catGeneral = SupportFaqCategory::firstOrCreate(
            ['name' => 'General Account Issues'],
            ['context' => 'both', 'sort_order' => 99]
        );

        // ==========================================
        // 2. FAQs
        // ==========================================
        // Buyer FAQs
        $faqOrderTrack = SupportFaq::updateOrCreate(
            ['question' => 'How do I track my delivery?'],
            [
                'category_id' => $catBuyerOrders->id,
                'context' => 'buyer',
                'answer_html' => '<p>Once your order is purchased, a Delivery Rider will be assigned. You can track the progress straight from your <strong>Buyer Dashboard &gt; Orders</strong>. You will be notified when the rider is approaching your service area!</p>',
                'keywords' => ['track', 'delivery', 'rider', 'where is my order'],
                'sort_order' => 1
            ]
        );

        $faqReferral = SupportFaq::updateOrCreate(
            ['question' => 'How does the 500 CFA Referral Bonus work?'],
            [
                'category_id' => $catBuyerReferrals->id,
                'context' => 'buyer',
                'answer_html' => '<p>When a friend registers using your unique Custom Referral link, they instantly receive a <strong>locked 500 CFA bonus</strong>. Once they complete verification and make their first purchase, you receive your reward directly to your wallet!</p>',
                'keywords' => ['referral', 'bonus', '500 cfa', 'refer', 'friend'],
                'sort_order' => 1
            ]
        );

        $faqRefund = SupportFaq::updateOrCreate(
            ['question' => 'How do I request a refund if my item is damaged?'],
            [
                'category_id' => $catGeneral->id,
                'context' => 'buyer',
                'answer_html' => '<p>If your package arrives damaged, please go to your <strong>Buyer Dashboard &gt; Dispute / Complaint</strong> to open a ticket. Our support team will investigate the escrow ledger to initiate your refund securely.</p>',
                'keywords' => ['refund', 'money back', 'cancel', 'damaged', 'broken'],
                'sort_order' => 1
            ]
        );

        // Vendor FAQs
        $faqKyc = SupportFaq::updateOrCreate(
            ['question' => 'What documents do I need to become a Verified Vendor?'],
            [
                'category_id' => $catVendorSetup->id,
                'context' => 'vendor',
                'answer_html' => '<p>To activate your store, you must upload copies of your <strong>National ID / Passport</strong> and your <strong>Business Registration Certificate</strong>. You will also need to digitally sign the Fabilive Merchant Agreement from your Verification dashboard.</p>',
                'keywords' => ['kyc', 'verify', 'document', 'id card', 'passport', 'business registration', 'agreement'],
                'sort_order' => 1
            ]
        );

        $faqPayout = SupportFaq::updateOrCreate(
            ['question' => 'When can I withdraw money from my Wallet?'],
            [
                'category_id' => $catVendorFinance->id,
                'context' => 'vendor',
                'answer_html' => '<p>Once an order is successfully completed and confirmed by the buyer, the escrow funds are released into your <strong>Fabilive Wallet Ledger</strong>. From there, you can submit a payout request anytime, given you meet the local minimum withdrawal threshold.</p>',
                'keywords' => ['payout', 'withdraw', 'money', 'wallet', 'ledger', 'cash out'],
                'sort_order' => 1
            ]
        );

        $faq3D = SupportFaq::updateOrCreate(
            ['question' => 'How do I upload a 3D Model for my product?'],
            [
                'category_id' => $catVendorSetup->id,
                'context' => 'vendor',
                'answer_html' => '<p>When creating a new product from your dashboard, look for the <strong>"3D Model File"</strong> upload option. Attach your .GLB or .Gltf file, and buyers will be able to interactively spin and view your product in 3D!</p>',
                'keywords' => ['3d', 'model', 'upload', 'glb', 'render', 'product'],
                'sort_order' => 2
            ]
        );

        // ==========================================
        // 3. BOT RULES (SpeedyAi)
        // ==========================================

        //// Persona & Greetings (High Priority Catch-all)
        SupportBotRule::firstOrCreate(
            ['pattern_value' => 'hi,hello,hey,greetings,help,morning,afternoon'],
            [
                'context' => 'both',
                'pattern_type' => 'keyword',
                'response_text' => 'Hello! I am SpeedyAi, your Fabilive automated assistant. How can I speed up your day?',
                'suggested_faq_id' => null,
                'priority' => 100
            ]
        );

        //// Order & Delivery Rules
        SupportBotRule::firstOrCreate(
            ['pattern_value' => 'track,delivery,rider,where is my order,arrival,shipping'],
            [
                'context' => 'buyer',
                'pattern_type' => 'keyword',
                'response_text' => 'It sounds like you need help tracking your delivery. I fetched this guide for you:',
                'suggested_faq_id' => $faqOrderTrack->id,
                'priority' => 50
            ]
        );
        
        //// Refund & Dispute Rules
        SupportBotRule::firstOrCreate(
            ['pattern_value' => 'refund,money back,cancel order,fake,damaged,stolen'],
            [
                'context' => 'buyer',
                'pattern_type' => 'keyword',
                'response_text' => 'Fabilive protects you with our escrow system. Here is how to initiate a complaint and seek a refund:',
                'suggested_faq_id' => $faqRefund->id,
                'priority' => 60
            ]
        );

        //// Referral Rules
        SupportBotRule::firstOrCreate(
            ['pattern_value' => 'referral,bonus,500 cfa,friend code,invite'],
            [
                'context' => 'buyer',
                'pattern_type' => 'keyword',
                'response_text' => 'Looking to earn rewards with your friends? Check out our 500 CFA custom referral program!',
                'suggested_faq_id' => $faqReferral->id,
                'priority' => 40
            ]
        );

        //// Vendor KYC & Verification
        SupportBotRule::firstOrCreate(
            ['pattern_value' => 'verify,kyc,document,id card,business license,agreement,onboarding'],
            [
                'context' => 'vendor',
                'pattern_type' => 'keyword',
                'response_text' => 'Verification ensures Fabilive remains a safe marketplace. Here are the documents you need to get your store verified:',
                'suggested_faq_id' => $faqKyc->id,
                'priority' => 70
            ]
        );

        //// Vendor Payouts
        SupportBotRule::firstOrCreate(
            ['pattern_value' => 'payout,withdraw,earnings,wallet,ledger,cash out'],
            [
                'context' => 'vendor',
                'pattern_type' => 'keyword',
                'response_text' => 'Managing your money is critical. Here is how the Fabilive escrow drops funds into your wallet for payouts:',
                'suggested_faq_id' => $faqPayout->id,
                'priority' => 60
            ]
        );

        //// Vendor 3D Uploads
        SupportBotRule::firstOrCreate(
            ['pattern_value' => '3d,model,glb,upload 3d,ar,virtual'],
            [
                'context' => 'vendor',
                'pattern_type' => 'keyword',
                'response_text' => 'Want to make your products stand out with dynamic 3D rendering? Here is how to upload .GLB models:',
                'suggested_faq_id' => $faq3D->id,
                'priority' => 50
            ]
        );
        
        //// Escalation explicitly requested by user
        SupportBotRule::firstOrCreate(
            ['pattern_value' => 'agent,human,live support,person,real person'],
            [
                'context' => 'both',
                'pattern_type' => 'keyword',
                'response_text' => 'You would like to speak to a real person. Please tap the "Request Live Support" button at the bottom of your chat window to be connected to our team immediately.',
                'suggested_faq_id' => null,
                'priority' => 200 // highest priority
            ]
        );
    }
}
