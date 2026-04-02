<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportSystemSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------
        // FAQ CATEGORIES
        // ---------------------------------------------------------
        $categories = [
            ['id' => 1, 'name' => 'Orders & Delivery', 'context' => 'buyer', 'sort_order' => 1],
            ['id' => 2, 'name' => 'Referrals & Bonuses', 'context' => 'buyer', 'sort_order' => 2],
            ['id' => 3, 'name' => 'Vendor Onboarding & KYC', 'context' => 'vendor', 'sort_order' => 1],
            ['id' => 4, 'name' => 'Wallet & Payouts', 'context' => 'vendor', 'sort_order' => 2],
            ['id' => 5, 'name' => 'General Account Issues', 'context' => 'both', 'sort_order' => 99],
        ];

        foreach ($categories as $cat) {
            DB::table('support_faq_categories')->updateOrInsert(['id' => $cat['id']], $cat);
        }

        // ---------------------------------------------------------
        // FAQs
        // ---------------------------------------------------------
        $faqs = [
            [
                'id' => 1,
                'category_id' => 1,
                'context' => 'buyer',
                'question' => 'How do I track my delivery?',
                'answer_html' => '<p>Once your order is purchased, a Delivery Rider will be assigned. You can track the progress straight from your <strong>Buyer Dashboard > Orders</strong>. Check the status: "Rider Assigned" or "Out for Delivery"!</p>',
                'keywords' => json_encode(['track', 'delivery', 'rider', 'order', 'parcel', 'where', 'package', 'irder']),
                'sort_order' => 1
            ],
            [
                'id' => 2,
                'category_id' => 2,
                'context' => 'buyer',
                'question' => 'How does the 500 CFA Referral Bonus work?',
                'answer_html' => '<p>Share your code! When a friend registers, they get <strong>500 CFA locked</strong>. Once they verify and buy something, you get your bonus too!</p>',
                'keywords' => json_encode(['referral', 'bonus', '500', 'code', 'invite', 'money']),
                'sort_order' => 1
            ],
            [
                'id' => 3,
                'category_id' => 5,
                'context' => 'buyer',
                'question' => 'How do I request a refund?',
                'answer_html' => '<p>Go to your <strong>Buyer Dashboard > Dispute / Complaint</strong>. Our team will review the escrow and handle your refund.</p>',
                'keywords' => json_encode(['refund', 'return', 'money back', 'cancel', 'faulty', 'broken', 'wrong']),
                'sort_order' => 1
            ]
        ];

        foreach ($faqs as $faq) {
            DB::table('support_faqs')->updateOrInsert(['id' => $faq['id']], $faq);
        }

        // ---------------------------------------------------------
        // BOT RULES (SpeedyAi Brain)
        // ---------------------------------------------------------
        $rules = [
            [
                'id' => 1,
                'context' => 'both',
                'pattern_type' => 'contains',
                'pattern_value' => 'hi,hello,hey,help,speedy,gm,gn',
                'response_text' => 'Hello! I am SpeedyAi. I am here to make your Fabilive experience faster and smoother. What can I do for you today?',
                'priority' => 100
            ],
            [
                'id' => 2,
                'context' => 'buyer',
                'pattern_type' => 'contains',
                'pattern_value' => 'order,track,delivery,rider,irder,parcel,where,package,ship,arrival',
                'response_text' => 'I can help with your order! You can track all active deliveries in your Buyer Dashboard under "Orders". Here is more info:',
                'suggested_faq_id' => 1,
                'priority' => 80
            ],
            [
                'id' => 3,
                'context' => 'buyer',
                'pattern_type' => 'contains',
                'pattern_value' => 'refund,cancel,return,money back,problem,issue,damaged,broken,fake',
                'response_text' => 'Need a refund or have a problem with an item? Our escrow protects you! Check this guide:',
                'suggested_faq_id' => 3,
                'priority' => 70
            ],
            [
                'id' => 4,
                'context' => 'both',
                'pattern_type' => 'contains',
                'pattern_value' => 'agent,human,person,talk,chat,live',
                'response_text' => 'If you need a real person, please click the "Request Live Support" button below. I will notify an available agent immediately!',
                'priority' => 200
            ]
        ];

        foreach ($rules as $rule) {
            DB::table('support_bot_rules')->updateOrInsert(['id' => $rule['id']], $rule);
        }

        echo "Seeding completed successfully!\n";
    }
}
