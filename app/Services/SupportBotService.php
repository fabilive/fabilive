<?php

namespace App\Services;

use App\Models\SupportBotRule;
use App\Models\SupportFaq;
use Illuminate\Support\Str;

class SupportBotService
{
    /**
     * Process a message and try to find a matching rule.
     *
     * @param  string  $context (buyer|vendor)
     */
    public function processMessage(string $message, string $context): ?array
    {
        $messageLower = Str::lower(trim($message));

        // 1. Check for localized / common greetings first
        $greetings = [
            'hello' => "Hello! I'm MbokoAi, your Fabilive assistant. How can I help you today?",
            'hi' => "Hi there! I'm MbokoAi. Ready to help with your orders or account.",
            'mboko' => "Mboko is here! 😊 How can I support you today?",
            'who are you' => "I am MbokoAi, the smart support bot for Fabilive. I can help you track orders, manage your shop, or find products.",
            'how are you' => "I'm doing great and ready to serve you! How about you?",
            'thanks' => "You're very welcome! Let me know if you need anything else.",
            'thank you' => "Happy to help! Fabilive is here for you.",
        ];

        foreach ($greetings as $key => $response) {
            if (Str::contains($messageLower, $key)) {
                return [
                    'response_text' => $response,
                    'suggested_faq' => null,
                    'rule_id' => 'greeting',
                ];
            }
        }

        // 2. Fallback to database rules
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
                ];
            }
        }

        return null;
    }


    /**
     * Determine if a rule matches the message.
     */
    private function ruleMatches(SupportBotRule $rule, string $message, string $messageLower): bool
    {
        $pattern = $rule->pattern_value;

        switch ($rule->pattern_type) {
            case 'keyword':
                // exact match word boundary
                $words = explode(',', $pattern);
                foreach ($words as $word) {
                    $word = trim(Str::lower($word));
                    if (preg_match('/\b'.preg_quote($word, '/').'\b/i', $message)) {
                        return true;
                    }
                }
                break;

            case 'regex':
                // Attempt to match regex
                try {
                    // Check if it has delimiters
                    $regex = $pattern;
                    if (! Str::startsWith($regex, '/')) {
                        $regex = '/'.$regex.'/i';
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
                // Simple substring and fuzzy match
                $parts = explode(',', $pattern);
                $messageWords = explode(' ', preg_replace('/[^\w\s]/', '', $messageLower));

                foreach ($parts as $part) {
                    $part = trim(Str::lower($part));
                    // 1. Direct substring match
                    if (Str::contains($messageLower, $part)) {
                        return true;
                    }

                    // 2. Fuzzy match against words (handles typos like 'irder')
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
}
