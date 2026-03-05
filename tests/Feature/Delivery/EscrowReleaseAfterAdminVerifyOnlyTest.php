<?php

namespace Tests\Feature\Delivery;

use App\Models\DeliveryJob;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EscrowReleaseAfterAdminVerifyOnlyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_escrow_not_released_until_admin_verified()
    {
        $rider = User::factory()->create();
        $job = DeliveryJob::factory()->create(['status' => 'delivered_pending_verification']);
        
        $order = $job->order;
        $this->assertFalse((bool)$order->admin_verified);

        // Try to access verification as rider (should fail)
        $this->actingAs($rider, 'rider-api')
            ->postJson("/admin/delivery/verify/{$job->id}")
            ->assertStatus(302); 

        $order->refresh();
        $this->assertFalse((bool)$order->admin_verified);
    }
}
