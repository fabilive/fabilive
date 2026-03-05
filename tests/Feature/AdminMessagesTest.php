<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AdminUserConversation;
use App\Models\AdminUserMessage;
use App\Models\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminMessagesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_send_ticket_to_admin()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->postJson(route('user-admin-message'), [
            'type' => 'Ticket',
            'subject' => 'Help with my account',
            'message' => 'I cannot update my profile picture.',
        ]);

        $response->assertJson(['success' => true, 'message' => 'Message sent successfully']);

        // Assert conversation created
        $this->assertDatabaseHas('admin_user_conversations', [
            'user_id' => $user->id,
            'type' => 'Ticket',
            'subject' => 'Help with my account',
        ]);

        $conversation = AdminUserConversation::where('user_id', $user->id)
            ->where('subject', 'Help with my account')
            ->first();

        // Assert message recorded
        $this->assertDatabaseHas('admin_user_messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'message' => 'I cannot update my profile picture.',
        ]);

        // Assert notification created for admin
        $this->assertDatabaseHas('notifications', [
            'conversation_id' => $conversation->id,
        ]);
    }

    public function test_user_can_reply_to_existing_admin_conversation()
    {
        $user = User::factory()->create();

        $conv = AdminUserConversation::create([
            'user_id' => $user->id,
            'type' => 'Ticket',
            'subject' => 'Payment Issue',
            'message' => 'My card was declined.',
        ]);

        $response = $this->actingAs($user, 'web')->postJson(route('user-admin-message'), [
            'type' => 'Ticket',
            'subject' => 'Payment Issue', // same subject maps to existing conv
            'message' => 'Actually, it just went through.',
        ]);

        $response->assertJson(['success' => true, 'message' => 'Message sent successfully']);

        $this->assertDatabaseHas('admin_user_messages', [
            'conversation_id' => $conv->id,
            'user_id' => $user->id,
            'message' => 'Actually, it just went through.',
        ]);
        
        // Assert total 1 message + initial message from create? Our test only recorded the reply.
        $this->assertEquals(1, AdminUserMessage::where('conversation_id', $conv->id)->count());
    }
}
