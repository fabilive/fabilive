<?php

namespace App\Services\AI;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class MultilingualAssistantService extends AIService
{
    /**
     * Start or continue a conversation with the AI assistant.
     */
    public function reply(int $userId, string $message, array $history = []): array
    {
        if (! $this->isFeatureEnabled('multilingual_assistant')) {
            return ['error' => 'Assistant feature is not enabled.'];
        }

        // Check rate limit (stricter for chat)
        if (! $this->checkRateLimit($userId, 'assistant')) {
            return ['error' => 'You are sending messages too quickly. Please try again in a minute.', 'fallback' => true];
        }

        // Build prompt messages
        $messages = [
            ['role' => 'system', 'content' => $this->getSystemPrompt()],
        ];

        // Add history
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg['role'] ?? 'user',
                'content' => $msg['content'] ?? '',
            ];
        }

        // Add RAG context if applicable
        $context = $this->gatherContext($message);
        if ($context) {
            $messages[] = [
                'role' => 'system',
                'content' => "RELEVANT PLATFORM RULES/CONTEXT:\n".$context,
            ];
        }

        // Add current user message
        $messages[] = ['role' => 'user', 'content' => $message];

        // 1. First pass: Check if the user is asking for a specific tool action (listing draft, search)
        // For simplicity, we implement a naive router here. In production, we'd use function calling.
        $action = $this->determineAction($message);

        if ($action['type'] === 'draft_listing') {
            return $this->handleDraftListing($userId, $message, $messages);
        } elseif ($action['type'] === 'search') {
            return $this->handleSearch($message, $messages);
        }

        // 2. Standard reply
        $response = $this->chatCompletion($messages, $userId, 'assistant');

        if (isset($response['error'])) {
            return ['error' => 'Sorry, I am having trouble connecting right now. Please try again later.', 'fallback' => true];
        }

        return [
            'reply' => $this->extractContent($response),
            'action' => null,
        ];
    }

    /**
     * Get the system prompt (incorporating the Pidgin style guide where possible).
     */
    protected function getSystemPrompt(): string
    {
        $path = resource_path('ai/assistant_prompts/system.md');
        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return 'You are the Fabilive Assistant. You speak English, French, and Cameroon Pidgin. Be helpful and brief.';
    }

    /**
     * Naive intent detection for wiring Tools.
     */
    protected function determineAction(string $message): array
    {
        $message = strtolower($message);

        // Draft Listing Intent
        if (preg_match('/(sell|post|list|draft|create).*(phone|laptop|shoe|bag|car|item)/i', $message)) {
            return ['type' => 'draft_listing'];
        }

        // Search Intent
        if (preg_match('/(find|search|buy|looking for|want).*(phone|laptop|shoe|bag|car|item)/i', $message)) {
            return ['type' => 'search'];
        }

        return ['type' => 'general'];
    }

    /**
     * Handle the draft_listing tool flow.
     */
    protected function handleDraftListing(int $userId, string $message, array $messages): array
    {
        // Inject a specific prompt to extract the product details
        $messages[] = [
            'role' => 'system',
            'content' => 'The user wants to sell an item. Extract the item name, condition, and any details. If they did not provide enough info to write a good description, ask them for details. If they did, draft a JSON with title, description, and price (XAF), and also reply with a friendly message.',
        ];

        $response = $this->chatCompletion($messages, $userId, 'assistant');
        $content = $this->extractContent($response);

        // Very naive JSON extraction from the reply
        preg_match('/\{.*\}/s', $content, $matches);
        $draft = [];
        if (! empty($matches[0])) {
            $draft = json_decode($matches[0], true) ?? [];
            // Remove the JSON from the text response
            $content = trim(str_replace($matches[0], '', $content));
        }

        return [
            'reply' => $content ?: 'I can help you sell that! What condition is it in and how much do you want for it?',
            'action' => 'draft_listing',
            'data' => $draft,
        ];
    }

    /**
     * Handle the search tool flow.
     */
    protected function handleSearch(string $message, array $messages): array
    {
        // Simple keyword extraction for the DB query (mocked NLP)
        $keywords = explode(' ', strtolower(preg_replace('/[^a-z0-9 ]/i', '', $message)));
        $stopWords = ['i', 'want', 'to', 'buy', 'find', 'search', 'looking', 'for', 'a', 'an', 'the', 'some'];
        $terms = array_diff($keywords, $stopWords);
        $searchQuery = implode(' ', $terms);

        $results = [];
        if (! empty($searchQuery)) {
            $results = DB::table('products')
                ->where('status', 1)
                ->where('name', 'LIKE', '%'.current($terms).'%')
                ->select('id', 'name', 'price', 'photo')
                ->limit(3)
                ->get()
                ->toArray();
        }

        if (empty($results)) {
            return [
                'reply' => "I couldn't find exactly what you're looking for right now. (A no see that one o). Check back later!",
                'action' => 'search',
                'data' => [],
            ];
        }

        return [
            'reply' => 'Here are some items I found for you:',
            'action' => 'search',
            'data' => $results,
        ];
    }

    /**
     * Gather RAG context (mocked for now, would use embeddings/vector DB).
     */
    protected function gatherContext(string $message): ?string
    {
        $message = strtolower($message);

        if (str_contains($message, 'scam') || str_contains($message, 'wayo') || str_contains($message, 'safe')) {
            return 'Fabilive Safety: Never pay outside the platform. All payments go into the Escrow Wallet. The seller only gets paid after Admin verifies delivery. If anyone asks you for WhatsApp or Western Union, it is a scam.';
        }

        if (str_contains($message, 'delivery') || str_contains($message, 'rider')) {
            return 'Delivery: Buyers and sellers cannot chat directly. Delivery Agents pick up the item from the seller and bring it to the buyer. The seller must provide a code to the rider. The rider must upload proof photos.';
        }

        return null; // No specific context
    }
}
