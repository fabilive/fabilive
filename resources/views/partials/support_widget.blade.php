<div id="fabi-support-widget-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <!-- Launcher Button -->
    <button id="fabi-support-launcher" style="background: #000; color: #fff; border-radius: 50%; width: 60px; height: 60px; border: none; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); font-size: 24px; display: flex; align-items: center; justify-content: center;">
        💬
    </button>

    <!-- Main Widget Window -->
    <div id="fabi-support-window" style="display: none; width: 350px; height: 500px; background: #fff; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.15); display: flex; flex-direction: column; overflow: hidden; position: absolute; bottom: 80px; right: 0;">
        
        <!-- Header -->
        <div style="background: #000; color: #fff; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
            <span>Fabilive Support</span>
            <button id="fabi-support-close" style="background: transparent; border: none; color: #fff; cursor: pointer; font-size: 16px;">✖</button>
        </div>

        <!-- Body -->
        <div id="fabi-support-body" style="flex: 1; overflow-y: auto; padding: 15px; background: #f9f9f9;">
            <!-- Step 1: Select Context -->
            <div id="fabi-support-context-selection">
                <p style="margin-bottom: 20px; font-size: 14px; color: #333;">How can we help you today?</p>
                <button class="fabi-context-btn" data-context="buyer" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; background: #fff; border-radius: 5px; cursor: pointer; font-weight: bold;">I am a Buyer</button>
                <button class="fabi-context-btn" data-context="vendor" style="width: 100%; padding: 12px; border: 1px solid #ddd; background: #fff; border-radius: 5px; cursor: pointer; font-weight: bold;">I am a Seller/Vendor</button>
            </div>

            <!-- Step 2: Chat View (Hidden initially) -->
            <div id="fabi-support-chat-view" style="display: none; height: 100%; flex-direction: column;">
                <div id="fabi-support-messages" style="flex: 1; overflow-y: auto; margin-bottom: 10px; font-size: 14px;">
                    <!-- Messages go here -->
                </div>
            </div>
        </div>

        <!-- Footer / Input -->
        <div id="fabi-support-footer" style="display: none; padding: 10px; border-top: 1px solid #eee; background: #fff;">
            <div style="display: flex;">
                <input type="text" id="fabi-support-input" placeholder="Type your message..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; outline: none;">
                <button id="fabi-support-send" style="margin-left: 10px; padding: 10px 15px; background: #000; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Send</button>
            </div>
            <button id="fabi-escalate-btn" style="margin-top: 10px; width: 100%; padding: 8px; background: #ff4757; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 12px; display: none;">Request Live Support</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const launcher = document.getElementById('fabi-support-launcher');
        const windowEl = document.getElementById('fabi-support-window');
        const closeBtn = document.getElementById('fabi-support-close');
        
        const contextBtns = document.querySelectorAll('.fabi-context-btn');
        const contextView = document.getElementById('fabi-support-context-selection');
        const chatView = document.getElementById('fabi-support-chat-view');
        const footer = document.getElementById('fabi-support-footer');
        const messageContainer = document.getElementById('fabi-support-messages');
        const sendBtn = document.getElementById('fabi-support-send');
        const inputField = document.getElementById('fabi-support-input');
        const escalateBtn = document.getElementById('fabi-escalate-btn');

        let currentContext = null;
        let conversationId = null;

        // Toggle Widget
        launcher.addEventListener('click', () => {
            windowEl.style.display = windowEl.style.display === 'none' ? 'flex' : 'none';
        });

        closeBtn.addEventListener('click', () => {
            windowEl.style.display = 'none';
        });

        // Context Selection
        contextBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                currentContext = e.target.getAttribute('data-context');
                contextView.style.display = 'none';
                chatView.style.display = 'flex';
                footer.style.display = 'block';
                
                addMessage('bot', `Hello! How can I help you today regarding your ${currentContext} account?`);
            });
        });

        // Send Message
        sendBtn.addEventListener('click', sendMessage);
        inputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        function sendMessage() {
            const text = inputField.value.trim();
            if (!text) return;

            addMessage('user', text);
            inputField.value = '';

            // API Call
            fetch('/support/bot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    context: currentContext,
                    message: text,
                    conversation_id: conversationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    conversationId = data.conversation_id;
                    addMessage('bot', data.bot_message.body_text);
                    
                    // Show escalate button if bot returns a specific phrase indicating failure
                    if (data.bot_message.body_text.includes('Request Live Support')) {
                        escalateBtn.style.display = 'block';
                    }
                } else if (data.status === 'error') {
                    addMessage('system', data.message || 'An error occurred.');
                }
            })
            .catch(err => {
                addMessage('system', 'Unable to reach support at this time. Are you logged in?');
            });
        }

        function addMessage(sender, text) {
            const msgEl = document.createElement('div');
            msgEl.style.marginBottom = '10px';
            msgEl.style.padding = '10px';
            msgEl.style.borderRadius = '5px';
            msgEl.style.clear = 'both';
            msgEl.style.maxWidth = '85%';

            if (sender === 'user') {
                msgEl.style.background = '#000';
                msgEl.style.color = '#fff';
                msgEl.style.float = 'right';
            } else if (sender === 'bot') {
                msgEl.style.background = '#e9ecef';
                msgEl.style.color = '#333';
                msgEl.style.float = 'left';
            } else {
                msgEl.style.background = '#fff3cd';
                msgEl.style.color = '#856404';
                msgEl.style.textAlign = 'center';
                msgEl.style.width = '100%';
                msgEl.style.maxWidth = '100%';
                msgEl.style.fontSize = '12px';
            }

            msgEl.innerText = text;
            messageContainer.appendChild(msgEl);
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        // Handle Escalation
        escalateBtn.addEventListener('click', () => {
            escalateBtn.innerText = 'Requesting...';
            escalateBtn.disabled = true;

            fetch('/support/live/request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ conversation_id: conversationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    escalateBtn.style.display = 'none';
                    addMessage('system', 'Escalation request sent.');
                    // In a full implementation, you would listen to Echo events here
                    // window.Echo.private(`support.conversation.${conversationId}`).listen('SupportMessageSent', (e) => { addMessage('agent', e.message.body_text); });
                }
            });
        });
    });
</script>
