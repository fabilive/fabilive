

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <h3>Customer Messages</h3>
    <ul class="list-group">
        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $unreadCount = \App\Models\LiveMessage::where('sender_id', $customer->id)
                                ->where('receiver_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
            ?>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="<?php echo e(route('vendor.chat', $customer->id)); ?>" class="text-decoration-none">
                    <img src="<?php echo e(asset('assets/images/noimage.png')); ?>" alt="Profile" class="rounded-circle" width="40">
                    <?php echo e($customer->name); ?>

                </a>
                <?php if($unreadCount > 0): ?>
                    <span class="badge bg-danger text-white"><?php echo e($unreadCount); ?></span>
                <?php endif; ?>
                
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/messages.blade.php ENDPATH**/ ?>