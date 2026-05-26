<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Process an image to fit exactly within a specific dimension by placing it on a white canvas,
     * maintaining its aspect ratio. Optionally adds a watermark.
     *
     * @param mixed $fileData The image data (file path, UploadedFile, binary data, etc.)
     * @param string $path The directory path to save the image to
     * @param int $width The target canvas width
     * @param int $height The target canvas height
     * @param bool $watermark Whether to apply the Fabilive watermark
     * @param string $forceExtension The extension to save the file as (e.g., 'jpg', 'png')
     * @return string The generated filename
     */
    public static function processImage($fileData, $path, $width = 800, $height = 800, $watermark = true, $forceExtension = 'jpg')
    {
        try {
            $img = Image::make($fileData);
        } catch (\Exception $e) {
            \Log::error('ImageHelper: Failed to make image. ' . $e->getMessage());
            throw $e;
        }

        // Resize the image so that the largest side fits within the limit; the smaller
        // side will be scaled to maintain the original aspect ratio.
        // We prevent upscaling small images to avoid pixelation.
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Create a new canvas with a white background to ensure the final image is exactly
        // the requested dimensions (prevents layout shifting on frontend).
        $canvas = Image::canvas($width, $height, '#ffffff');

        // Insert the resized image into the center of the canvas
        $canvas->insert($img, 'center');

        // Apply watermark if requested
        if ($watermark) {
            $watermarkPath = public_path('assets/front/images/watermark.png');
            if (file_exists($watermarkPath)) {
                // Insert watermark at bottom-right with 10px offset
                $canvas->insert($watermarkPath, 'bottom-right', 10, 10);
            }
        }

        $filename = time() . Str::random(8) . '.' . ltrim($forceExtension, '.');
        $fullPath = rtrim($path, '/') . '/' . $filename;

        // Ensure directory exists
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $canvas->save($fullPath);

        return $filename;
    }

    /**
     * Specialized helper for processing the main product photo from base64 data
     * (commonly used in Fabilive product controllers).
     *
     * @param string $base64Data The base64 encoded image string
     * @param string $basePath The root public directory path (e.g., public_path())
     * @return array Associative array containing 'photo' and 'thumbnail' filenames
     */
    public static function processBase64Photo($base64Data, $basePath)
    {
        // Clean base64 string if it contains data URI scheme headers
        if (strpos($base64Data, ';') !== false && strpos($base64Data, ',') !== false) {
            [$type, $base64Data] = explode(';', $base64Data);
            [, $base64Data] = explode(',', $base64Data);
        }
        $imageData = base64_decode($base64Data);

        $productsPath = $basePath . '/assets/images/products';
        $thumbnailsPath = $basePath . '/assets/images/thumbnails';

        // 1. Process Main Image (800x800) WITH watermark, save as PNG (preserve quality)
        $mainFileName = self::processImage($imageData, $productsPath, 800, 800, true, 'png');

        // 2. Process Thumbnail (285x285) WITHOUT watermark, save as JPG
        $thumbnailName = self::processImage($imageData, $thumbnailsPath, 285, 285, false, 'jpg');

        return [
            'photo' => $mainFileName,
            'thumbnail' => $thumbnailName
        ];
    }
}
