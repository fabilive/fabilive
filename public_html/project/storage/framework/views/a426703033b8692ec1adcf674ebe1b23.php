

<?php $__env->startSection('content'); ?>

<div class="content-area">
<div class="mr-breadcrumb">
    <div class="row">
        <div class="col-lg-12">
            <?php if($conv->order_number != null): ?>
            <h4 class="heading"><?php echo e(__('Order Number')); ?>: <?php echo e($conv->order_number); ?></h4>
            <?php endif; ?>
            <h4 class="heading"><?php echo e(__('Conversation with')); ?> <?php echo e($conv->user->name); ?> <a class="add-btn" href="<?php echo e(url()->previous()); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                <ul class="links">
                    <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin-message-index')); ?>"><?php echo e(__('Messages')); ?></a>
                    </li>
                    <li>
                        <a href="javascript:;"><?php echo e(__('Conversations Details')); ?></a>
                    </li>
                </ul>
        </div>
    </div>
</div>

<div class="order-table-wrap support-ticket-wrapper ">
                        <div class="panel panel-primary">
                        <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
                            <div class="panel-body" id="messages">
                                <?php $__currentLoopData = $conv->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($message->user_id != null): ?>
                                <div class="single-reply-area user">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="reply-area">
                                                <div class="left">
                                                    <p><?php echo e($message->message); ?></p>
                                                </div>
                                                <div class="right">
                                            <?php if($message->conversation->user->is_provider == 1): ?>
                                            <img class="img-circle" src="<?php echo e($message->conversation->user->photo != null ? $message->conversation->user->photo : asset('assets/images/noimage.png')); ?>" alt="">
                                            <?php else: ?> 

                                            <img class="img-circle" src="<?php echo e($message->conversation->user->photo != null ? asset('assets/images/users/'.$message->conversation->user->photo) : asset('assets/images/noimage.png')); ?>" alt="">

                                            <?php endif; ?>
                                                    <a target="_blank" class="d-block profile-btn" href="<?php echo e(route('admin-user-show',$message->conversation->user->id)); ?>" class="d-block"><?php echo e(__('View Profile')); ?></a>
                                                    <p class="ticket-date"><?php echo e($message->created_at->diffForHumans()); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <?php else: ?>

                                <div class="single-reply-area admin">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="reply-area">
                                                <div class="left">
                                                    <img class="img-circle" src="<?php echo e(Auth::guard('admin')->user()->photo ? asset('assets/images/admins/'.Auth::guard('admin')->user()->photo ):asset('assets/images/noimage.png')); ?>" alt="">
                                                    <p class="ticket-date"><?php echo e($message->created_at->diffForHumans()); ?></p>
                                                </div>
                                                <div class="right">
                                                    <p><?php echo e($message->message); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <?php endif; ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="panel-footer">
                                <form id="messageform" action="<?php echo e(route('admin-message-store')); ?>" data-href="<?php echo e(route('admin-message-load',$conv->id)); ?>" method="POST">
                                    <?php echo e(csrf_field()); ?>

                                    <div class="form-group">
                                        <input type="hidden" name="conversation_id" value="<?php echo e($conv->id); ?>">
                                        <textarea class="form-control" name="message" id="wrong-invoice" rows="5" required="" placeholder="<?php echo e(__('Message')); ?>"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="mybtn1">
                                            <?php echo e(__('Add Reply')); ?>

                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/message/create.blade.php ENDPATH**/ ?>