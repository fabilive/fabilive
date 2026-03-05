<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AIService
{
    protected string $provider;
    protected array $config;

    public function __construct()
    {
        $this->provider = config('ai.provider', 'openai');
        $this->config = config("ai.providers.{$this->provider}", []);
    }

    /**
     * Check if a specific AI feature is enabled.
     */
    public function isFeatureEnabled(string $feature): bool
    {
        return config("ai.features.{$feature}", false);
    }

    /**
     * Check rate limit for a user on a specific feature.
     */
    public function checkRateLimit(int $userId, string $feature): bool
    {
        $key = "ai:{$feature}:{$userId}";
        $perMinute = config('ai.rate_limits.requests_per_minute', 10);

        if (RateLimiter::tooManyAttempts($key, $perMinute)) {
            $this->auditLog($userId, $feature, 'rate_limited');
            return false;
        }

        RateLimiter::hit($key, 60);
        return true;
    }

    /**
     * Make a chat completion request to the configured AI provider.
     */
    public function chatCompletion(array $messages, int $userId = null, string $feature = 'general', array $options = []): ?array
    {
        if (!$this->checkRateLimit($userId ?? 0, $feature)) {
            return ['error' => 'Rate limit exceeded. Please try again later.'];
        }

        $startTime = microtime(true);

        try {
            $response = $this->callProvider($messages, $options);
            $elapsed = (int)((microtime(true) - $startTime) * 1000);

            $this->auditLog($userId, $feature, 'success', [
                'input_tokens' => $response['usage']['prompt_tokens'] ?? null,
                'output_tokens' => $response['usage']['completion_tokens'] ?? null,
                'response_time_ms' => $elapsed,
                'model' => $this->config['model'] ?? null,
            ]);

            return $response;
        } catch (\Exception $e) {
            $elapsed = (int)((microtime(true) - $startTime) * 1000);

            $this->auditLog($userId, $feature, 'error', [
                'error_message' => $e->getMessage(),
                'response_time_ms' => $elapsed,
            ]);

            Log::error("AI call failed [{$feature}]: " . $e->getMessage());
            return ['error' => 'AI service unavailable. Please try again later.'];
        }
    }

    /**
     * Call the configured AI provider.
     */
    protected function callProvider(array $messages, array $options = []): array
    {
        return match ($this->provider) {
            'openai' => $this->callOpenAI($messages, $options),
            'gemini' => $this->callGemini($messages, $options),
            'anthropic' => $this->callAnthropic($messages, $options),
            default => throw new \Exception("Unsupported AI provider: {$this->provider}"),
        };
    }

    protected function callOpenAI(array $messages, array $options = []): array
    {
        $response = Http::withToken($this->config['api_key'])
            ->timeout(30)
            ->post($this->config['base_url'] . '/chat/completions', array_merge([
                'model' => $this->config['model'],
                'messages' => $messages,
                'max_tokens' => $this->config['max_tokens'] ?? 2048,
            ], $options));

        if (!$response->successful()) {
            throw new \Exception("OpenAI API error: " . $response->body());
        }

        return $response->json();
    }

    protected function callGemini(array $messages, array $options = []): array
    {
        $contents = array_map(function ($msg) {
            return [
                'role' => $msg['role'] === 'assistant' ? 'model' : $msg['role'],
                'parts' => [['text' => $msg['content']]],
            ];
        }, $messages);

        $response = Http::timeout(30)
            ->post($this->config['base_url'] . '/models/' . $this->config['model'] . ':generateContent?key=' . $this->config['api_key'], [
                'contents' => $contents,
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gemini API error: " . $response->body());
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return [
            'choices' => [['message' => ['content' => $text]]],
            'usage' => $data['usageMetadata'] ?? [],
        ];
    }

    protected function callAnthropic(array $messages, array $options = []): array
    {
        $system = '';
        $filtered = [];
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $system .= $msg['content'] . "\n";
            } else {
                $filtered[] = $msg;
            }
        }

        $response = Http::withHeaders([
            'x-api-key' => $this->config['api_key'],
            'anthropic-version' => '2023-06-01',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->config['model'],
            'max_tokens' => 2048,
            'system' => $system,
            'messages' => $filtered,
        ]);

        if (!$response->successful()) {
            throw new \Exception("Anthropic API error: " . $response->body());
        }

        $data = $response->json();
        return [
            'choices' => [['message' => ['content' => $data['content'][0]['text'] ?? '']]],
            'usage' => $data['usage'] ?? [],
        ];
    }

    /**
     * Extract the text content from a provider response.
     */
    public function extractContent(array $response): string
    {
        if (isset($response['error'])) {
            return '';
        }

        return $response['choices'][0]['message']['content'] ?? '';
    }

    /**
     * Log an AI usage event for audit/billing.
     */
    protected function auditLog(int $userId = null, string $feature = 'general', string $status = 'success', array $extra = []): void
    {
        try {
            DB::table('ai_audit_logs')->insert(array_merge([
                'user_id' => $userId,
                'feature' => $feature,
                'provider' => $this->provider,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ], $extra));
        } catch (\Exception $e) {
            Log::error("Failed to write AI audit log: " . $e->getMessage());
        }
    }
}
