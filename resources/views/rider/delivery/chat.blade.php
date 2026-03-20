@extends('layouts.front')
@section('css')
<style>
    .chat-container { height: 500px; display: flex; flex-direction: column; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .chat-messages { flex-grow: 1; overflow-y: auto; padding: 20px; background: #f9f9f9; }
    .message { margin-bottom: 15px; max-width: 80%; padding: 10px 15px; border-radius: 18px; position: relative; font-size: 0.95rem; line-height: 1.4; }
    .message.sent { align-self: flex-end; background: #2d3274; color: #fff; border-bottom-right-radius: 4px; margin-left: auto; }
    .message.received { align-self: flex-start; background: #e9ecef; color: #333; border-bottom-left-radius: 4px; }
    .chat-input-area { padding: 15px; background: #fff; border-top: 1px solid #dee2e6; }
    .chat-header { padding: 15px 20px; background: #2d3274; color: #fff; display: flex; align-items: center; justify-content: space-between; }
</style>
@endsection

@section('content')
@include('partials.global.common-header')

<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('Chat') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="full-row">
    <div class="container">
        <div class="row">
            <div class="col-xl-3">
                @include('partials.rider.dashboard-sidebar')
            </div>
            <div class="col-xl-9">
                <div class="chat-container">
                    <div class="chat-header">
                        <div>
                            <h5 class="mb-0 text-white">
                                @if($thread->thread_type == 'rider_seller')
                                    {{ __('Seller') }}: {{ $thread->seller->shop_name }}
                                @else
                                    {{ __('Buyer') }}: {{ $thread->buyer->name }}
                                @endif
                            </h5>
                            <small>{{ __('Order') }} #{{ $thread->deliveryJob->order->order_number }}</small>
                        </div>
                        <a href="{{ route('rider-delivery-details', $thread->delivery_job_id) }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left"></i> {{ __('Back to Job') }}
                        </a>
                    </div>

                    <div class="chat-messages d-flex flex-column" id="chat-box">
                        @forelse($thread->messages as $msg)
                            <div class="message {{ $msg->sent_user == auth()->guard('rider')->id() ? 'sent' : 'received' }}">
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

@includeIf('partials.global.common-footer')
@endsection

@section('script')
<script>
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-message');
    const threadId = {{ $thread->id }};
    const riderId = {{ auth()->guard('rider')->id() }};

    // Scroll to bottom
    chatBox.scrollTop = chatBox.scrollHeight;

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const msg = chatInput.value.trim();
        if(!msg) return;

        chatInput.disabled = true;

        fetch("{{ route('rider-delivery-chat-send') }}", {
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
        
        // Remove "No messages yet" if it exists
        const emptyMsg = chatBox.querySelector('.text-muted.text-center');
        if(emptyMsg) emptyMsg.remove();
    }

    // Polling for new messages (simple implementation for now)
    setInterval(() => {
        fetch("{{ url('/rider/delivery/chat/messages') }}/" + threadId)
        .then(res => res.json())
        .then(data => {
            if(data.status) {
                const currentCount = chatBox.querySelectorAll('.message').length;
                if(data.messages.length > currentCount) {
                    // Refresh if new messages (could be optimized)
                    location.reload(); 
                }
            }
        });
    }, 10000); // Check every 10s
</script>
@endsection
