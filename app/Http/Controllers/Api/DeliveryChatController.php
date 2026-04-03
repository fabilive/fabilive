<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSents;
use App\Http\Controllers\Controller;
use App\Models\DeliveryChatThread;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryChatController extends Controller
{
    /**
     * Fetch messages for a specific delivery chat thread.
     */
    public function fetchMessages(int $threadId)
    {
        $thread = DeliveryChatThread::findOrFail($threadId);
        $user = Auth::user();

        // 1. Authorization Check: Must be part of the thread
        if ($user->id !== $thread->rider_id && $user->id !== $thread->buyer_id && $user->id !== $thread->seller_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized access to this chat.'], 403);
        }

        // 2. Archival Check: Don't show if hidden_at is set (unless admin, but this is API for users/riders)
        if ($thread->hidden_at && (now()->diffInHours($thread->hidden_at) >= 0)) {
            return response()->json(['status' => false, 'message' => 'This chat has been archived and is no longer accessible.'], 403);
        }

        $messages = Message::where('delivery_chat_thread_id', $threadId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Send a message in a delivery chat thread.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'delivery_chat_thread_id' => 'required|exists:delivery_chat_threads,id',
            'message' => 'required|string|max:2000',
        ]);

        $thread = DeliveryChatThread::findOrFail($request->delivery_chat_thread_id);
        $user = Auth::user();

        // 1. Authorization Check
        if ($user->id !== $thread->rider_id && $user->id !== $thread->buyer_id && $user->id !== $thread->seller_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        // 2. Archival Check: Block sending to archived threads
        if ($thread->hidden_at) {
            return response()->json(['status' => false, 'message' => 'Cannot send messages to an archived chat.'], 403);
        }

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
            'message_data' => $message,
        ]);
    }
}
