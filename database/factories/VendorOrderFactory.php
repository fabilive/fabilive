<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VendorOrder>
 */
class VendorOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'user_id' => \App\Models\User::factory(),
            'order_number' => $this->faker->numberBetween(100000, 999999),
            'qty' => 1,
            'price' => 1000,
            'status' => 'pending',
        ];
    }
}
