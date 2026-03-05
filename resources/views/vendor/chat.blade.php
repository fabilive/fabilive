@extends('layouts.vendor')

@section('content')
<style>
    h3 {
        text-align: center;
        margin-bottom: 15px;
    }
    #chat-body {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f9f9f9;
    }
    .message {
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 10px;
        width: fit-content;
        max-width: 80%;
    }
    .sent {
        background: #007bff;
        color: white;
        margin-left: auto;
        text-align: right;
    }
    .received {
        background: #e0e0e0;
        color: black;
        text-align: left;
    }
    #chat-footer {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    #message {
        flex: 1;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin: 0;
    }
    #sendMessage {
        padding: 10px 15px;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        margin-left: 10px;
        font-size: 14px;
    }

    #sendMessage:hover {
        background: #218838;
    }
</style>

<div class="content-area">
    <h3>Chat with {{ \App\Models\User::find($customerId)->name }}</h3>
    <div id="chat-body">
        @foreach ($messages as $message)
            <p class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
                <strong>{{ $message->sender->name }}:</strong> {{ $message->content }}
            </p>
        @endforeach
    </div>

    <div id="chat-footer">
        <input type="text" id="message" placeholder="Type a message...">
        <button id="sendMessage" type="button">Send</button>
    </div>
</div>

<script>
    Pusher.logToConsole = true;
    var pusher = new Pusher("3ccc506b109bd00544fe", {
        cluster: "mt1",
        encrypted: true
    });
    
    function scrollToBottom() {
        var chatBody = document.getElementById("chat-body");
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    
    // Scroll to bottom on page load
    document.addEventListener("DOMContentLoaded", function () {
        scrollToBottom();
    });

    var channel = pusher.subscribe("private-chat.{{ auth()->id() }}");

    channel.bind("MessageSent", function (data) {
        if (data.sender_id === {{ $customerId }}) {
            let chatBody = document.getElementById("chat-body");
            chatBody.innerHTML += `
                <p class="message received">
                    <strong>${data.sender_name}:</strong> ${data.message}
                </p>
            `;
            scrollToBottom();
        }
    });
    
    

    document.getElementById("sendMessage").addEventListener("click", function () {
        let message = document.getElementById("message").value;
        if (message.trim() === "") return;

        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                message: message,
                user_id: {{ $customerId }}
            })
        }).then(response => response.json()).then(data => {
            let chatBody = document.getElementById("chat-body");
            chatBody.innerHTML += `
                <p class="message sent">
                    <strong>You:</strong> ${message}
                </p>
            `;
            document.getElementById("message").value = "";
             scrollToBottom();
        });
    });
</script>
@endsection
