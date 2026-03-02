<?php if(isset($order)): ?>
<div class="tracking-steps-area">

        <ul class="tracking-steps">
            <?php $__currentLoopData = $order->tracks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $track): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="<?php echo e(in_array($track->title, $datas) ? 'active' : ''); ?>">
                    <div class="icon"><?php echo e($loop->index + 1); ?></div>
                    <div class="content">
                            <h4 class="title"><?php echo e(ucwords($track->title)); ?></h4>
                            <p class="date"><?php echo e(date('d m Y',strtotime($track->created_at))); ?></p>
                            <p class="details"><?php echo e($track->text); ?></p>
                    </div>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>
</div>


    <?php else: ?>
    <h3 class="text-center"><?php echo e(__('No Order Found.')); ?></h3>
    <?php endif; ?>          
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/load/track-load.blade.php ENDPATH**/ ?>