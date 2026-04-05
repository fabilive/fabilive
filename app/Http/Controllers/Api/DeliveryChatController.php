<?php

namespace App\Http\Controllers\Api;

use App\Events\DeliveryMessageSent;
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
        $currUser = Auth::user();

        // 1. Authorization Check: Must be part of the thread
        $isRider = ($currUser instanceof \App\Models\Rider) && $currUser->id === $thread->rider_id;
        $isBuyer = ($currUser instanceof \App\Models\User) && $currUser->id === $thread->buyer_id;
        $isSeller = ($currUser instanceof \App\Models\User) && $currUser->id === $thread->seller_id;

        if (!$isRider && !$isBuyer && !$isSeller) {
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
        $currUser = Auth::user();

        // 1. Authorization Check
        $isRider = ($currUser instanceof \App\Models\Rider) && $currUser->id === $thread->rider_id;
        $isBuyer = ($currUser instanceof \App\Models\User) && $currUser->id === $thread->buyer_id;
        $isSeller = ($currUser instanceof \App\Models\User) && $currUser->id === $thread->seller_id;

        if (!$isRider && !$isBuyer && !$isSeller) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        // 2. Archival Check: Block sending to archived threads
        if ($thread->hidden_at) {
            return response()->json(['status' => false, 'message' => 'Cannot send messages to an archived chat.'], 403);
        }

        $message = Message::create([
            'delivery_chat_thread_id' => $thread->id,
            'sent_user' => $currUser->id,
            'message' => $request->message,
            // Mapping to existing message table fields if necessary
        ]);

        // Broadcast event
        broadcast(new DeliveryMessageSent($message));

        return response()->json([
            'status' => true,
            'message_data' => $message,
        ]);
    }
}
