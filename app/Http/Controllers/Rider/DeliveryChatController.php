<?php

namespace App\Http\Controllers\Rider;

use App\Events\DeliveryMessageSent;
use App\Http\Controllers\Controller;
use App\Models\DeliveryChatThread;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:rider');
    }

    public function show($id)
    {
        $rider = Auth::guard('rider')->user();
        $thread = DeliveryChatThread::with(['deliveryJob.order', 'seller', 'buyer', 'messages'])
            ->where('id', $id)
            ->where('rider_id', $rider->id)
            ->firstOrFail();

        if ($thread->hidden_at) {
            return redirect()->back()->with('error', __('This chat has been archived.'));
        }

        return view('rider.delivery.chat', compact('thread'));
    }

    public function fetchMessages($id)
    {
        $rider = Auth::guard('rider')->user();
        $thread = DeliveryChatThread::where('id', $id)
            ->where('rider_id', $rider->id)
            ->firstOrFail();

        $messages = Message::where('delivery_chat_thread_id', $id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $rider = Auth::guard('rider')->user();
        $request->validate([
            'delivery_chat_thread_id' => 'required|exists:delivery_chat_threads,id',
            'message' => 'required|string|max:2000',
        ]);

        $thread = DeliveryChatThread::where('id', $request->delivery_chat_thread_id)
            ->where('rider_id', $rider->id)
            ->firstOrFail();

        if ($thread->hidden_at) {
            return response()->json(['status' => false, 'message' => 'Archived.'], 403);
        }

        $message = Message::create([
            'delivery_chat_thread_id' => $thread->id,
            'sent_user' => $rider->id,
            'message' => $request->message,
        ]);

        broadcast(new DeliveryMessageSent($message));

        return response()->json([
            'status' => true,
            'message_data' => $message,
        ]);
    }
}
