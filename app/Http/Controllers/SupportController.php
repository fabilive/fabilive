<?php

namespace App\Http\Controllers;

use App\Models\SupportAgent;
use App\Models\SupportConversation;
use App\Models\SupportConversationEvent;
use App\Models\SupportFaqCategory;
use App\Models\SupportMessage;
use App\Models\SupportRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    /**
     * Auto-detect the user's role from authentication state.
     * Priority: admin > rider > vendor > buyer (default).
     *
     * @return array{role: string, user_id: int|null, user: mixed}
     */
    protected function detectUserRole(): array
    {
        // 1. Check rider guard
        $rider = Auth::guard('rider')->user();
        if ($rider) {
            return ['role' => 'rider', 'user_id' => $rider->id, 'user' => $rider, 'guard' => 'rider'];
        }

        // 2. Check web/user guard
        $user = Auth::guard('web')->user();
        if ($user) {
            // Vendor check: is_vendor == 2 means approved vendor
            if ($user->is_vendor == 2) {
                return ['role' => 'vendor', 'user_id' => $user->id, 'user' => $user, 'guard' => 'web'];
            }
            return ['role' => 'buyer', 'user_id' => $user->id, 'user' => $user, 'guard' => 'web'];
        }

        return ['role' => 'guest', 'user_id' => null, 'user' => null, 'guard' => null];
    }

    /**
     * Get the authenticated user for support purposes.
     * Supports web, rider, and admin guards.
     */
    protected function getSupportUser(): ?object
    {
        return Auth::guard('web')->user()
            ?? Auth::guard('rider')->user();
    }

    /**
     * Get the user ID for support conversation ownership.
     * For riders and admins, we still use their respective IDs.
     */
    protected function getSupportUserId(): ?int
    {
        $user = Auth::guard('web')->user();
        if ($user) return $user->id;

        $rider = Auth::guard('rider')->user();
        if ($rider) return $rider->id;

        return null;
    }

    /**
     * Verify conversation ownership based on role.
     */
    protected function checkConversationOwner(SupportConversation $conversation): bool
    {
        $detected = $this->detectUserRole();
        $userId = $detected['user_id'];
        $role = $detected['role'];

        if ($role === 'rider') {
            return $conversation->rider_id === $userId;
        } else {
            return $conversation->requester_user_id === $userId;
        }
    }

    /**
     * Get a query builder for conversations belonging to the current user.
     */
    protected function getUserConversationsQuery()
    {
        $detected = $this->detectUserRole();
        $userId = $detected['user_id'];
        $role = $detected['role'];

        $query = SupportConversation::query();
        
        if ($role === 'rider') {
            return $query->where('rider_id', $userId);
        } else {
            return $query->where('requester_user_id', $userId);
        }
    }

    /**
     * API endpoint: auto-detect the user's role.
     * Frontend calls this to skip manual role selection.
     */
    public function detectRole(Request $request)
    {
        $detected = $this->detectUserRole();

        if ($detected['role'] === 'guest') {
            return response()->json([
                'status' => 'unauthenticated',
                'role' => 'guest',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'role' => $detected['role'],
            'user_name' => $detected['user']->name ?? $detected['user']->email ?? 'User',
        ]);
    }

    /**
     * Get FAQs based on the selected context.
     * Accessible by guests as well.
     */
    public function getFaqs(Request $request)
    {
        $request->validate([
            'context' => 'required|in:buyer,vendor,rider',
        ]);

        $context = $request->context;

        $categories = SupportFaqCategory::where(function ($query) use ($context) {
            $query->where('context', $context)->orWhere('context', 'both');
        })
            ->where('is_active', true)
            ->with(['faqs' => function ($query) use ($context) {
                $query->where(function ($q) use ($context) {
                    $q->where('context', $context)->orWhere('context', 'both');
                })->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Start a bot interaction or process a message through the bot.
     * Supports all roles: buyer, vendor, rider, admin.
     */
    public function botChat(Request $request, \App\Services\SupportBotService $botService)
    {
        $request->validate([
            'context' => 'required|in:buyer,vendor,rider',
            'message' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:5120',
            'conversation_id' => 'nullable|integer',
        ]);

        // Use auto-detection with manual context as override
        $detected = $this->detectUserRole();
        $user = $detected['user'];
        $userId = $detected['user_id'];

        if (!$user || $detected['role'] === 'guest') {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        // Use detected role, but allow manual override only if valid
        $context = $request->context;
        $detectedRole = $detected['role'];

        // Security: Prevent non-admins from using admin context
        if ($context === 'admin' && $detectedRole !== 'admin') {
            $context = $detectedRole;
        }

        // Sanitize message text
        $messageText = $request->message ? $this->sanitizeInput($request->message) : null;

        // Fetch or create conversation
        if ($request->conversation_id) {
            $conversation = SupportConversation::findOrFail($request->conversation_id);
            
            // Ownership check (role-aware)
            $isOwner = false;
            if ($detectedRole === 'admin') {
                $isOwner = $conversation->admin_id === $userId;
            } elseif ($detectedRole === 'rider') {
                $isOwner = $conversation->rider_id === $userId;
            } else {
                $isOwner = $conversation->requester_user_id === $userId;
            }

            if (!$isOwner) {
                abort(403);
            }
        } else {
            // Map ID to correct column to avoid FK violations
            $convData = [
                'context' => $context,
                'detected_role' => $detectedRole,
                'status' => 'bot_active',
            ];

            if ($detectedRole === 'rider') {
                $convData['rider_id'] = $userId;
            } else {
                $convData['requester_user_id'] = $userId;
            }

            $conversation = SupportConversation::create($convData);
        }

        // Save user message
        $msgData = [
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'sender_id' => $userId,
            'type' => 'text',
            'body_text' => $messageText,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('support_attachments', 'public');
            $msgData['type'] = 'file';
            $msgData['attachment_url'] = '/storage/'.$path;
            $msgData['attachment_mime'] = $file->getMimeType();
            $msgData['attachment_size'] = $file->getSize();

            if (str_starts_with($msgData['attachment_mime'], 'image/')) {
                $msgData['type'] = 'image';
            }
        }

        SupportMessage::create($msgData);

        // Retrieve session order ID from conversation meta
        $sessionOrderId = session("mboko_order_{$conversation->id}");

        // Process through bot (only if there's text AND it's bot_active)
        $botResponseText = '';
        $botMsg = null;
        if ($messageText && $conversation->status === 'bot_active') {
            $reply = $botService->processMessage(
                $messageText,
                $context,
                $userId,
                $sessionOrderId
            );

            if ($reply) {
                $botResponseText = $reply['response_text'];

                // Store the session order ID for follow-up questions
                if (!empty($reply['session_order_id'])) {
                    session(["mboko_order_{$conversation->id}" => $reply['session_order_id']]);
                }

                // If bot indicates escalation is needed, keep bot active so user can click escalate button
                if (isset($reply['escalate']) && $reply['escalate'] === true) {
                    $conversation->status = 'bot_active';
                    $conversation->save();
                }
            }
        }

        if (! $botResponseText && $conversation->status === 'bot_active') {
            // Count consecutive misses to trigger auto-escalation suggestion
            $recentBotMessages = SupportMessage::where('conversation_id', $conversation->id)
                ->where('sender_type', 'bot')
                ->latest()
                ->take(2)
                ->get();

            $repeatedMiss = $recentBotMessages->count() === 2 && $recentBotMessages->every(
                fn ($msg) => str_contains($msg->body_text, 'rephrase')
                    || str_contains($msg->body_text, 'Live Support')
                    || str_contains($msg->body_text, 'not sure I understood')
            );

            if ($repeatedMiss) {
                $botResponseText = "I think a live agent would help you better. 🧑‍💼 Please click the 'Request Live Support' button below to connect with our team.";
            } else {
                $botResponseText = "I'm not sure I understood that completely. 🤔 Could you rephrase your question? Or click 'Request Live Support' to chat with a human agent.";
            }
        }

        if ($botResponseText) {
            $botMsg = SupportMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'sender_id' => null,
                'type' => 'text',
                'body_text' => $botResponseText,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'conversation_id' => $conversation->id,
            'bot_message' => $botMsg ?? null,
        ]);
    }

    /**
     * Escalate to a live agent.
     * Attempts to assign an available agent. If none available, queues the request.
     */
    public function requestLiveSupport(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:support_conversations,id',
        ]);

        $userId = $this->getSupportUserId();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $conversation = SupportConversation::findOrFail($request->conversation_id);
        if (!$this->checkConversationOwner($conversation)) {
            abort(403);
        }

        if (!in_array($conversation->status, ['bot_active', 'waiting_agent'])) {
            return response()->json(['status' => 'error', 'message' => 'Conversation is already active or ended']);
        }

        try {
            $convId = $request->conversation_id;

            // 1. Try to find an available online agent
            $availableAgent = SupportAgent::where('is_online', true)
                ->where('last_seen_at', '>=', now()->subMinutes(10))
                ->first();

            $agentAssigned = false;
            $agentName = null;

            if ($availableAgent) {
                // Check if agent is under capacity
                $activeChats = SupportConversation::where('assigned_agent_admin_id', $availableAgent->admin_id)
                    ->whereIn('status', ['assigned', 'waiting_agent'])
                    ->count();

                if ($activeChats < $availableAgent->max_active_chats) {
                    // Assign agent
                    DB::table('support_conversations')
                        ->where('id', $convId)
                        ->update([
                            'status' => 'assigned',
                            'assigned_agent_admin_id' => $availableAgent->admin_id,
                            'assigned_at' => now(),
                            'updated_at' => now(),
                        ]);
                    $agentAssigned = true;
                    $agentName = $availableAgent->admin?->name ?? 'Support Agent';
                }
            }

            if (!$agentAssigned) {
                // 2. No agent available — queue the request
                DB::table('support_conversations')
                    ->where('id', $convId)
                    ->update([
                        'status' => 'waiting_agent',
                        'updated_at' => now(),
                    ]);
            }

            // 3. Insert system handoff message
            $systemMessage = $agentAssigned
                ? "You've been connected to {$agentName}. They will respond shortly. 🧑‍💼"
                : "Your request has been queued. A live agent will connect with you as soon as one becomes available. Please stay on this chat. ⏳";

            DB::table('support_messages')->insert([
                'conversation_id' => $convId,
                'sender_type' => 'system',
                'body_text' => $systemMessage,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Log the event
            DB::table('support_conversation_events')->insert([
                'conversation_id' => $convId,
                'actor_type' => 'system',
                'event' => $agentAssigned ? 'assigned' : 'waiting_agent',
                'meta_json' => json_encode([
                    'requested_at' => now()->toISOString(),
                    'agent_assigned' => $agentAssigned,
                    'agent_id' => $agentAssigned ? $availableAgent->admin_id : null,
                ]),
                'created_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'conversation' => [
                    'id' => $convId,
                    'status' => $agentAssigned ? 'assigned' : 'waiting_agent',
                    'agent_assigned' => $agentAssigned,
                    'message' => $systemMessage,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Support escalation failed: '.$e->getMessage(), [
                'conversation_id' => $request->conversation_id,
                'user_id' => $userId,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'We could not process your request right now. Please try again.',
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
            'message' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $userId = $this->getSupportUserId();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $conversation = SupportConversation::findOrFail($request->conversation_id);
        if (!$this->checkConversationOwner($conversation)) {
            abort(403);
        }

        if ($conversation->status === 'ended') {
            return response()->json(['status' => 'error', 'message' => 'Conversation ended']);
        }

        $msgData = [
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'sender_id' => $userId,
            'type' => 'text',
            'body_text' => $request->message ? $this->sanitizeInput($request->message) : null,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('support_attachments', 'public');
            $msgData['type'] = 'file';
            $msgData['attachment_url'] = '/storage/'.$path;
            $msgData['attachment_mime'] = $file->getMimeType();
            $msgData['attachment_size'] = $file->getSize();

            if (str_starts_with($msgData['attachment_mime'], 'image/')) {
                $msgData['type'] = 'image';
            }
        }

        $message = SupportMessage::create($msgData);

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    /**
     * End conversation.
     */
    public function endConversation(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:support_conversations,id',
        ]);

        $user = Auth::guard('web')->user() ?? Auth::guard('rider')->user();
        $admin = Auth::guard('admin')->user();
        $conversation = SupportConversation::findOrFail($request->conversation_id);

        if (!$this->checkConversationOwner($conversation) && !$admin) {
            abort(403);
        }

        if (! $user && ! $admin) {
            abort(401);
        }

        $conversation->status = 'ended';
        $conversation->ended_at = now();
        $conversation->ended_by = $admin ? 'agent' : 'user';
        $conversation->save();

        SupportConversationEvent::create([
            'conversation_id' => $conversation->id,
            'actor_type' => $admin ? 'agent' : 'user',
            'actor_id' => $admin ? $admin->id : $user->id,
            'event' => 'ended',
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
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = $this->getSupportUserId();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $conversation = SupportConversation::findOrFail($request->conversation_id);
        if (!$this->checkConversationOwner($conversation)) {
            abort(403);
        }

        if ($conversation->status !== 'ended') {
            return response()->json(['status' => 'error', 'message' => 'You can only rate an ended conversation.']);
        }

        if (SupportRating::where('conversation_id', $conversation->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'You have already rated this conversation.']);
        }

        SupportRating::create([
            'conversation_id' => $conversation->id,
            'agent_admin_id' => $conversation->assigned_agent_admin_id ?? 1,
            'rater_user_id' => $userId,
            'rating' => $request->rating,
            'comment' => $request->comment ? $this->sanitizeInput($request->comment) : null,
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
        $userId = $this->getSupportUserId();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $conversationId = $request->query('conversation_id');

        if ($conversationId) {
            $conversation = SupportConversation::with('messages', 'assignedAgent')->findOrFail($conversationId);
            if (!$this->checkConversationOwner($conversation)) {
                abort(403);
            }
        } else {
            $conversation = $this->getUserConversationsQuery()
                ->whereIn('status', ['bot_active', 'waiting_agent', 'assigned'])
                ->with('messages', 'assignedAgent')
                ->orderBy('created_at', 'desc')
                ->first();

            if (! $conversation) {
                return response()->json(['status' => 'no_content']);
            }
        }

        return response()->json([
            'status' => 'success',
            'conversation' => $conversation,
            'messages' => $conversation->messages,
        ]);
    }

    /**
     * Get all conversations for the authenticated user.
     */
    public function getUserConversations(Request $request)
    {
        $userId = $this->getSupportUserId();
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $conversations = $this->getUserConversationsQuery()
            ->with(['messages' => function ($q) {
                $q->latest()->limit(1);
            }, 'assignedAgent'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'conversations' => $conversations,
        ]);
    }

    /**
     * Sanitize user input to prevent XSS when displayed.
     * Strips tags and encodes special characters.
     */
    protected function sanitizeInput(string $text): string
    {
        return htmlspecialchars(strip_tags(trim($text)), ENT_QUOTES, 'UTF-8');
    }
}
