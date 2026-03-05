<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AssistantTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('ai.features.multilingual_assistant', true);
        Config::set('ai.provider', 'openai');
        Config::set('ai.providers.openai.api_key', 'test-key');
    }

    public function test_assistant_returns_error_if_disabled()
    {
        Config::set('ai.features.multilingual_assistant', false);

        $response = $this->postJson('/api/assistant/reply', [
            'message' => 'Hello',
        ]);

        $response->assertStatus(429) // 429 logic handles expected errors from our app
                 ->assertJson([
                     'success' => false,
                     'message' => 'Assistant feature is not enabled.'
                 ]);
    }

    public function test_assistant_replies_successfully()
    {
        // Mock the HTTP call to the AI provider
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'Welcome for Fabilive! How I fit help you?']]
                ],
                'usage' => ['prompt_tokens' => 10, 'completion_tokens' => 10]
            ], 200)
        ]);

        $response = $this->postJson('/api/assistant/reply', [
            'message' => 'Hello',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
                 
        $this->assertEquals(
            'Welcome for Fabilive! How I fit help you?',
            $response->json('data.reply')
        );
    }

    public function test_assistant_handles_provider_failure_gracefully()
    {
        // Mock a 500 failure from the API
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response('Server Error', 500)
        ]);

        $response = $this->postJson('/api/assistant/reply', [
            'message' => 'Hello',
        ]);

        // AIService handles exceptions and returns an array with 'error'
        $response->assertStatus(429)
                 ->assertJson([
                     'success' => false,
                     'fallback' => true,
                 ]);
    }

    public function test_assistant_detects_draft_intent()
    {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => '{"title": "Samsung Phone", "description": "Good", "price": 50000} Sure!']]
                ],
            ], 200)
        ]);

        $response = $this->postJson('/api/assistant/reply', [
            'message' => 'I want to sell my samsung phone',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('draft_listing', $response->json('data.action'));
        $this->assertEquals('Samsung Phone', $response->json('data.data.title'));
    }
}
