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
            'message' => 'required|string',
            'conversation_id' => 'nullable|integer'
        ]);

        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        // Fetch or create conversation
        if ($request->conversation_id) {
            $conversation = \App\Models\SupportConversation::findOrFail($request->conversation_id);
            // Ensure auth
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
        \App\Models\SupportMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'type' => 'text',
            'body_text' => $request->message
        ]);

        // Process through bot
        $reply = $botService->processMessage($request->message, $request->context);
        
        $botResponseText = "";
        if ($reply) {
            $botResponseText = $reply['response_text'];
            if ($reply['suggested_faq']) {
                $botResponseText .= "\n\n" . $reply['suggested_faq'];
            }
        } else {
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

        // Save bot message
        $botMsg = \App\Models\SupportMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'bot',
            'sender_id' => null,
            'type' => 'text',
            'body_text' => $botResponseText
        ]);

        return response()->json([
            'status' => 'success', 
            'conversation_id' => $conversation->id,
            'bot_message' => $botMsg
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

        // Concurrency-safe agent assignment
        \Illuminate\Support\Facades\DB::transaction(function () use ($conversation) {
            // Re-fetch conversation to lock it
            $lockedConv = \App\Models\SupportConversation::where('id', $conversation->id)->lockForUpdate()->first();
            
            if ($lockedConv->status !== 'bot_active' && $lockedConv->status !== 'waiting_agent') {
                return;
            }

            // Find an online agent with least active chats who is below max_active_chats limit
            $availableAgent = \App\Models\SupportAgent::where('is_online', true)
                ->whereRaw('(SELECT COUNT(*) FROM support_conversations WHERE assigned_agent_admin_id = support_agents.admin_id AND status = "assigned") < max_active_chats')
                ->orderByRaw('(SELECT COUNT(*) FROM support_conversations WHERE assigned_agent_admin_id = support_agents.admin_id AND status = "assigned") ASC')
                ->lockForUpdate()
                ->first();

            if ($availableAgent) {
                $lockedConv->assigned_agent_admin_id = $availableAgent->admin_id;
                $lockedConv->status = 'assigned';
                $lockedConv->assigned_at = now();
                $lockedConv->save();

                \App\Models\SupportConversationEvent::create([
                    'conversation_id' => $lockedConv->id,
                    'actor_type' => 'system',
                    'event' => 'assigned',
                    'meta_json' => ['agent_id' => $availableAgent->admin_id]
                ]);

                // We would broadcast to the agent here (Ably is configured)
                // broadcast(new \App\Events\AgentAssigned($lockedConv));

                // Send a bot message confirming assignment
                \App\Models\SupportMessage::create([
                    'conversation_id' => $lockedConv->id,
                    'sender_type' => 'system',
                    'sender_id' => null,
                    'type' => 'text',
                    'body_text' => 'An agent has been assigned and will be with you shortly.'
                ]);
            } else {
                $lockedConv->status = 'waiting_agent';
                $lockedConv->save();

                \App\Models\SupportMessage::create([
                    'conversation_id' => $lockedConv->id,
                    'sender_type' => 'system',
                    'sender_id' => null,
                    'type' => 'text',
                    'body_text' => 'All agents are currently offline or busy. We will assist you as soon as someone is available.'
                ]);
            }
            $conversation->refresh();
        });

        return response()->json([
            'status' => 'success',
            'conversation' => $conversation->refresh()
        ]);
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
        $conversation = \App\Models\SupportConversation::findOrFail($request->conversation_id);
        
        if ($conversation->requester_user_id !== $user->id) {
            abort(403);
        }

        $conversation->status = 'ended';
        $conversation->ended_at = now();
        $conversation->ended_by = 'user';
        $conversation->save();
        
        \App\Models\SupportConversationEvent::create([
            'conversation_id' => $conversation->id,
            'actor_type' => 'user',
            'actor_id' => $user->id,
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
        $request->validate([
            'conversation_id' => 'required|exists:support_conversations,id'
        ]);

        $user = Auth::guard('web')->user();
        $conversation = \App\Models\SupportConversation::with('messages', 'assignedAgent')->findOrFail($request->conversation_id);
        
        if ($conversation->requester_user_id !== $user->id) {
            abort(403);
        }

        return response()->json([
            'status' => 'success', 
            'conversation' => $conversation,
            'messages' => $conversation->messages
        ]);
    }
}
