<div id="fabi-support-widget-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; font-family: 'Jost', sans-serif;">
    <!-- Launcher Button -->
    <button id="fabi-support-launcher" style="background: #000; color: #fff; border-radius: 50%; width: 60px; height: 60px; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.3); font-size: 28px; display: flex; align-items: center; justify-content: center; transition: transform 0.2s;">
        💬
    </button>

    <!-- Main Widget Window -->
    <div id="fabi-support-window" style="display: none; width: 380px; height: 550px; background: #fff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); display: flex; flex-direction: column; overflow: hidden; position: absolute; bottom: 80px; right: 0; border: 1px solid #eee;">
        
        <!-- Header -->
        <div style="background: #000; color: #fff; padding: 15px 20px; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <button id="fabi-support-back" style="display: none; background: transparent; border: none; color: #fff; cursor: pointer; font-size: 20px; padding: 0;">←</button>
                <span style="letter-spacing: 0.5px;">SpeedyAi</span>
            </div>
            <button id="fabi-support-close" style="background: transparent; border: none; color: #fff; cursor: pointer; font-size: 18px;">✖</button>
        </div>

        <!-- Body -->
        <div id="fabi-support-body" style="flex: 1; overflow-y: auto; padding: 0; background: #fff;">
            <!-- Step 1: Select Context -->
            <div id="fabi-support-context-selection" style="padding: 25px; text-align: center;">
                <img src="{{asset('assets/images/'.$gs->logo)}}" style="width: 80px; margin-bottom: 20px; border-radius: 10px;">
                <h3 style="margin-bottom: 10px; font-size: 18px; color: #000;">Welcome to SpeedyAi</h3>
                <p style="margin-bottom: 25px; font-size: 14px; color: #666;">How can we speed up your day today?</p>
                <button class="fabi-context-btn" data-context="buyer" style="width: 100%; padding: 14px; margin-bottom: 12px; border: 1px solid #000; background: #000; color: #fff; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.2s;">I am a Buyer</button>
                <button class="fabi-context-btn" data-context="vendor" style="width: 100%; padding: 14px; border: 1px solid #000; background: #fff; color: #000; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.2s;">I am a Seller / Vendor</button>
            </div>

            <!-- Step 2: FAQ View -->
            <div id="fabi-support-faq-view" style="display: none; padding: 20px;">
                <h4 style="font-size: 15px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 8px;">Frequently Asked Questions</h4>
                <div id="fabi-faq-container" style="max-height: 250px; overflow-y: auto; margin-bottom: 20px;">
                    <!-- FAQ items loaded via JS -->
                </div>
                <div style="text-align: center;">
                    <p style="font-size: 13px; color: #888; margin-bottom: 10px;">Don't see your answer?</p>
                    <button id="fabi-start-chat-btn" style="background: #000; color: #fff; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; font-size: 13px; font-weight: 600;">Chat with SpeedyAi</button>
                </div>
            </div>

            <!-- Step 3: Chat View -->
            <div id="fabi-support-chat-view" style="display: none; height: 100%; flex-direction: column; padding: 15px;">
                <div id="fabi-support-messages" style="flex: 1; overflow-y: auto; margin-bottom: 10px; font-size: 14px; display: flex; flex-direction: column; gap: 12px;">
                    <!-- Messages go here -->
                </div>
            </div>
        </div>

        <!-- Footer / Input -->
        <div id="fabi-support-footer" style="display: none; padding: 15px; border-top: 1px solid #eee; background: #fff;">
            <div style="display: flex; gap: 8px;">
                <input type="text" id="fabi-support-input" placeholder="Ask SpeedyAi something..." style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; font-size: 14px;">
                <button id="fabi-support-send" style="padding: 10px 18px; background: #000; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Send</button>
            </div>
            <button id="fabi-escalate-btn" style="margin-top: 10px; width: 100%; padding: 10px; background: #ff4757; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; display: none;">Request Live Support</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const launcher = document.getElementById('fabi-support-launcher');
        const windowEl = document.getElementById('fabi-support-window');
        const closeBtn = document.getElementById('fabi-support-close');
        const backBtn = document.getElementById('fabi-support-back');
        
        const contextBtns = document.querySelectorAll('.fabi-context-btn');
        const contextView = document.getElementById('fabi-support-context-selection');
        const faqView = document.getElementById('fabi-support-faq-view');
        const chatView = document.getElementById('fabi-support-chat-view');
        const faqContainer = document.getElementById('fabi-faq-container');
        const startChatBtn = document.getElementById('fabi-start-chat-btn');
        const footer = document.getElementById('fabi-support-footer');
        const messageContainer = document.getElementById('fabi-support-messages');
        const sendBtn = document.getElementById('fabi-support-send');
        const inputField = document.getElementById('fabi-support-input');
        const escalateBtn = document.getElementById('fabi-escalate-btn');

        let currentContext = null;
        let conversationId = null;
        const botLogo = "{{asset('assets/images/'.$gs->logo)}}";

        // Initial History Load
        loadHistory();

        function loadHistory() {
            fetch('/support/chat/history')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.conversation) {
                        conversationId = data.conversation.id;
                        currentContext = data.conversation.messages[0]?.context || 'buyer'; // Best guess if missing
                        
                        // Populate messages
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                let sender = msg.sender_type;
                                if (sender === 'admin' || sender === 'agent') sender = 'bot';
                                addMessage(sender, msg.body_text || '', !!msg.attachment_url);
                            });
                            
                            startChat();
                            backBtn.style.display = 'block';
                            
                            if (data.conversation.status === 'waiting_agent' || data.conversation.status === 'assigned') {
                                escalateBtn.style.display = 'none';
                                addMessage('system', 'Resuming live support session...');
                            }
                        }
                    }
                })
                .catch(err => console.log('No active session found.'));
        }

        // Toggle Widget
        launcher.addEventListener('click', () => {
            windowEl.style.display = windowEl.style.display === 'none' ? 'flex' : 'none';
            launcher.style.transform = windowEl.style.display === 'none' ? 'scale(1)' : 'scale(0.9)';
        });

        closeBtn.addEventListener('click', () => {
            windowEl.style.display = 'none';
            launcher.style.transform = 'scale(1)';
        });

        // Back Button
        backBtn.addEventListener('click', () => {
            resetToContext();
        });

        function resetToContext() {
            contextView.style.display = 'block';
            faqView.style.display = 'none';
            chatView.style.display = 'none';
            footer.style.display = 'none';
            backBtn.style.display = 'none';
            messageContainer.innerHTML = '';
            conversationId = null;
        }

        // Context Selection
        contextBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                currentContext = e.currentTarget.getAttribute('data-context');
                loadFaqs(currentContext);
                
                contextView.style.display = 'none';
                faqView.style.display = 'block';
                backBtn.style.display = 'block';
            });
        });

        function loadFaqs(context) {
            faqContainer.innerHTML = '<p style="font-size:12px; color:#999; text-align:center;">Loading help topics...</p>';
            fetch(`/support/faqs?context=${context}`)
                .then(res => res.json())
                .then(data => {
                    faqContainer.innerHTML = '';
                    if (data.categories && data.categories.length > 0) {
                        data.categories.forEach(cat => {
                            cat.faqs.forEach(faq => {
                                const item = document.createElement('div');
                                item.style.padding = '10px';
                                item.style.marginBottom = '8px';
                                item.style.background = '#f8f9fa';
                                item.style.borderRadius = '6px';
                                item.style.cursor = 'pointer';
                                item.style.fontSize = '13px';
                                item.style.border = '1px solid #eee';
                                item.innerHTML = `<strong>Q:</strong> ${faq.question}`;
                                item.onclick = () => {
                                    startChat();
                                    addMessage('user', faq.question);
                                    addMessage('bot', faq.answer_html, true);
                                };
                                faqContainer.appendChild(item);
                            });
                        });
                    } else {
                        faqContainer.innerHTML = '<p style="font-size:12px; color:#999; text-align:center;">No topics found for this role.</p>';
                    }
                });
        }

        startChatBtn.addEventListener('click', () => {
            startChat();
            addMessage('bot', `Hello! I am SpeedyAi. How can I help you today regarding your ${currentContext} account?`);
        });

        function startChat() {
            faqView.style.display = 'none';
            chatView.style.display = 'flex';
            footer.style.display = 'block';
        }

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
                    
                    if (data.bot_message.body_text.includes('Request Live Support')) {
                        escalateBtn.style.display = 'block';
                    }
                } else if (data.status === 'error') {
                    addMessage('system', data.message || 'An error occurred.');
                }
            })
            .catch(err => {
                addMessage('system', 'Unable to reach support. Are you logged in?');
            });
        }

        function addMessage(sender, text, isHtml = false) {
            const row = document.createElement('div');
            row.style.display = 'flex';
            row.style.flexDirection = sender === 'user' ? 'row-reverse' : 'row';
            row.style.alignItems = 'flex-start';
            row.style.gap = '8px';

            if (sender === 'bot') {
                const avatar = document.createElement('img');
                avatar.src = botLogo;
                avatar.style.width = '28px';
                avatar.style.height = '28px';
                avatar.style.borderRadius = '50%';
                avatar.style.border = '1px solid #eee';
                row.appendChild(avatar);
            }

            const msgEl = document.createElement('div');
            msgEl.style.padding = '10px 14px';
            msgEl.style.borderRadius = '12px';
            msgEl.style.fontSize = '14px';
            msgEl.style.maxWidth = '75%';
            msgEl.style.lineHeight = '1.4';

            if (sender === 'user') {
                msgEl.style.background = '#000';
                msgEl.style.color = '#fff';
                msgEl.style.borderBottomRightRadius = '2px';
            } else if (sender === 'bot') {
                msgEl.style.background = '#f1f1f1';
                msgEl.style.color = '#000';
                msgEl.style.borderBottomLeftRadius = '2px';
            } else {
                msgEl.style.background = '#fff3cd';
                msgEl.style.color = '#856404';
                msgEl.style.width = '100%';
                msgEl.style.textAlign = 'center';
                msgEl.style.fontSize = '12px';
            }

            if (isHtml) {
                msgEl.innerHTML = text;
            } else {
                msgEl.innerText = text;
            }
            
            row.appendChild(msgEl);
            messageContainer.appendChild(row);
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
                    addMessage('system', 'Escalation request sent to live agents.');
                }
            });
        });
    });
</script>
