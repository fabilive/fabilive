<?php

namespace App\Services\AI;

use App\Models\Product;

class ListingGeneratorService extends AIService
{
    /**
     * Generate listing title, description, and price suggestion from photos.
     */
    public function generateFromPhoto(int $userId, string $categoryName, string $serviceArea = '', string $userNotes = ''): array
    {
        if (! $this->isFeatureEnabled('listing_generator')) {
            return ['error' => 'Listing generator is not enabled.'];
        }

        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a marketplace listing assistant for Fabilive, a Cameroon-based marketplace.
Generate a product listing in JSON format with these fields:
- title: catchy, descriptive title (max 100 chars)
- description: detailed product description (150-300 words), mention quality, condition, features
- suggested_price_xaf: price in XAF (Central African CFA franc), based on local market rates
- tags: array of 3-5 relevant tags

Respond ONLY with valid JSON, no markdown.',
            ],
            [
                'role' => 'user',
                'content' => "Generate a listing for category: {$categoryName}"
                    .($serviceArea ? ", location: {$serviceArea}" : '')
                    .($userNotes ? ". Seller notes: {$userNotes}" : ''),
            ],
        ];

        $response = $this->chatCompletion($messages, $userId, 'listing_gen');

        if (isset($response['error'])) {
            return $response;
        }

        $content = $this->extractContent($response);

        // Parse JSON response
        $data = json_decode($content, true);
        if (! $data) {
            return ['error' => 'Failed to parse AI suggestion.'];
        }

        // Add price heuristic: median price in category
        $medianPrice = $this->getCategoryMedianPrice($categoryName, $serviceArea);
        if ($medianPrice) {
            $data['median_price_xaf'] = $medianPrice;
        }

        return $data;
    }

    /**
     * Get median price for a category in a service area (heuristic).
     */
    protected function getCategoryMedianPrice(string $categoryName, string $serviceArea = ''): ?float
    {
        $query = Product::where('status', 1)
            ->where('price', '>', 0);

        // Try to match category
        $query->whereHas('category', function ($q) use ($categoryName) {
            $q->where('name', 'LIKE', "%{$categoryName}%");
        });

        $prices = $query->pluck('price')->sort()->values();

        if ($prices->isEmpty()) {
            return null;
        }

        $count = $prices->count();
        $mid = (int) ($count / 2);

        return $count % 2 === 0
            ? ($prices[$mid - 1] + $prices[$mid]) / 2
            : $prices[$mid];
    }
}
