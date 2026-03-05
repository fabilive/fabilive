<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'cart' => json_encode(['items' => []]),
            'method' => 'system',
            'order_number' => $this->faker->unique()->numberBetween(100000, 999999),
            'pay_amount' => 1000,
            'total_delivery_fee' => 100,
            'totalQty' => 1,
            'service_area_id' => 1,
            'tax' => 0,
            'shipping_cost' => 0,
            'packing_cost' => 0,
            'wallet_price' => 0,
            'status' => 'pending',
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->safeEmail,
            'customer_phone' => $this->faker->phoneNumber,
            'customer_address' => $this->faker->address,
            'customer_city' => 'Test City',
            'customer_zip' => '12345',
            'customer_country' => 'UK',
            'currency_sign' => '$',
            'currency_name' => 'USD',
            'currency_value' => 1,
        ];
    }
}
