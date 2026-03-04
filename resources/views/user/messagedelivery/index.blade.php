@extends('layouts.front')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/front/css/datatables.css') }}">
@endsection

@section('content')
@include('partials.global.common-header')

<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
    style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('Chat With Delivery Boy') }}</h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Chat With Delivery Boy') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->

<div class="full-row">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-xl-4">
                @include('partials.user.dashboard-sidebar')
            </div>

            <!-- Chat Section -->
            <div class="col-xl-8">
                <div class="row g-0 border rounded shadow-sm" style="height:600px;">

                    <!-- CHAT LIST -->
                    <div class="col-xl-3 col-lg-4 border-end bg-light overflow-auto">
                        <div class="p-3 border-bottom">
                            <h5 class="mb-0">{{ __('Chats') }}</h5>
                        </div>

                        <ul class="list-group list-group-flush" id="chat-list">
                            @forelse($chats as $chat)
                                <li class="list-group-item list-group-item-action chat-item
                                       d-flex align-items-center justify-content-between"
                                    data-chat-id="{{ $chat->id }}"  data-product-name="{{ implode(', ', $chat->product_names ?? []) }}" style="cursor: pointer">
                                    <strong class="p-1">{{ $chat->rider->name ?? 'Unknown Rider' }}</strong>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">
                                    No conversations found
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- CHAT AREA -->
                    <div class="col-xl-9 col-lg-8 d-flex flex-column bg-white">
                        <div class="p-3 border-bottom">
                            <h6 class="mb-0" id="chat-header">{{ __('Select a chat') }}</h6>
                        </div>

                        <!-- MESSAGES -->
                        <div class="flex-grow-1 overflow-auto p-3" id="chat-messages"
                             style="background:#f9f9f9; font-size:0.9rem; height:500px; max-height:500px; overflow-y:auto;">
                            <p class="text-muted text-center mt-5">Select a chat to view messages</p>
                        </div>

                        <!-- INPUT -->
                        <form id="chat-form" class="d-flex p-3 border-top bg-light">
                            <input type="text" class="form-control me-2 rounded-pill" id="chat-input" placeholder="Type a message...">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('Send') }}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
    let currentChatId = null;
    const chatItems = document.querySelectorAll('.chat-item');
    const chatHeader = document.getElementById('chat-header');
    const chatBox = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const userId = {{ auth()->id() }};

    // CLICK CHAT → fetch messages from user-chat-fetch route ONLY
    chatItems.forEach(item => {
    item.addEventListener('click', function () {

        chatItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');

        currentChatId = this.dataset.chatId;

        const riderName = this.dataset.riderName;
        const productName = this.dataset.productName || 'N/A';

        // 🔥 THIS is what you want
        chatHeader.textContent = `Product: ${productName}`;

        // Fetch messages
        fetch("{{ route('user-chat-fetch') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ chat_id: currentChatId })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.status) return;
            renderMessages(data.messages);
            subscribePusherChannel(currentChatId);
        });
    });
});


    // Render messages
    function renderMessages(messages) {
        chatBox.innerHTML = '';
        if (!messages.length) {
            chatBox.innerHTML = `<p class="text-muted text-center mt-5">No messages yet</p>`;
            return;
        }

        messages.forEach(msg => {
            const isUser = msg.sender_id == userId;
            const align = isUser ? 'justify-content-end' : '';
            const bg = isUser ? 'bg-primary text-white' : 'bg-light border';

            chatBox.innerHTML += `
                <div class="d-flex ${align} mb-2">
                    <div class="p-2 rounded ${bg}" style="max-width:70%">
                        ${msg.message}
                    </div>
                </div>
            `;
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // SEND MESSAGE → call only user-chat-send route
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!currentChatId) return alert('Please select a chat first');
        const msg = chatInput.value.trim();
        if (!msg) return;

        fetch("{{ route('user-chat-send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ chat_id: currentChatId, message: msg })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.status) return alert(data.message || 'Message failed to send');

            chatBox.innerHTML += `
                <div class="d-flex justify-content-end mb-2">
                    <div class="p-2 rounded bg-primary text-white" style="max-width:70%">
                        ${msg}
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;
            chatInput.value = '';
        });
    });

    let pusherChannel = null;
    function subscribePusherChannel(chatId) {
        if (pusherChannel) {
            pusherChannel.unbind_all();
            pusherChannel.unsubscribe();
        }

        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", { cluster: "mt1", forceTLS: true });
        pusherChannel = pusher.subscribe('chat.' + chatId);

        pusherChannel.bind('MessageSents', function(e) {
            // Only append messages for this chat
            if (parseInt(e.chat_id) !== parseInt(currentChatId)) return;
            if (e.sender_id == userId) return;

            chatBox.innerHTML += `
                <div class="d-flex mb-2">
                    <div class="p-2 rounded bg-light border" style="max-width:70%">
                        ${e.message}
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    }
</script>

@includeIf('partials.global.common-footer')
@endsection
