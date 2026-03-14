<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your AI provider. Supported: openai, gemini, anthropic
    |
    */

    'provider' => env('AI_PROVIDER', 'openai'),

    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 2048),
        ],
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
        ],
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-haiku-20240307'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limits' => [
        'requests_per_minute' => env('AI_RATE_LIMIT_PER_MINUTE', 10),
        'requests_per_hour' => env('AI_RATE_LIMIT_PER_HOUR', 100),
        'requests_per_day' => env('AI_RATE_LIMIT_PER_DAY', 500),
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Enable/disable AI features per environment.
    |
    */

    'features' => [
        'multilingual_assistant' => env('AI_FEATURE_ASSISTANT', false),
        'listing_generator' => env('AI_FEATURE_LISTING_GEN', false),
        'photo_enhancer' => env('AI_FEATURE_PHOTO_ENHANCE', false),
        'anti_scam' => env('AI_FEATURE_ANTI_SCAM', false),
        'reputation_badges' => env('AI_FEATURE_REPUTATION', false),
        'smart_notifications' => env('AI_FEATURE_SMART_NOTIF', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | 3D Model Generation
    |--------------------------------------------------------------------------
    */
    '3d' => [
        'provider' => env('AI_3D_PROVIDER', 'mock'), // mock, tripo, meshy
        'api_key' => env('AI_3D_API_KEY'),
        'output_path' => 'assets/models/products',
    ],

    /*
    |--------------------------------------------------------------------------
    | Photo Enhancement
    |--------------------------------------------------------------------------
    */

    'photo' => [
        'provider' => env('AI_PHOTO_PROVIDER', 'local'), // local, cloudinary
        'cloudinary' => [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key' => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ],
        'output_formats' => ['webp', 'jpg'],
        'sizes' => [
            'thumbnail' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 600, 'height' => 600],
            'large' => ['width' => 1200, 'height' => 1200],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Knowledge Base (for RAG)
    |--------------------------------------------------------------------------
    */

    'knowledge' => [
        'storage_path' => storage_path('app/ai/knowledge'),
        'embedding_model' => env('AI_EMBEDDING_MODEL', 'text-embedding-3-small'),
    ],
];
