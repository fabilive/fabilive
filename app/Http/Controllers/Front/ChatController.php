<?php

namespace App\Http\Controllers\Front;

use App\Events\MessageSent;
use App\Models\LiveMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return 1234;
        $user = $this->user;

        $messages = LiveMessage::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        return view('chat', compact('user', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        $message = LiveMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => $message]);
    }
}
