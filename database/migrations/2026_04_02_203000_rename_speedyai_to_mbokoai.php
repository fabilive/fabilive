<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update bot rules
        DB::table('support_bot_rules')->get()->each(function ($rule) {
            $newText = str_replace(['SpeedyAi', 'Speedyi', 'speedyai', 'speedyi'], 'MbokoAi', $rule->response_text);
            if ($newText !== $rule->response_text) {
                DB::table('support_bot_rules')->where('id', $rule->id)->update(['response_text' => $newText]);
            }
        });

        // 2. Update FAQs (questions and answers)
        DB::table('support_faqs')->get()->each(function ($faq) {
            $newQ = str_replace(['SpeedyAi', 'Speedyi', 'speedyai', 'speedyi'], 'MbokoAi', $faq->question);
            $newA = str_replace(['SpeedyAi', 'Speedyi', 'speedyai', 'speedyi'], 'MbokoAi', $faq->answer_html);
            
            if ($newQ !== $faq->question || $newA !== $faq->answer_html) {
                DB::table('support_faqs')->where('id', $faq->id)->update([
                    'question' => $newQ,
                    'answer_html' => $newA
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse without potentially breaking other things, 
        // so we'll just leave it.
    }
};
