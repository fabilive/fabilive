<?php

namespace App\Policies;

use App\Models\SupportConversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupportConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the support conversation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SupportConversation  $conversation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SupportConversation $conversation)
    {
        return $user->id === $conversation->requester_user_id;
    }

    /**
     * Determine whether the user can send a message to the support conversation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SupportConversation  $conversation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function message(User $user, SupportConversation $conversation)
    {
        return $user->id === $conversation->requester_user_id && $conversation->status !== 'ended';
    }
}
