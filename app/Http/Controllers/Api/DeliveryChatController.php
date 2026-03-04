<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryChatThread;
use App\Models\Message;
use App\Events\MessageSents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryChatController extends Controller
{
    /**
     * Fetch messages for a specific delivery chat thread.
     */
    public function fetchMessages(int $threadId)
    {
        $messages = Message::where('delivery_chat_thread_id', $threadId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Send a message in a delivery chat thread.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'delivery_chat_thread_id' => 'required|exists:delivery_chat_threads,id',
            'message' => 'required|string|max:2000'
        ]);

        $thread = DeliveryChatThread::findOrFail($request->delivery_chat_thread_id);
        $user = Auth::user();

        // Determine recipient
        $receiverId = null;
        if ($user->id === $thread->rider_id) {
            $receiverId = $thread->thread_type === 'rider_buyer' ? $thread->buyer_id : $thread->seller_id;
        } else {
            $receiverId = $thread->rider_id;
        }

        $message = Message::create([
            'delivery_chat_thread_id' => $thread->id,
            'sent_user' => $user->id,
            'message' => $request->message,
            // Mapping to existing message table fields if necessary
        ]);

        // Broadcast event (using existing MessageSents if compatible or create new)
        broadcast(new MessageSents($message));

        return response()->json([
            'status' => true,
            'message_data' => $message
        ]);
    }
}
