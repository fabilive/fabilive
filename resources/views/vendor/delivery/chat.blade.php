@extends('layouts.vendor')

@section('styles')
<style>
    .chat-container { height: 500px; display: flex; flex-direction: column; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top:20px; }
    .chat-messages { flex-grow: 1; overflow-y: auto; padding: 20px; background: #f9f9f9; }
    .message { margin-bottom: 15px; max-width: 80%; padding: 10px 15px; border-radius: 18px; position: relative; font-size: 0.95rem; line-height: 1.4; }
    .message.sent { align-self: flex-end; background: #2d3274; color: #fff; border-bottom-right-radius: 4px; margin-left: auto; }
    .message.received { align-self: flex-start; background: #e9ecef; color: #333; border-bottom-left-radius: 4px; }
    .chat-input-area { padding: 15px; background: #fff; border-top: 1px solid #dee2e6; }
    .chat-header { padding: 15px 20px; background: #2d3274; color: #fff; display: flex; align-items: center; justify-content: space-between; }
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Chat with Rider') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('vendor.delivery.index') }}">{{ __('Order Delivery') }}</a></li>
                    <li><a href="javascript:;">{{ __('Chat') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="chat-container">
                    <div class="chat-header">
                        <div>
                            <h5 class="mb-0 text-white">
                                {{ __('Rider') }}: {{ $thread->rider->name }}
                            </h5>
                            <small>{{ __('Order') }} #{{ $thread->deliveryJob->order->order_number }}</small>
                        </div>
                        <div>
                            @if($thread->rider->phone)
                            <a href="tel:{{ $thread->rider->phone }}" class="btn btn-sm btn-info mr-2">
                                <i class="fas fa-phone-alt"></i> {{ $thread->rider->phone }}
                            </a>
                            @endif
                            <a href="{{ route('vendor.delivery.index') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-arrow-left"></i> {{ __('Back to Delivery') }}
                            </a>
                        </div>
                    </div>

                    <div class="chat-messages d-flex flex-column" id="chat-box">
                        @forelse($thread->messages as $msg)
                            <div class="message {{ $msg->sender_id == auth()->id() ? 'sent' : 'received' }}">
                                {{ $msg->message }}
                                <div class="text-right mt-1" style="font-size: 0.7rem; opacity: 0.7;">
                                    {{ $msg->created_at->format('h:i A') }}
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center mt-5">{{ __('No messages yet. Send one to start the conversation!') }}</p>
                        @endforelse
                    </div>

                    <div class="chat-input-area">
                        <form id="chat-form" class="d-flex">
                            <input type="text" id="chat-message" class="form-control rounded-pill mr-2" placeholder="{{ __('Type your message here...') }}" required autocomplete="off">
                            <button type="submit" class="btn btn-primary rounded-circle" style="width: 45px; height: 45px;">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-message');
    const threadId = {{ $thread->id }};

    // Scroll to bottom
    chatBox.scrollTop = chatBox.scrollHeight;

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const msg = chatInput.value.trim();
        if(!msg) return;

        chatInput.disabled = true;

        fetch("{{ route('vendor-delivery-chat-send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                delivery_chat_thread_id: threadId,
                message: msg
            })
        })
        .then(res => res.json())
        .then(data => {
            chatInput.disabled = false;
            if(data.status) {
                appendMessage(msg, 'sent');
                chatInput.value = '';
                chatBox.scrollTop = chatBox.scrollHeight;
            } else {
                alert(data.message || 'Error sending message');
            }
        })
        .catch(err => {
            chatInput.disabled = false;
            console.error(err);
        });
    });

    function appendMessage(text, type) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const div = document.createElement('div');
        div.className = 'message ' + type;
        div.innerHTML = `
            ${text}
            <div class="text-right mt-1" style="font-size: 0.7rem; opacity: 0.7;">
                ${time}
            </div>
        `;
        chatBox.appendChild(div);
        
        const emptyMsg = chatBox.querySelector('.text-muted.text-center');
        if(emptyMsg) emptyMsg.remove();
    }

    setInterval(() => {
        fetch("{{ route('vendor-delivery-chat-messages', $thread->id) }}")
        .then(res => res.json())
        .then(data => {
            if(data.status) {
                const currentCount = chatBox.querySelectorAll('.message').length;
                if(data.messages.length > currentCount) {
                    location.reload(); 
                }
            }
        });
    }, 10000);
</script>
@endsection
