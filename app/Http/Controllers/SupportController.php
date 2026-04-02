<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportFaqCategory;
use App\Models\SupportFaq;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use App\Models\SupportConversationEvent;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Get FAQs based on the selected context (buyer/vendor).
     * Accessible by guests as well.
     */
    public function getFaqs(Request $request)
    {
        $request->validate([
            'context' => 'required|in:buyer,vendor'
        ]);

        $context = $request->context;
        
        $categories = SupportFaqCategory::where(function($query) use ($context) {
                $query->where('context', $context)->orWhere('context', 'both');
            })
            ->where('is_active', true)
            ->with(['faqs' => function($query) use ($context) {
                $query->where(function($q) use ($context) {
                    $q->where('context', $context)->orWhere('context', 'both');
                })->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
            
        return response()->json(['categories' => $categories]);
    }

    /**
     * Start a bot interaction or process a message through the bot.
     */
    public function botChat(Request $request, \App\Services\SupportBotService $botService)
    {
        $request->validate([
            'context' => 'required|in:buyer,vendor',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120', // 5MB max
            'conversation_id' => 'nullable|integer'
        ]);

        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        // Fetch or create conversation
        if ($request->conversation_id) {
            $conversation = \App\Models\SupportConversation::findOrFail($request->conversation_id);
            if ($conversation->requester_user_id !== $user->id) {
                abort(403);
            }
        } else {
            $conversation = \App\Models\SupportConversation::create([
                'requester_user_id' => $user->id,
                'context' => $request->context,
                'status' => 'bot_active'
            ]);
        }

        // Save user message
        $msgData = [
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'type' => 'text',
            'body_text' => $request->message
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('support_attachments', 'public');
            $msgData['type'] = 'file';
            $msgData['attachment_url'] = '/storage/' . $path;
            $msgData['attachment_mime'] = $file->getMimeType();
            $msgData['attachment_size'] = $file->getSize();
            
            if (str_starts_with($msgData['attachment_mime'], 'image/')) {
                $msgData['type'] = 'image';
            }
        }

        \App\Models\SupportMessage::create($msgData);

        // Process through bot (only if there's text AND it's bot_active)
        $botResponseText = "";
        if ($request->message && $conversation->status === 'bot_active') {
            $reply = $botService->processMessage($request->message, $request->context);
            if ($reply) {
                $botResponseText = $reply['response_text'];
                
                // If bot indicates escalation is needed
                if (isset($reply['escalate']) && $reply['escalate'] === true) {
                    $conversation->status = 'bot_active'; // Keep bot active so user can click escalate button
                    $conversation->save();
                }
            }
        }
        
        if (!$botResponseText) {
            // Count consecutive misses to trigger escalation
            // We can check the last 2 messages from bot if they were fallback
            $recentBotMessages = \App\Models\SupportMessage::where('conversation_id', $conversation->id)
                ->where('sender_type', 'bot')
                ->latest()
                ->take(2)
                ->get();
                
            $repeatedMiss = $recentBotMessages->count() === 2 && $recentBotMessages->every(fn($msg) => str_contains($msg->body_text, 'Please clarify') || str_contains($msg->body_text, 'Live Support'));
            
            if ($repeatedMiss) {
                $botResponseText = "You want talk with a live agent? Make you click 'Request Live Support' button below.";
            } else {
                $botResponseText = "I no fully understand. Please clarify your question, or click 'Request Live Support' to chat with a human agent.";
            }
        }

        if ($botResponseText) {
            // Save bot message
            $botMsg = \App\Models\SupportMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'sender_id' => null,
                'type' => 'text',
                'body_text' => $botResponseText
            ]);
        }

        return response()->json([
            'status' => 'success', 
            'conversation_id' => $conversation->id,
            'bot_message' => $botMsg ?? null
        ]);
    }

    /**
     * Escalate to a live agent.
     */
    public function requestLiveSupport(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:support_conversations,id'
        ]);

        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $conversation = \App\Models\SupportConversation::findOrFail($request->conversation_id);
        if ($conversation->requester_user_id !== $user->id) {
            abort(403);
        }

        if ($conversation->status !== 'bot_active' && $conversation->status !== 'waiting_agent') {
            return response()->json(['status' => 'error', 'message' => 'Conversation is already active or ended']);
        }
        try {
            $convId = $request->conversation_id;
            
            // 1. Instant Status Switch (Direct & Fast)
            \Illuminate\Support\Facades\DB::table('support_conversations')
                ->where('id', $convId)
                ->update([
                    'status' => 'waiting_agent',
                    'updated_at' => now(),
                ]);

            // 2. Insert Handoff Message (No complex triggers)
            \Illuminate\Support\Facades\DB::table('support_messages')->insert([
                'conversation_id' => $convId,
                'sender_type' => 'system',
                'body_text' => 'Please wait for a live agent to connect with your chat... our live agent will get in touch with you shortly.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Log the Event
            \Illuminate\Support\Facades\DB::table('support_conversation_events')->insert([
                'conversation_id' => $convId,
                'actor_type' => 'system',
                'event' => 'waiting_agent',
                'meta_json' => json_encode(['requested_at' => now()]),
                'created_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'conversation' => [
                    'id' => $convId,
                    'status' => 'waiting_agent'
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("ULTRA STABLE ESCALATION FAILED: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Escalation error. Our team is investigating.'
            ], 500);
        }
    }

    /**
     * Send a message to an active live chat.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:support_conversations,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120', // 5MB max
        ]);

        $user = Auth::guard('web')->user();
        $conversation = \App\Models\SupportConversation::findOrFail($request->conversation_id);
        
        if ($conversation->requester_user_id !== $user->id) {
            abort(403);
        }
        
        if ($conversation->status === 'ended') {
            return response()->json(['status' => 'error', 'message' => 'Conversation ended']);
        }

        $msgData = [
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'type' => 'text',
            'body_text' => $request->message
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('support_attachments', 'public'); // Usually using public disk or s3
            $msgData['type'] = 'file';
            $msgData['attachment_url'] = '/storage/' . $path;
            $msgData['attachment_mime'] = $file->getMimeType();
            $msgData['attachment_size'] = $file->getSize();
            
            if (str_starts_with($msgData['attachment_mime'], 'image/')) {
                $msgData['type'] = 'image';
            }
        }

        $message = \App\Models\SupportMessage::create($msgData);

        // Here we would broadcast to the agent using Ably
        // broadcast(new \App\Events\SupportMessageSent($message));

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    /**
     * End conversation.
     */
    public function endConversation(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:support_conversations,id'
        ]);

        $user = Auth::guard('web')->user();
        $admin = Auth::guard('admin')->user();
        $conversation = \App\Models\SupportConversation::findOrFail($request->conversation_id);
        
        if ($user && $conversation->requester_user_id !== $user->id) {
            abort(403);
        }

        if (!$user && !$admin) {
            abort(401);
        }

        $conversation->status = 'ended';
        $conversation->ended_at = now();
        $conversation->ended_by = $admin ? 'agent' : 'user';
        $conversation->save();
        
        \App\Models\SupportConversationEvent::create([
            'conversation_id' => $conversation->id,
            'actor_type' => $admin ? 'agent' : 'user',
            'actor_id' => $admin ? $admin->id : $user->id,
            'event' => 'ended'
        ]);

        return response()->json(['status' => 'success']);
    }
    
    /**
     * Rate a conversation.
     */
    public function rateConversation(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:support_conversations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $user = Auth::guard('web')->user();
        $conversation = \App\Models\SupportConversation::findOrFail($request->conversation_id);
        
        if ($conversation->requester_user_id !== $user->id) {
            abort(403);
        }
        
        if ($conversation->status !== 'ended') {
            return response()->json(['status' => 'error', 'message' => 'You can only rate an ended conversation.']);
        }
        
        if (\App\Models\SupportRating::where('conversation_id', $conversation->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'You have already rated this conversation.']);
        }

        \App\Models\SupportRating::create([
            'conversation_id' => $conversation->id,
            'agent_admin_id' => $conversation->assigned_agent_admin_id ?? 1, // Fallback if rated unassigned
            'rater_user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        
        $conversation->status = 'rated';
        $conversation->save();

        return response()->json(['status' => 'success']);
    }
    
    /**
     * Get chat history.
     */
    public function getChatHistory(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $conversationId = $request->query('conversation_id');
        
        if ($conversationId) {
            $conversation = \App\Models\SupportConversation::with('messages', 'assignedAgent')->findOrFail($conversationId);
            if ($conversation->requester_user_id !== $user->id) {
                abort(403);
            }
        } else {
            // Find latest active or recently ended conversation
            $conversation = \App\Models\SupportConversation::where('requester_user_id', $user->id)
                ->whereIn('status', ['bot_active', 'waiting_agent', 'assigned'])
                ->with('messages', 'assignedAgent')
                ->orderBy('created_at', 'desc')
                ->first();
                
            if (!$conversation) {
                return response()->json(['status' => 'no_content']);
            }
        }

        return response()->json([
            'status' => 'success', 
            'conversation' => $conversation,
            'messages' => $conversation->messages
        ]);
    }

    /**
     * Get all conversations for the authenticated user.
     */
    public function getUserConversations(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $conversations = \App\Models\SupportConversation::where('requester_user_id', $user->id)
            ->with(['messages' => function($q) {
                $q->latest()->limit(1);
            }, 'assignedAgent'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'conversations' => $conversations
        ]);
    }
}
