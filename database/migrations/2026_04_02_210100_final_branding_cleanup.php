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
        // 1. Update bot rules (response_text)
        try {
            DB::table('support_bot_rules')->get()->each(function ($rule) {
                if (isset($rule->response_text)) {
                    $newText = str_ireplace(['SpeedyAi', 'Speedyi'], 'MbokoAi', $rule->response_text);
                    if ($newText !== $rule->response_text) {
                        DB::table('support_bot_rules')->where('id', $rule->id)->update(['response_text' => $newText]);
                    }
                }
            });
        } catch (\Exception $e) {
            // Silently fail if table not found
        }

        // 2. Update FAQs (questions and answers)
        try {
            DB::table('support_faqs')->get()->each(function ($faq) {
                $newQ = str_ireplace(['SpeedyAi', 'Speedyi'], 'MbokoAi', $faq->question);
                $newA = str_ireplace(['SpeedyAi', 'Speedyi'], 'MbokoAi', $faq->answer_html);
                
                if ($newQ !== $faq->question || $newA !== $faq->answer_html) {
                    DB::table('support_faqs')->where('id', $faq->id)->update([
                        'question' => $newQ,
                        'answer_html' => $newA
                    ]);
                }
            });
        } catch (\Exception $e) {
            // Silently fail if table not found
        }

        // 3. Update General Settings (if applicable)
        try {
            $gs = DB::table('general_settings')->first();
            if ($gs) {
                // If there are other branding fields, they could be updated here
            }
        } catch (\Exception $e) {
            // Silently fail if table not found
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse without potentially breaking other things
    }
};
