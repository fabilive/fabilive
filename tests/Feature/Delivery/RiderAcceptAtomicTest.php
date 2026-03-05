<?php

namespace Tests\Feature\Delivery;

use App\Models\DeliveryJob;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RiderAcceptAtomicTest extends TestCase
{
    use DatabaseTransactions;

    public function test_only_one_rider_can_accept_available_job()
    {
        $job = DeliveryJob::factory()->create(['status' => 'available']);
        $rider1 = User::factory()->create();
        $rider2 = User::factory()->create();

        // Simulate simultaneous acceptance (sequential for test but logic uses lockForUpdate)
        $this->actingAs($rider1, 'rider-api')
            ->postJson("/api/delivery/rider/accept/{$job->id}")
            ->assertStatus(200);

        $this->actingAs($rider2, 'rider-api')
            ->postJson("/api/delivery/rider/accept/{$job->id}")
            ->assertStatus(400); // Should fail as status changed
    }
}
