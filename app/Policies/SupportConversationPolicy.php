<?php

namespace App\Policies;

use App\Models\SupportConversation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupportConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the support conversation.
     *
     * @param  Authenticatable  $user
     * @param  SupportConversation  $conversation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Authenticatable $user, SupportConversation $conversation)
    {
        // If it's an admin, they can view everything. 
        // If it's a user, they can only view their own.
        if (method_exists($user, 'canAccessPanel')) {
            return true;
        }

        return $user->id === $conversation->requester_user_id;
    }

    /**
     * Determine whether the user can send a message to the support conversation.
     *
     * @param  Authenticatable  $user
     * @param  SupportConversation  $conversation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function message(Authenticatable $user, SupportConversation $conversation)
    {
        if (method_exists($user, 'canAccessPanel')) {
            return $conversation->status !== 'ended';
        }

        return $user->id === $conversation->requester_user_id && $conversation->status !== 'ended';
    }
}
