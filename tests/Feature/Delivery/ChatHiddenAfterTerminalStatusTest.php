<?php

namespace Tests\Feature\Delivery;

use App\Models\DeliveryChatThread;
use App\Models\DeliveryJob;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ChatHiddenAfterTerminalStatusTest extends TestCase
{
    use DatabaseTransactions;

    public function test_participants_cannot_access_hidden_chat()
    {
        $rider = User::factory()->create();
        $buyer = User::factory()->create();
        $job = DeliveryJob::factory()->create();
        
        $thread = DeliveryChatThread::create([
            'delivery_job_id' => $job->id,
            'rider_id' => $rider->id,
            'buyer_id' => $buyer->id,
            'thread_type' => 'rider_buyer',
            'hidden_at' => now()
        ]);

        $this->actingAs($rider, 'rider-api')
            ->getJson("/api/delivery/chat/messages/{$thread->id}")
            ->assertStatus(403);

        $this->actingAs($buyer, 'api')
            ->getJson("/api/delivery/chat/messages/{$thread->id}")
            ->assertStatus(403);
            
        $admin = \App\Models\Admin::first() ?: \App\Models\Admin::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password')
        ]);
        $this->actingAs($admin, 'admin')
            ->getJson("/api/delivery/chat/messages/{$thread->id}")
            ->assertStatus(200);
    }
}
