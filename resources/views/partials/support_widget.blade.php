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
                <span style="letter-spacing: 0.5px;">MbokoAi</span>
            </div>
            <button id="fabi-support-close" style="background: transparent; border: none; color: #fff; cursor: pointer; font-size: 18px;">✖</button>
        </div>

        <!-- Body -->
        <div id="fabi-support-body" style="flex: 1; overflow-y: auto; padding: 0; background: #fff;">
            <!-- Step 1: Select Context -->
            <div id="fabi-support-context-selection" style="padding: 25px; text-align: center;">
                <img src="{{asset('assets/images/'.$gs->logo)}}" style="width: 80px; margin-bottom: 20px; border-radius: 10px;">
                <h3 style="margin-bottom: 10px; font-size: 18px; color: #000;">Welcome to MbokoAi</h3>
                <p style="margin-bottom: 25px; font-size: 14px; color: #666;">How can we help you today?</p>
                
                @auth
                    <button class="fabi-context-btn" data-context="buyer" style="width: 100%; padding: 14px; margin-bottom: 12px; border: 1px solid #000; background: #000; color: #fff; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.2s;">I am a Buyer</button>
                    <button class="fabi-context-btn" data-context="vendor" style="width: 100%; padding: 14px; border: 1px solid #000; background: #fff; color: #000; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.2s;">I am a Seller / Vendor</button>
                @else
                    <div style="background: #fff8e1; color: #856404; padding: 15px; border-radius: 10px; text-align: left; margin-bottom: 20px; border: 1px solid #ffe082; font-size: 13.5px; line-height: 1.5;">
                        <span style="font-size: 18px; margin-bottom: 5px; display: block;">🔒 <strong>Member Access Only</strong></span>
                        Hello! I am <strong>MbokoAi</strong>. To help you better, please <a href="{{ route('user.login') }}" style="color: #000; text-decoration: underline; font-weight: bold;">Sign In</a> or <a href="{{ route('user.register') }}" style="color: #000; text-decoration: underline; font-weight: bold;">Sign Up</a> so we can access your order details.
                    </div>
                    <button style="width: 100%; padding: 14px; background: #f1f1f1; color: #999; border: 1px solid #ddd; border-radius: 8px; cursor: not-allowed; font-weight: 600;" disabled>Chat Restricted to Users</button>
                @endauth
                
                <div id="fabi-support-inbox" style="margin-top: 30px; text-align: left; display: none;">
                    <h4 style="font-size: 13px; color: #888; border-top: 1px solid #eee; padding-top: 15px; margin-bottom: 10px;">Your Recent Chats</h4>
                    <div id="fabi-inbox-list" style="display: flex; flex-direction: column; gap: 10px;">
                        <!-- Inbox items -->
                    </div>
                </div>
            </div>

            <!-- Step 2: FAQ View -->
            <div id="fabi-support-faq-view" style="display: none; padding: 20px;">
                <h4 style="font-size: 15px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 8px;">Frequently Asked Questions</h4>
                <div id="fabi-faq-container" style="max-height: 250px; overflow-y: auto; margin-bottom: 20px;">
                    <!-- FAQ items loaded via JS -->
                </div>
                <div style="text-align: center;">
                    <p style="font-size: 13px; color: #888; margin-bottom: 10px;">Don't see your answer?</p>
                    <button id="fabi-start-chat-btn" style="background: #000; color: #fff; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; font-size: 13px; font-weight: 600;">Chat with MbokoAi</button>
                </div>
            </div>

            <!-- Step 3: Chat View -->
            <div id="fabi-support-chat-view" style="display: none; height: 100%; flex-direction: column; padding: 15px;">
                <div id="fabi-support-messages" style="flex: 1; overflow-y: auto; margin-bottom: 10px; font-size: 14px; display: flex; flex-direction: column; gap: 12px;">
                    <!-- Messages go here -->
                </div>

                <!-- Step 4: Rating View (Appears inside chat view) -->
                <div id="fabi-support-rating-view" style="display: none; padding: 20px; background: #f9f9f9; border-radius: 10px; text-align: center; border: 1px solid #eee; margin-top: auto;">
                    <h4 style="font-size: 16px; margin-bottom: 10px; color: #000;">How was your experience?</h4>
                    <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Please rate your conversation with our agent.</p>
                    
                    <div id="fabi-star-rating" style="display: flex; justify-content: center; gap: 8px; margin-bottom: 20px; font-size: 30px;">
                        <span class="fabi-star" data-rating="1" style="cursor: pointer; color: #ccc;">★</span>
                        <span class="fabi-star" data-rating="2" style="cursor: pointer; color: #ccc;">★</span>
                        <span class="fabi-star" data-rating="3" style="cursor: pointer; color: #ccc;">★</span>
                        <span class="fabi-star" data-rating="4" style="cursor: pointer; color: #ccc;">★</span>
                        <span class="fabi-star" data-rating="5" style="cursor: pointer; color: #ccc;">★</span>
                    </div>

                    <textarea id="fabi-rating-comment" placeholder="Any additional feedback? (Optional)" style="width: 100%; height: 60px; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; font-size: 13px; resize: none;"></textarea>
                    
                    <button id="fabi-submit-rating" style="width: 100%; padding: 12px; background: #000; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Submit Rating</button>
                </div>
            </div>
        </div>

        <!-- Footer / Input -->
        <div id="fabi-support-footer" style="display: none; padding: 15px; border-top: 1px solid #eee; background: #fff;">
            <div style="display: flex; gap: 8px; align-items: center;">
                <input type="file" id="fabi-support-attach-input" style="display: none;" accept="image/*">
                <button id="fabi-support-attach" style="background: transparent; border: none; cursor: pointer; font-size: 22px; color: #666; padding: 5px; display: flex; align-items: center; justify-content: center; transition: 0.2s;">+</button>
                <input type="text" id="fabi-support-input" placeholder="Ask MbokoAi something..." style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; font-size: 14px;">
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
        let conversationStatus = null;
        let pollInterval = null;
        let selectedRating = 0;
        const botLogo = "{{asset('assets/images/'.$gs->logo)}}";

        // Initial Inbox Load
        loadInbox();

        function loadInbox() {
            fetch('/support/chat/list')
                .then(res => res.json())
                .then(data => {
                    const inboxContainer = document.getElementById('fabi-support-inbox');
                    const inboxList = document.getElementById('fabi-inbox-list');
                    
                    if (data.status === 'success' && data.conversations.length > 0) {
                        inboxContainer.style.display = 'block';
                        inboxList.innerHTML = '';
                        
                        // Check if any is active - if so, auto-resume only if it's been active recently?
                        // For now, let user pick from inbox.
                        data.conversations.forEach(conv => {
                            const lastMsg = conv.messages[0] ? conv.messages[0].body_text : 'No messages yet';
                            const item = document.createElement('div');
                            item.style.padding = '12px';
                            item.style.border = '1px solid #eee';
                            item.style.borderRadius = '8px';
                            item.style.cursor = 'pointer';
                            item.style.fontSize = '13px';
                            item.style.background = '#fff';
                            item.style.transition = '0.2s';
                            
                            item.onmouseover = () => item.style.background = '#f9f9f9';
                            item.onmouseout = () => item.style.background = '#fff';
                            item.onclick = () => openConversation(conv.id, conv.status);
                            
                            const statusColor = (conv.status === 'ended' || conv.status === 'rated') ? '#888' : '#2ed573';
                            const statusLabel = conv.status.replace('_', ' ').charAt(0).toUpperCase() + conv.status.replace('_', ' ').slice(1);

                            item.innerHTML = `
                                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                    <span style="font-weight:bold; color:#000;">${conv.context.toUpperCase()} Support</span>
                                    <span style="font-size:10px; color:${statusColor}; font-weight:bold;">${statusLabel}</span>
                                </div>
                                <div style="color:#666; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${lastMsg}</div>
                            `;
                            
                            item.onclick = () => openConversation(conv.id, conv.status);
                            item.onmouseover = () => item.style.borderColor = '#000';
                            item.onmouseout = () => item.style.borderColor = '#eee';
                            
                            inboxList.appendChild(item);
                        });
                    }
                });
        }

        function openConversation(id, status) {
            conversationId = id;
            conversationStatus = status;
            messageContainer.innerHTML = '<p style="text-align:center; font-size:12px; color:#999;">Loading history...</p>';
            
            fetch(`/support/chat/history?conversation_id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        messageContainer.innerHTML = '';
                        currentContext = data.conversation.context;
                        
                        // Clear any existing polling
                        if (pollInterval) clearInterval(pollInterval);
                        
                        data.messages.forEach(msg => {
                            let sender = msg.sender_type;
                            if (sender === 'admin' || sender === 'agent') sender = 'bot'; // Agents show with bot avatar on user side for consistency or custom agent avatar?
                            addMessage(sender, msg.body_text || '', !!msg.attachment_url);
                        });
                        
                        startChat();
                        backBtn.style.display = 'block';
                        
                        updateChatUI(data.conversation.status);
                        
                        // Start polling if active
                        if (status !== 'ended' && status !== 'rated') {
                            startPolling();
                        }
                    }
                });
        }

        function updateChatUI(status) {
            conversationStatus = status;
            const ratingView = document.getElementById('fabi-support-rating-view');
            const messagesList = document.getElementById('fabi-support-messages');
            
            if (status === 'ended' || status === 'rated') {
                if (pollInterval) clearInterval(pollInterval);
                footer.style.display = 'block'; 
                document.getElementById('fabi-support-input').placeholder = 'This chat is closed.';
                document.getElementById('fabi-support-input').disabled = true;
                document.getElementById('fabi-support-send').disabled = true;
                document.getElementById('fabi-support-attach').disabled = true;
                escalateBtn.style.display = 'none';

                if (status === 'ended') {
                    ratingView.style.display = 'block';
                    messagesList.style.display = 'none';
                } else {
                    ratingView.style.display = 'none';
                    messagesList.style.display = 'flex';
                }
            } else {
                ratingView.style.display = 'none';
                messagesList.style.display = 'flex';
                document.getElementById('fabi-support-input').placeholder = 'Ask MbokoAi something...';
                document.getElementById('fabi-support-input').disabled = false;
                document.getElementById('fabi-support-send').disabled = false;
                document.getElementById('fabi-support-attach').disabled = false;
                
                if (status === 'waiting_agent' || status === 'assigned') {
                    escalateBtn.style.display = 'none';
                } else {
                    // Check if it should be shown
                    // Only show if bot indicated it
                }
            }
        }

        function startPolling() {
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(() => {
                if (!conversationId) return;
                
                fetch(`/support/chat/history?conversation_id=${conversationId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Check for status change
                            if (data.conversation.status !== conversationStatus) {
                                if (data.conversation.status === 'assigned' && conversationStatus === 'waiting_agent') {
                                    addMessage('system', 'A live agent has joined the chat.');
                                }
                                updateChatUI(data.conversation.status);
                            }
                            
                            // Check for new messages
                            const currentMsgCount = messageContainer.children.length;
                            const newMsgCount = data.messages.length;
                            
                            if (newMsgCount > currentMsgCount) {
                                // Add only new messages
                                const newMessages = data.messages.slice(currentMsgCount);
                                newMessages.forEach(msg => {
                                    let sender = msg.sender_type;
                                    if (sender === 'admin' || sender === 'agent') sender = 'bot';
                                    addMessage(sender, msg.body_text || '', !!msg.attachment_url);
                                });
                            }
                        }
                    });
            }, 5000);
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
            addMessage('bot', `Hello! I am MbokoAi. How can I help you today regarding your ${currentContext} account?`);
        });

        function startChat() {
            faqView.style.display = 'none';
            chatView.style.display = 'flex';
            footer.style.display = 'block';
            startPolling();
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
                    'Accept': 'application/json',
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
                    
                    const msgTextLower = data.bot_message.body_text.toLowerCase();
                    const triggerKeywords = ['request live support', 'live agent', 'human agent', 'requesting live agent', 'real person'];
                    const shouldShowEscalate = triggerKeywords.some(keyword => msgTextLower.includes(keyword));

                    if (shouldShowEscalate) {
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

        // Image Upload Logic
        const attachBtn = document.getElementById('fabi-support-attach');
        const fileInput = document.getElementById('fabi-support-attach-input');

        attachBtn.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', uploadImage);

        function uploadImage() {
            const file = fileInput.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('attachment', file);
            formData.append('conversation_id', conversationId);
            formData.append('context', currentContext);

            addMessage('user', `Uploading ${file.name}...`);

            fetch('/support/bot/chat', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    conversationId = data.conversation_id;
                    addMessage('bot', data.bot_message.body_text);
                } else {
                    addMessage('system', data.message || 'Upload failed.');
                }
                fileInput.value = '';
            })
            .catch(err => {
                addMessage('system', 'Error uploading image.');
                fileInput.value = '';
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

        escalateBtn.addEventListener('click', () => {
            if (!conversationId) {
                alert('Connection establishing... please try again in a second.');
                return;
            }

            escalateBtn.innerText = 'Requesting Live Support...';
            escalateBtn.disabled = true;

            const updateUIAsWaiting = () => {
                escalateBtn.style.display = 'none';
                conversationStatus = 'waiting_agent';
                console.log('MbokoAi: Scaling to live agent view.');
            };

            // 1. Race the fetch against a 5-second timeout
            const escalationTimeout = new Promise((_, reject) => 
                setTimeout(() => reject(new Error('TIMEOUT')), 5000)
            );

            const escalationRequest = fetch('/support/live/request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ conversation_id: conversationId })
            }).then(async response => {
                if (response.status === 419 || response.status === 401) {
                    alert('Session expired. Please refresh the page to continue.');
                    window.location.reload();
                    throw new Error('SESSION_EXPIRED');
                }
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Server Error: ${response.status}`);
                }
                return response.json();
            });

            Promise.race([escalationRequest, escalationTimeout])
                .then(data => {
                    updateUIAsWaiting();
                })
                .catch(err => {
                    if (err.message === 'TIMEOUT') {
                        // On timeout, assume the request went through but was slow responding
                        updateUIAsWaiting();
                        console.warn('Escalation server response slow, force-moving to waiting state.');
                    } else if (err.message !== 'SESSION_EXPIRED') {
                        escalateBtn.innerText = 'Request Live Support';
                        escalateBtn.disabled = false;
                        alert('System is busy. Please try again or wait a moment.');
                        console.error('Escalation failed:', err);
                    }
                });
        });

        // Rating Star Logic
        const stars = document.querySelectorAll('.fabi-star');
        stars.forEach(star => {
            star.addEventListener('click', (e) => {
                selectedRating = parseInt(e.target.getAttribute('data-rating'));
                stars.forEach(s => {
                    if (parseInt(s.getAttribute('data-rating')) <= selectedRating) {
                        s.style.color = '#ffd700'; // Gold
                    } else {
                        s.style.color = '#ccc';
                    }
                });
            });
        });

        const submitRatingBtn = document.getElementById('fabi-submit-rating');
        submitRatingBtn.addEventListener('click', () => {
            if (selectedRating === 0) {
                alert('Please select a star rating.');
                return;
            }

            const comment = document.getElementById('fabi-rating-comment').value;

            submitRatingBtn.innerText = 'Submitting...';
            submitRatingBtn.disabled = true;

            fetch('/support/chat/rate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    conversation_id: conversationId,
                    rating: selectedRating,
                    comment: comment
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('fabi-support-rating-view').innerHTML = `
                        <div style="padding:10px;">
                            <span style="font-size:40px; display:block; margin-bottom:10px;">✅</span>
                            <h4 style="font-size:16px; margin-bottom:5px;">Thank You!</h4>
                            <p style="font-size:13px; color:#666;">Your feedback helps us improve MbokoAi.</p>
                        </div>
                    `;
                    setTimeout(() => {
                        resetToContext();
                    }, 3000);
                }
            });
        });
    });
</script>
