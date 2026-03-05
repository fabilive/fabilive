<?php

namespace Tests\Feature\Delivery;

use App\Models\DeliveryChatThread;
use App\Models\DeliveryJob;
use App\Models\User;
use App\Http\Middleware\EnsureDeliveryChatAccess;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NoBuyerSellerChatPolicyTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("SET SESSION sql_mode=''");
    }

    public function test_buyer_cannot_access_seller_chat_thread_directly()
    {
        // Create participants
        $buyer = User::factory()->create();
        $seller = User::factory()->create(['is_vendor' => 1]);
        $rider = User::factory()->create();

        // Create a delivery job and a rider↔seller thread (buyer is NOT a participant)
        $job = DeliveryJob::factory()->create(['status' => 'picking_up']);
        $thread = DeliveryChatThread::create([
            'delivery_job_id' => $job->id,
            'thread_type'     => 'rider_seller',
            'seller_id'       => $seller->id,
            'buyer_id'        => null,
            'rider_id'        => $rider->id,
        ]);

        // Simulate middleware check as the buyer (who is NOT a participant)
        $middleware = new EnsureDeliveryChatAccess();

        $request = Request::create("/api/delivery-chat/{$thread->id}/messages", 'GET');
        $request->setRouteResolver(function () use ($thread) {
            $route = new \Illuminate\Routing\Route('GET', '/api/delivery-chat/{chat_thread_id}/messages', []);
            $route->bind(Request::create("/api/delivery-chat/{$thread->id}/messages"));
            $route->setParameter('chat_thread_id', $thread->id);
            return $route;
        });

        // Act as buyer through the auth facade
        $this->actingAs($buyer, 'api');

        $response = $middleware->handle($request, function ($req) {
            return response()->json(['status' => 'ok'], 200);
        });

        // Buyer should be forbidden (403) — they are not a participant
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('Unauthorized access', $response->getContent());

        // Also verify: no buyer↔seller-only thread type should exist
        $this->assertDatabaseMissing('delivery_chat_threads', [
            'thread_type' => 'buyer_seller',
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
        ]);
    }
}
