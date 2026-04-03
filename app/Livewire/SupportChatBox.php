<?php

namespace App\Livewire;

use App\Models\SupportConversation;
use App\Models\SupportConversationEvent;
use App\Models\SupportMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupportChatBox extends Component
{
    use WithFileUploads;

    public SupportConversation $conversation;

    public $message = '';

    public $attachment = null;

    public function mount(SupportConversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function sendReply()
    {
        $this->validate([
            'message' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|image|max:10240', // 10MB
        ]);

        $msgData = [
            'conversation_id' => $this->conversation->id,
            'sender_type' => 'agent',
            'sender_id' => Auth::id(),
            'type' => 'text',
            'body_text' => $this->message,
        ];

        if ($this->attachment) {
            $path = $this->attachment->store('support_attachments', 'public');
            $msgData['type'] = 'image';
            $msgData['attachment_url'] = '/storage/'.$path;
        }

        SupportMessage::create($msgData);

        // Auto-assign and update status
        if ($this->conversation->status === 'bot_active' || $this->conversation->status === 'waiting_agent') {
            $this->conversation->status = 'assigned';
            $this->conversation->assigned_agent_admin_id = Auth::id();
            $this->conversation->assigned_at = now();
            $this->conversation->save();

            SupportConversationEvent::create([
                'conversation_id' => $this->conversation->id,
                'actor_type' => 'agent',
                'actor_id' => Auth::id(),
                'event' => 'assigned',
            ]);
        }

        $this->reset(['message', 'attachment']);

        // Notify and Refresh
        $this->dispatch('refreshRelationManager');

        session()->flash('message', 'Reply sent successfully!');
    }

    public function render()
    {
        return view('livewire.support-chat-box');
    }
}
