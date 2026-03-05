<?php

namespace Tests\Feature;

use App\Services\AI\PhotoEnhancerService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PhotoEnhancerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::set('ai.features.photo_enhancer', true);
        Config::set('ai.photo.provider', 'cloudinary');
        Config::set('ai.photo.cloudinary', [
            'cloud_name' => 'test_cloud',
            'api_key' => 'test_key',
            'api_secret' => 'test_secret',
        ]);
        Config::set('ai.photo.sizes.medium', ['width' => 600, 'height' => 600]);
        Config::set('ai.photo.sizes.thumbnail', ['width' => 150, 'height' => 150]);
    }

    public function test_photo_enhancer_uses_fallback_if_disabled()
    {
        Config::set('ai.features.photo_enhancer', false);

        $service = new PhotoEnhancerService();
        $file = UploadedFile::fake()->image('test_product.jpg');

        $result = $service->optimizeAndStore($file->getPathname(), 'test_product.jpg', 'products');

        $this->assertEquals('local', $result['provider']);
        $this->assertStringContainsString('assets/images/products/', $result['original']);
    }

    public function test_photo_enhancer_uploads_to_cloudinary()
    {
        Http::fake([
            'api.cloudinary.com/*' => Http::response([
                'public_id' => 'products/random123',
                'format' => 'jpg',
                'secure_url' => 'https://res.cloudinary.com/test_cloud/image/upload/v1234/products/random123.jpg',
            ], 200)
        ]);

        $service = new PhotoEnhancerService();
        $file = UploadedFile::fake()->image('test_product.jpg');

        $result = $service->optimizeAndStore($file->getPathname(), 'test_product.jpg', 'products');

        $this->assertEquals('cloudinary', $result['provider']);
        $this->assertStringContainsString('f_auto,q_auto,e_improve', $result['original']);
        $this->assertStringContainsString('products/random123.webp', $result['original']);
        $this->assertStringContainsString('w_600,h_600', $result['medium']);
        $this->assertStringContainsString('w_150,h_150', $result['thumbnail']);
    }

    public function test_photo_enhancer_falls_back_on_api_error()
    {
        Http::fake([
            'api.cloudinary.com/*' => Http::response('Unauthorized', 401)
        ]);

        $service = new PhotoEnhancerService();
        $file = UploadedFile::fake()->image('test_product.jpg');

        $result = $service->optimizeAndStore($file->getPathname(), 'test_product.jpg', 'products');

        $this->assertEquals('local', $result['provider']);
        $this->assertStringContainsString('assets/images/products/', $result['original']);
    }
}
