<?php

namespace App\Http\Controllers\User;

use App\{
    Models\User,
    Models\Message,
    Models\Notification,
    Models\Conversation,
    Classes\GeniusMailer,
    Models\AdminUserMessage,
    Models\AdminUserConversation,
};
use App\Events\UserMessageSent;
use App\Models\Chat;
use App\Models\ChatMessages;
use App\Models\DeliveryRider;
use App\Models\Order;
use Illuminate\Http\Request;

class MessageController extends UserBaseController
{


    public function messages()
    {
        $user = $this->user;
        $convs = Conversation::where('sent_user', '=', $user->id)->orWhere('recieved_user', '=', $user->id)->get();
        return view('user.message.index', compact('user', 'convs'));
    }

    public function message($id)
    {
        $user = $this->user;
        $conv = Conversation::findOrfail($id);
        return view('user.message.create', compact('user', 'conv'));
    }

    public function messagedelete($id)
    {
        $conv = Conversation::findOrfail($id);
        if ($conv->messages->count() > 0) {
            foreach ($conv->messages as $key) {
                $key->delete();
            }
        }
        $conv->delete();
        return redirect()->back()->with('success', __('Message Deleted Successfully'));
    }

    public function msgload($id)
    {
        $conv = Conversation::findOrfail($id);
        return view('load.usermsg', compact('conv'));
    }

    //Send email to user
    public function usercontact(Request $request)
    {

        $data = 1;
        $user = User::findOrFail($request->user_id);
        $vendor = User::where('email', '=', $request->email)->first();
        $seller = User::findOrFail($request->vendor_id);


        if (!$vendor) {
            return response()->json(['error' => true, 'message' => 'Email Not Found']);
        }

        if ($vendor->email == $seller->email) {
            return response()->json(['error' => true, 'message' => 'You can not message yourself!!']);
        }

        $subject = $request->subject;
        $name = $request->name;
        $from = $request->email;
        $msg = "Name: " . $name . "\nEmail: " . $from . "\nMessage: " . $request->message;

        $data = [
            'to' => $seller->email,
            'subject' => $request->subject,
            'body' => $msg,
        ];

        $mailer = new GeniusMailer();
        $mailer->sendCustomMail($data);

        $conv = Conversation::where('sent_user', '=', $user->id)->where('subject', '=', $subject)->first();

        if (isset($conv)) {
            $msg = new Message();
            $msg->conversation_id = $conv->id;
            $msg->message = $request->message;
            $msg->sent_user = $user->id;
            $msg->save();
            return response()->json($data);
        } else {
            $message = new Conversation();
            $message->subject = $subject;
            $message->sent_user = $request->user_id;
            $message->recieved_user = $seller->id;
            $message->message = $request->message;
            $message->save();

            $msg = new Message();
            $msg->conversation_id = $message->id;
            $msg->message = $request->message;
            $msg->sent_user = $request->user_id;;
            $msg->save();
            return response()->json(['error' => false, 'message' => 'Message sent successfully']);
        }
    }

    public function postmessage(Request $request)
    {
        $msg = new Message();
        $input = $request->all();
        $msg->fill($input)->save();
        //--- Redirect Section
        $msg = __('Message Sent!');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function adminmessages()
    {
        $user = $this->user;
        $convs = AdminUserConversation::where('type', '=', 'Ticket')->where('user_id', '=', $user->id)->get();
        return view('user.ticket.index', compact('convs'));
    }

    public function adminDiscordmessages()
    {
        $user = $this->user;
        $convs = AdminUserConversation::where('type', '=', 'Dispute')->where('user_id', '=', $user->id)->get();
        return view('user.dispute.index', compact('convs'));
    }

    public function messageload($id)
    {
        $conv = AdminUserConversation::findOrfail($id);
        return view('load.usermessage', compact('conv'));
    }

    public function adminmessage($id)
    {
        $conv = AdminUserConversation::findOrfail($id);
        return view('user.ticket.create', compact('conv'));
    }

    public function adminmessagedelete($id)
    {
        $conv = AdminUserConversation::findOrfail($id);
        if ($conv->messages->count() > 0) {
            foreach ($conv->messages as $key) {
                $key->delete();
            }
        }
        $conv->delete();
        return redirect()->back()->with('success', __('Message Deleted Successfully'));
    }

    public function adminpostmessage(Request $request)
    {
        $msg = new AdminUserMessage();
        $input = $request->all();
        $msg->fill($input)->save();
        $notification = new Notification;
        $notification->conversation_id = $msg->conversation->id;
        $notification->save();
        //--- Redirect Section
        $msg = __('Message Sent!');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function adminusercontact(Request $request)
    {

        if ($request->type ==  'Dispute') {
            $order = Order::where('order_number', $request->order)->exists();
            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order Number Not Found']);
            }
        }


        $user = $this->user;
        $gs = $this->gs;
        $subject = $request->subject;
        $to = \DB::table('pagesettings')->first()->contact_email;
        $from = $user->email;
        $msg = "Email: " . $from . "\nMessage: " . $request->message;

        $data = [
            'to' => $to,
            'subject' => $subject,
            'body' => $msg,
        ];

        $mailer = new GeniusMailer();
        $mailer->sendCustomMail($data);




        if ($request->type == 'Ticket') {
            $conv = AdminUserConversation::whereType('Ticket')->whereUserId($user->id)->whereSubject($subject)->first();
        } else {
            $conv = AdminUserConversation::whereType('Dispute')->whereUserId($user->id)->whereSubject($subject)->first();
        }

        if (isset($conv)) {
            $msg = new AdminUserMessage();
            $msg->conversation_id = $conv->id;
            $msg->message = $request->message;
            $msg->user_id = $user->id;
            $msg->save();
            return response()->json(['success' => true, 'message' => 'Message sent successfully']);
        } else {
            $message = new AdminUserConversation();
            $message->subject = $subject;
            $message->user_id = $user->id;
            $message->message = $request->message;
            $message->order_number = $request->order;
            $message->type = $request->type;
            $message->save();
            $notification = new Notification;
            $notification->conversation_id = $message->id;
            $notification->save();
            $msg = new AdminUserMessage();
            $msg->conversation_id = $message->id;
            $msg->message = $request->message;
            $msg->user_id = $user->id;
            $msg->save();
            return response()->json(['success' => true, 'message' => 'Message sent successfully']);
        }
    }





    public function messages2()
    {
        $user = auth()->user();

        $chats = Chat::where('buyer_id', $user->id)
            ->with([
                'rider:id,name',
                'order:id,cart',
                'messages' => function ($query) {
                    $query->orderBy('id', 'asc');
                }
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        // 🔹 Attach product names to each chat
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

            // Add dynamic property
            $chat->product_names = $productNames;
        });

        return view('user.messagedelivery.index', compact('chats'));
    }


    // Fetch messages for selected chat
    public function fetchMessages(Request $request)
    {
        $request->validate(['chat_id' => 'required|integer']);

        $user = auth()->user();

        $chat = Chat::where('id', $request->chat_id)
            ->where('buyer_id', $user->id)
            ->firstOrFail();

        $messages = ChatMessages::where('chat_id', $chat->id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }

    // Store buyer message
    public function storeMessage(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'chat_id' => 'required|exists:delivery_chat_threads,id',
            'message' => 'required|string|max:2000'
        ]);

        $chat = Chat::where('id', $request->chat_id)
            ->where('buyer_id', $user->id)
            ->firstOrFail();

        $message = ChatMessages::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'receiver_id' => $chat->rider_id,
            'message' => $request->message,
            'is_read' => 0,
        ]);

        try {
            broadcast(new UserMessageSent($message));
        } catch (\Exception $e) {
            // Broadcasting may fail if Pusher is not configured - message is still saved
        }

        return response()->json([
            'status' => true,
            'message' => 'Message sent successfully.',
            'message_data' => $message
        ]);
    }
}
