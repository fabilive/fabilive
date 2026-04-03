<?php

namespace App\Http\Middleware;

use App\Models\DeliveryChatThread;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDeliveryChatAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $threadId = $request->route('chat_thread_id') ?? $request->input('delivery_chat_thread_id');

        if (! $threadId) {
            return $next($request);
        }

        $thread = DeliveryChatThread::find($threadId);

        if (! $thread) {
            return response()->json(['message' => 'Chat thread not found.'], 404);
        }

        $user = Auth::user();
        $isRider = $user->id === $thread->rider_id;
        $isBuyer = $user->id === $thread->buyer_id;
        $isSeller = $user->id === $thread->seller_id;
        $isAdmin = ($user instanceof \App\Models\Admin) || ($user->is_admin ?? false) || (Auth::guard('admin')->check());

        if (! $isRider && ! $isBuyer && ! $isSeller && ! $isAdmin) {
            return response()->json(['message' => 'Unauthorized access to this chat.'], 403);
        }

        // Non-admins cannot see hidden threads
        if (! $isAdmin && $thread->hidden_at !== null) {
            return response()->json(['message' => 'This chat session has ended.'], 403);
        }

        // Delivery job must be active for participants (except admin)
        if (! $isAdmin) {
            $activeStatuses = ['accepted', 'picking_up', 'delivering', 'picked_up', 'delivered_pending_verification'];
            if (! in_array($thread->deliveryJob->status, $activeStatuses)) {
                return response()->json(['message' => 'Chat is no longer active for this delivery job.'], 403);
            }
        }

        return $next($request);
    }
}
