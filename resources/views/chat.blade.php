<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with {{ $user->name }}</title>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Chat Popup */
        #chat-popup {
            width: 100%;
            max-width: 400px;
            height: 500px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            font-family: Arial, sans-serif;
        }

        /* Chat Header */
        #chat-header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        /* Chat Body */
        #chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
        }

        /* Chat Messages */
        .message {
            padding: 10px 14px;
            margin: 5px 0;
            border-radius: 20px;
            max-width: 75%;
            word-wrap: break-word;
            font-size: 14px;
            display: inline-block;
        }

        .sent {
            background: #007bff;
            color: white;
            align-self: flex-end;
            text-align: right;
        }

        .received {
            background: #e9ecef;
            color: black;
            align-self: flex-start;
            text-align: left;
        }

        /* Chat Footer */
        #chat-footer {
            display: flex;
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ddd;
        }

        #message {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            outline: none;
            font-size: 14px;
        }

        #sendMessage {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 14px;
        }

        #sendMessage:hover {
            background: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 500px) {
            #chat-popup {
                width: 90%;
                right: 5%;
                bottom: 10px;
            }

            #message {
                font-size: 12px;
                padding: 8px;
            }

            #sendMessage {
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<!-- Open Chat Button -->
<button id="open-chat">Chat with {{ $user->name }}</button>

<!-- Chat Popup -->
<div id="chat-popup" class="d-none">
    <div id="chat-header">Chat with {{ $user->name }}</div>
    <div id="chat-body">
        @foreach ($messages as $message)
            <p class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
                <strong>{{ $message->sender->name }}:</strong> {{ $message->content }}
            </p>
        @endforeach
    </div>
    <div id="chat-footer">
        <input type="text" id="message" placeholder="Type a message...">
        <button id="sendMessage">Send</button>
    </div>
</div>

<script>
    // Toggle Chat Popup
    $("#open-chat").click(function () {
        $("#chat-popup").toggle();
    });

    // Enable Auto-Scrolling to Latest Message
    function scrollToBottom() {
        $("#chat-body").scrollTop($("#chat-body")[0].scrollHeight);
    }
    scrollToBottom(); // Scroll on page load

    // Pusher Setup
    Pusher.logToConsole = true;
    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        encrypted: true
    });

    var channel = pusher.subscribe("private-chat.{{ auth()->id() }}");

    // Listen for New Messages
    channel.bind("MessageSent", function (data) {
        console.log("Received:", data);
        $("#chat-body").append(`
            <p class="message received">
                <strong>${data.sender_name}:</strong> ${data.message}
            </p>
        `);
        scrollToBottom();
    });

    // Send Message
    $("#sendMessage").click(function () {
        let message = $("#message").val();
        console.log(message);
        let user_id = "{{ $user->id }}";
        console.log(message);
        if (message.trim() === "") return;
        $.ajax({
            url: "{{ route('chat.send') }}",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                _token: "{{ csrf_token() }}",
                user_id: user_id,
                message: message,
            }),
            success: function (response) {
                console.log(response);
                $("#chat-body").append(`
                    <p class="message sent"><strong>You:</strong> ${message}</p>
                `);
                $("#message").val(""); // Clear input after sending
                scrollToBottom();
            },
            error: function (error) {
                $("#message").val("");
                console.log("Error saving message:", error);
            }
        });
    });
</script>

</body>
</html>
