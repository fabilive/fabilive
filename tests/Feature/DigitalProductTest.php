<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DigitalProductTest extends TestCase
{
    use DatabaseTransactions;

    public function test_vendor_can_create_digital_product()
    {
        $vendor = User::factory()->create(['is_vendor' => 2]);
        $category = Category::factory()->create();

        $response = $this->actingAs($vendor, 'web')->postJson(route('vendor-prod-store'), [
            'type' => 'Digital',
            'name' => 'My Digital Book',
            'category_id' => $category->id,
            'price' => 15.00,
            'details' => 'A great ebook',
            'policy' => 'No returns on digital items',
            'photo' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==', // dummy base64
            'type_check' => 1,
            'file' => UploadedFile::fake()->create('book.zip', 100, 'application/zip'),
            // Removed product_location, product_city, delivery_fee as per our front-end fix
            'product_location' => 1,
            'product_city' => 1,
            'delivery_fee' => 0,
            'delivery_unit' => 'gram'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'type' => 'Digital',
            'name' => 'My Digital Book',
            'user_id' => $vendor->id,
            'price' => 15.00,
            'delivery_fee' => 0
        ]);
    }
}
