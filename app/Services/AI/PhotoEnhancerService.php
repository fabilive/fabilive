<?php

namespace App\Services\AI;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PhotoEnhancerService extends AIService
{
    /**
     * Enhance a photo and generate optimized variants via Cloudinary.
     * Returns an array of paths: ['original' => url, 'thumbnail' => url, 'medium' => url]
     * If Cloudinary is disabled or fails, falls back to local storage.
     */
    public function optimizeAndStore(string $filePath, string $originalName, string $folder = 'products'): array
    {
        // Fallback or if feature disabled
        if (!$this->isFeatureEnabled('photo_enhancer') || config('ai.photo.provider') !== 'cloudinary') {
            return $this->storeLocalFallback($filePath, $originalName, $folder);
        }

        try {
            return $this->uploadToCloudinary($filePath, $originalName, $folder);
        } catch (\Exception $e) {
            Log::error("Cloudinary enhance failed, falling back to local: " . $e->getMessage());
            return $this->storeLocalFallback($filePath, $originalName, $folder);
        }
    }

    /**
     * Upload an image to Cloudinary and return optimized delivery URLs.
     */
    protected function uploadToCloudinary(string $filePath, string $originalName, string $folder): array
    {
        $config = config('ai.photo.cloudinary');
        if (empty($config['cloud_name']) || empty($config['api_key']) || empty($config['api_secret'])) {
            throw new \Exception("Cloudinary credentials not configured.");
        }

        $cloudName = $config['cloud_name'];
        $timestamp = time();
        $publicId = $folder . '/' . Str::random(16);

        // Parameters to sign
        $params = [
            'background_removal' => 'pixelz',
            'folder' => $folder,
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];

        // Generate signature: SHA-1 of ordered params + secret (alphabetical order)
        $signatureString = 'background_removal=pixelz&folder=' . $folder . '&public_id=' . $publicId . '&timestamp=' . $timestamp . $config['api_secret'];
        $signature = sha1($signatureString);

        $response = Http::attach(
            'file', file_get_contents($filePath), $originalName
        )->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
            'api_key' => $config['api_key'],
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder' => $folder,
            'public_id' => $publicId,
            'background_removal' => 'pixelz'
        ]);

        if (!$response->successful()) {
            throw new \Exception("Cloudinary API Error: " . $response->body());
        }

        $data = $response->json();
        $basePublicId = $data['public_id'];

        // Cloudinary URL structure
        
        // 1. Original - VIESUS color correction on the fly (Pixelz is processing the background asynchronously)
        $originalUrl = "https://res.cloudinary.com/{$cloudName}/image/upload/f_auto,q_auto,e_viesus_correct,b_white/{$basePublicId}.webp";

        // 2. Medium variant
        $medConfig = config('ai.photo.sizes.medium');
        $medW = $medConfig['width'] ?? 600;
        $medH = $medConfig['height'] ?? 600;
        $mediumUrl = "https://res.cloudinary.com/{$cloudName}/image/upload/c_fill,w_{$medW},h_{$medH},f_auto,q_auto,e_viesus_correct,b_white/{$basePublicId}.webp";

        // 3. Thumbnail variant
        $thumbConfig = config('ai.photo.sizes.thumbnail');
        $thumbW = $thumbConfig['width'] ?? 150;
        $thumbH = $thumbConfig['height'] ?? 150;
        $thumbnailUrl = "https://res.cloudinary.com/{$cloudName}/image/upload/c_fill,w_{$thumbW},h_{$thumbH},f_auto,q_auto,e_viesus_correct,b_white/{$basePublicId}.webp";

        return [
            'original' => $originalUrl,
            'medium' => $mediumUrl,
            'thumbnail' => $thumbnailUrl,
            'provider' => 'cloudinary'
        ];
    }

    /**
     * Native Laravel storage fallback if Cloudinary is unavailable or disabled.
     */
    protected function storeLocalFallback(string $filePath, string $originalName, string $folder): array
    {
        // For consistency, we return only the filename for local storage.
        // The View accessors in Product model will handle the pathing.
        $filename = basename($filePath);

        return [
            'original' => $filename,
            'medium' => $filename, 
            'thumbnail' => $filename,
            'provider' => 'local'
        ];
    }
}
