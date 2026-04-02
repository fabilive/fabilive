<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\SupportConversation;
use App\Models\SupportBotRule;
use App\Models\SupportAgent;

class SupportSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_search_returns_correct_items_per_context()
    {
        $response = $this->getJson(route('support.faqs.get', ['context' => 'buyer']));
        $response->assertStatus(200)
                 ->assertJsonStructure(['categories']);
    }

    public function test_bot_responds_to_authenticated_user()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'web')->postJson(route('support.bot.chat'), [
            'context' => 'buyer',
            'message' => 'I want a refund'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'conversation_id', 'bot_message']);
    }

    public function test_unauthenticated_user_cannot_access_bot()
    {
        $response = $this->postJson(route('support.bot.chat'), [
            'context' => 'buyer',
            'message' => 'Help me'
        ]);

        $response->assertStatus(401);
    }
    
    public function test_escalation_assigns_exactly_one_agent()
    {
        $user = User::factory()->create();
        $admin = Admin::create([
            'name' => 'Agent Test',
            'email' => 'agent@test.com',
            'password' => bcrypt('password'),
            'role_id' => 1
        ]);
        
        SupportAgent::create([
            'admin_id' => $admin->id,
            'is_online' => true,
            'max_active_chats' => 5
        ]);

        $conversation = SupportConversation::create([
            'requester_user_id' => $user->id,
            'context' => 'buyer',
            'status' => 'bot_active'
        ]);

        // Attempt escalation
        $response = $this->actingAs($user, 'web')->postJson(route('support.live.request'), [
            'conversation_id' => $conversation->id
        ]);

        $response->assertStatus(200);
        $this->assertEquals('assigned', $response->json('conversation.status'));
        $this->assertEquals($admin->id, $response->json('conversation.assigned_agent_admin_id'));
    }

    public function test_unauthorized_user_cannot_access_another_conversation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = SupportConversation::create([
            'requester_user_id' => $user1->id,
            'context' => 'buyer',
            'status' => 'bot_active'
        ]);

        // User 2 trying to send message to User 1's chat
        $response = $this->actingAs($user2, 'web')->postJson(route('support.chat.send'), [
            'conversation_id' => $conversation->id,
            'message' => 'Hacking your chat'
        ]);

        $response->assertStatus(403);
    }

    public function test_rating_cannot_be_submitted_twice()
    {
        $user = User::factory()->create();

        $conversation = SupportConversation::create([
            'requester_user_id' => $user->id,
            'context' => 'buyer',
            'status' => 'ended'
        ]);

        // First rating
        $res1 = $this->actingAs($user, 'web')->postJson(route('support.chat.rate'), [
            'conversation_id' => $conversation->id,
            'rating' => 5,
            'comment' => 'Great work!'
        ]);
        $res1->assertStatus(200);

        // Second rating should fail
        $res2 = $this->actingAs($user, 'web')->postJson(route('support.chat.rate'), [
            'conversation_id' => $conversation->id,
            'rating' => 1
        ]);
        
        $res2->assertStatus(200)->assertJson(['status' => 'error', 'message' => 'You have already rated this conversation.']);
    }
}
