<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/datatables.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-center text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white"><?php echo e(__('Chat With Buyer')); ?></h3>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo e(route('rider-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo e(__('Chat With Buyer')); ?></li>
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
                <div class="col-xl-3">
                    <?php echo $__env->make('partials.rider.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <div class="col-xl-9">
                    <div class="row g-0 border rounded shadow-sm" style="height:600px;">

                        <!-- CHAT LIST -->
                        <div class="col-xl-3 col-lg-4 border-end bg-light overflow-auto">
                            <div class="p-3 border-bottom">
                                <h5 class="mb-0"><?php echo e(__('Chats')); ?></h5>
                            </div>

                            <ul class="list-group list-group-flush" id="chat-list">
                                <?php $__empty_1 = true; $__currentLoopData = $chats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <li class="list-group-item list-group-item-action chat-item
                                           d-flex align-items-center justify-content-between"
                                        data-chat-id="<?php echo e($chat->id); ?>" data-product-name="<?php echo e(implode(', ', $chat->product_names ?? [])); ?>" style="cursor: pointer">

                                        <strong class="p-1"><?php echo e($chat->buyer->name ?? 'Unknown Buyer'); ?></strong>

                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <li class="list-group-item text-center text-muted">
                                        No conversations found
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- CHAT AREA -->
                        <div class="col-xl-9 col-lg-8 d-flex flex-column bg-white">

                            <div class="p-3 border-bottom">
                                <h6 class="mb-0" id="chat-header">
                                    <?php echo e(__('Select a chat')); ?>

                                </h6>
                            </div>

                            <!-- MESSAGES -->
                            <div class="flex-grow-1 overflow-auto p-3" id="chat-messages"
                            style="background:#f9f9f9; font-size:0.9rem; height:500px; max-height:500px; overflow-y:auto;">
                           <p class="text-muted text-center mt-5">
                               Select a chat to view messages
                           </p>
                       </div>


                            <!-- INPUT -->
                            <form id="chat-form" class="d-flex p-3  border-top bg-light">
                                <input type="text" class="form-control me-2 rounded-pill" id="chat-input"
                                    placeholder="Type a message...">
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <?php echo e(__('Send')); ?>

                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script>
        const riderId = <?php echo e(auth()->guard('rider')->id()); ?>;
        let isChatOpen = true; // chat page open

        const pusher = new Pusher("<?php echo e(env('PUSHER_APP_KEY')); ?>", {
            cluster: "mt1",
            forceTLS: true
        });

        console.log('📡 Subscribing to:', 'admin-chat.' + riderId);

        const channel = pusher.subscribe('admin-chat.' + riderId);

        channel.bind('UserMessageSent', function (e) {
            console.log('🔥 UserMessageSent received:', e);

            // ❌ No chat selected
            if (!currentChatId) return;

            if (parseInt(e.chat_id) !== parseInt(currentChatId)) return;

            if (e.sender_id == riderId) return;

            // ✅ Append buyer message
            chatBox.innerHTML += `
                <div class="d-flex mb-2">
                    <div class="p-2 rounded bg-light border" style="max-width:70%">
                        ${e.message}
                    </div>
                </div>
            `;

            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>


    <?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/front/js/dataTables.min.js')); ?>" defer></script>
    <script src="<?php echo e(asset('assets/front/js/user.js')); ?>" defer></script>

    <script>
        let currentChatId = null;

        const chatItems = document.querySelectorAll('.chat-item');
        const chatHeader = document.getElementById('chat-header');
        const chatBox = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');

        // CLICK CHAT → FETCH MESSAGES
        chatItems.forEach(item => {
            item.addEventListener('click', function() {

                chatItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');

                currentChatId = this.dataset.chatId;
                const productName = this.dataset.productName || 'N/A';
chatHeader.innerText = `Product: ${productName}`;


                fetch("<?php echo e(route('rider-chat-messages')); ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                        },
                        body: JSON.stringify({
                            chat_id: currentChatId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.status) return;
                        renderMessages(data.messages);
                    });
            });
        });

        // RENDER MESSAGES
        function renderMessages(messages) {
            chatBox.innerHTML = '';

            if (!messages.length) {
                chatBox.innerHTML = `<p class="text-muted text-center mt-5">No messages yet</p>`;
                return;
            }

            messages.forEach(msg => {
                const isRider = msg.sender_id == <?php echo e(auth()->guard('rider')->id()); ?>;
                const align = isRider ? 'justify-content-end' : '';
                const bg = isRider ? 'bg-primary text-white' : 'bg-light border';

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

        // SEND MESSAGE (API + UI)
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentChatId) {
                alert('Please select a chat first');
                return;
            }

            const msg = chatInput.value.trim();
            if (!msg) return;

            fetch("<?php echo e(route('rider-chat-send')); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                },
                body: JSON.stringify({
                    chat_id: currentChatId,
                    message: msg
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.status) {
                    alert('Message failed to send');
                    return;
                }

                // Append message to UI
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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/rider/deliverymessage/index.blade.php ENDPATH**/ ?>