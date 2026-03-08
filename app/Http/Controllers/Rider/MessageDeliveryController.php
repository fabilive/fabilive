<?php

namespace App\Http\Controllers\Rider;

use App\Events\MessageSents;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessages;
use Illuminate\Http\Request;

class MessageDeliveryController extends Controller
{
    public function message()
{
    $rider = auth()->guard('rider')->user();

    $chats = Chat::where('rider_id', $rider->id)
        ->with([
            'buyer:id,name',
            'order:id,cart' // 👈 load cart
        ])
        ->orderBy('updated_at', 'desc')
        ->get();

    // Attach product names
    $chats->each(function ($chat) {

        $productNames = [];

        if ($chat->order && $chat->order->cart) {
            $cart = json_decode($chat->order->cart, true);

            if (!empty($cart['items'])) {
                foreach ($cart['items'] as $item) {
                    if (!empty($item['item']['name'])) {
                        $productNames[] = $item['item']['name'];
                    }
                }
            }
        }


        $chat->product_names = $productNames;

    });

    return view('rider.deliverymessage.index', compact('chats'));
}


public function fetchMessages(Request $request)
{
    $request->validate([
        'chat_id' => 'required|integer'
    ]);

    $rider = auth()->guard('rider')->user();

    // Verify chat belongs to rider
    $chat = Chat::where('id', $request->chat_id)
        ->where('rider_id', $rider->id)
        ->firstOrFail();

    // Fetch messages
    $messages = ChatMessages::where('chat_id', $chat->id)
        ->orderBy('id', 'asc')
        ->get();

    return response()->json([
        'status' => true,
        'messages' => $messages
    ]);
}
public function sendMessage(Request $request)
{
    try {
        $rider = auth()->guard('rider')->user();

        $request->validate([
            'chat_id' => 'required|exists:delivery_chat_threads,id',
            'message' => 'required|string|max:2000'
        ]);

        $chat = Chat::findOrFail($request->chat_id);

        if ($chat->rider_id != $rider->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized for this chat.'
            ], 403);
        }

        $message = ChatMessages::create([
            'chat_id'    => $chat->id,
            'sender_id'  => $rider->id,
            'receiver_id' => $chat->buyer_id, // check spelling
            'message'    => $request->message,
            'is_read'    => 0,
        ]);

        try {
            broadcast(new MessageSents($message));
        } catch (\Exception $e) {
            // Broadcasting may fail if Pusher is not configured - message is still saved
        }

        return response()->json([
            'status'       => true,
            'message'      => 'Message sent successfully',
            'message_data' => $message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}


}
