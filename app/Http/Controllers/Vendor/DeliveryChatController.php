<?php

namespace App\Http\Controllers\Vendor;

use App\Events\DeliveryMessageSent;
use App\Http\Controllers\Controller;
use App\Models\DeliveryChatThread;
use App\Models\ChatMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Vendors use web guard
    }

    public function show($id)
    {
        $vendor = Auth::user();
        $thread = DeliveryChatThread::with(['deliveryJob.order', 'rider', 'messages'])
            ->where('id', $id)
            ->where('seller_id', $vendor->id)
            ->firstOrFail();

        if ($thread->hidden_at) {
            return redirect()->back()->with('error', __('This chat has been archived.'));
        }

        return view('vendor.delivery.chat', compact('thread'));
    }

    public function fetchMessages($id)
    {
        $vendor = Auth::user();
        $thread = DeliveryChatThread::where('id', $id)
            ->where('seller_id', $vendor->id)
            ->firstOrFail();

        $messages = ChatMessages::where('chat_id', $id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request)
    {
        try {
            $vendor = Auth::user();
            $request->validate([
                'delivery_chat_thread_id' => 'required|exists:delivery_chat_threads,id',
                'message' => 'required|string|max:2000',
            ]);

            $thread = DeliveryChatThread::where('id', $request->delivery_chat_thread_id)
                ->where('seller_id', $vendor->id)
                ->firstOrFail();

            if ($thread->hidden_at) {
                return response()->json(['status' => false, 'message' => 'Archived.'], 403);
            }

            $receiverId = $thread->rider_id;

            $message = ChatMessages::create([
                'chat_id' => $thread->id,
                'sender_id' => $vendor->id,
                'receiver_id' => $receiverId,
                'message' => $request->message,
                'is_read' => 0,
            ]);

            try {
                broadcast(new DeliveryMessageSent($message));
            } catch (\Exception $e) {
                \Log::error('Chat Broadcast Error: ' . $e->getMessage());
            }

            return response()->json([
                'status' => true,
                'message_data' => $message,
            ]);
        } catch (\Exception $e) {
            \Log::error('Chat Send Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
