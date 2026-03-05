<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryJob>
 */
class DeliveryJobFactory extends Factory
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
            'buyer_id' => \App\Models\User::factory(),
            'service_area_id' => 1,
            'status' => 'pending_readiness',
            'base_fee' => 100,
            'stopover_fee' => 20,
            'sellers_count' => 1,
            'delivery_fee_total' => 120,
            'platform_delivery_commission' => 10,
            'rider_earnings' => 110,
        ];
    }
}
