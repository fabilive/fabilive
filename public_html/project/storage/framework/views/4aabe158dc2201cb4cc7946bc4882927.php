<?php $__currentLoopData = $conv->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if($message->sent_user != null): ?>

<div class="single-reply-area admin">
    <div class="row">
        <div class="col-lg-12">
            <div class="reply-area">
                <div class="left">
                    <?php if($message->conversation->sent->is_provider == 1 ): ?>
                    <img class="img-circle" src="<?php echo e($message->conversation->sent->photo != null ? $message->conversation->sent->photo : asset('assets/images/noimage.png')); ?>" alt="">
                    <?php else: ?>
                    <img class="img-circle" src="<?php echo e($message->conversation->sent->photo != null ? asset('assets/images/users/'.$message->conversation->sent->photo) : asset('assets/images/noimage.png')); ?>" alt="">
                    <?php endif; ?>
                    <p class="ticket-date"><?php echo e($message->conversation->sent->name); ?></p>
                </div>
                <div class="right">
                    <p><?php echo e($message->message); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>


<br>
<?php else: ?>

<div class="single-reply-area user">
    <div class="row">
        <div class="col-lg-12">
            <div class="reply-area">
                <div class="left">
                    <p><?php echo e($message->message); ?></p>
                </div>
                <div class="right">
                    <?php if($message->conversation->recieved->is_provider == 1 ): ?>
                    <img class="img-circle" src="<?php echo e($message->conversation->recieved->photo != null ? $message->conversation->recieved->photo : asset('assets/images/noimage.png')); ?>" alt="">
                    <?php else: ?>
                    <img class="img-circle" src="<?php echo e($message->conversation->recieved->photo != null ? asset('assets/images/users/'.$message->conversation->recieved->photo) : asset('assets/images/noimage.png')); ?>" alt="">
                    <?php endif; ?>
                    <p class="ticket-date"><?php echo e($message->conversation->recieved->name); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>



<br>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/load/usermsg.blade.php ENDPATH**/ ?>