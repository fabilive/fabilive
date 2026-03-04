<?php

namespace App\Events;

use App\Models\ChatMessages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UserMessageSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessages $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        // Use a private channel for the rider/admin
        return new Channel('admin-chat.' . $this->message->receiver_id);
    }

    /**
     * Broadcast event name for frontend subscription
     */

    public function broadcastAs()
    {
        return 'UserMessageSent';
    }

    /**
     * Data sent with the event
     */
    public function broadcastWith()
    {
        return [
            'id'          => $this->message->id,
            'chat_id'     => $this->message->chat_id,
            'sender_id'   => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message'     => $this->message->message,
            'created_at'  => $this->message->created_at->toDateTimeString(),
        ];
    }
}