<?php if($order->status == 'pending'): ?>

                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title"><?php echo e(__('Order Placed')); ?></div>
                                            </li>
                                            <li class="">
                                                <div class="icon">2</div>
                                                <div class="title"><?php echo e(__('On Review')); ?></div>
                                            </li>
                                            <li class="">
                                                <div class="icon">3</div>
                                                <div class="title"><?php echo e(__('On Delivery')); ?></div>
                                            </li>
                                            <li class="">
                                                <div class="icon">4</div>
                                                <div class="title"><?php echo e(__('Delivered')); ?></div>
                                            </li>
                                    </ul>

<?php elseif($order->status == 'processing'): ?>

                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title"><?php echo e(__('Order Placed')); ?></div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">2</div>
                                                <div class="title"><?php echo e(__('On Review')); ?></div>
                                            </li>
                                            <li class="">
                                                <div class="icon">3</div>
                                                <div class="title"><?php echo e(__('On Delivery')); ?></div>
                                            </li>
                                            <li class="">
                                                <div class="icon">4</div>
                                                <div class="title"><?php echo e(__('Delivered')); ?></div>
                                            </li>
                                    </ul>


<?php elseif($order->status == 'on delivery'): ?>


                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title"><?php echo e(__('Order Placed')); ?></div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">2</div>
                                                <div class="title"><?php echo e(__('On Review')); ?></div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">3</div>
                                                <div class="title"><?php echo e(__('On Delivery')); ?></div>
                                            </li>
                                            <li class="">
                                                <div class="icon">4</div>
                                                <div class="title"><?php echo e(__('Delivered')); ?></div>
                                            </li>
                                    </ul>

<?php elseif($order->status == 'completed'): ?>

                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title"><?php echo e(__('Order Placed')); ?></div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">2</div>
                                                <div class="title"><?php echo e(__('On Review')); ?></div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">3</div>
                                                <div class="title"><?php echo e(__('On Delivery')); ?></div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">4</div>
                                                <div class="title"><?php echo e(__('Delivered')); ?></div>
                                            </li>
                                    </ul>

<?php endif; ?>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/user/order-process.blade.php ENDPATH**/ ?>