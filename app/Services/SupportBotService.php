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
     * @param string $message
     * @param string $context (buyer|vendor)
     * @return array|null
     */
    public function processMessage(string $message, string $context): ?array
    {
        // Get active rules for this context or 'both', ordered by highest priority
        $rules = SupportBotRule::where(function($query) use ($context) {
                $query->where('context', $context)->orWhere('context', 'both');
            })
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        $messageLower = Str::lower($message);

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
                    'rule_id' => $rule->id
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
                    if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $message)) {
                        return true;
                    }
                }
                break;
                
            case 'regex':
                // Attempt to match regex
                try {
                    // Check if it has delimiters
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
