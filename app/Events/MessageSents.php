<?php

namespace App\Events;

use App\Models\ChatMessages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class MessageSents implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(ChatMessages $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('chat.'.$this->message->chat_id); // public channel
    }

    public function broadcastAs()
    {
        return 'MessageSents'; // 🔥 important, matches frontend bind
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'chat_id' => $this->message->chat_id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
