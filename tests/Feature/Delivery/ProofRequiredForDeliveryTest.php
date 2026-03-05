<?php

namespace Tests\Feature\Delivery;

use App\Models\DeliveryJob;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProofRequiredForDeliveryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_rider_cannot_mark_delivered_without_photo()
    {
        $rider = User::factory()->create();
        $job = DeliveryJob::factory()->create([
            'status' => 'assigned',
            'assigned_rider_id' => $rider->id
        ]);

        $response = $this->actingAs($rider, 'rider-api')
            ->postJson("/api/delivery/rider/deliver/{$job->id}", []);

        $job->refresh();
        $this->assertNotEquals('delivered_pending_verification', $job->status);
    }
}
