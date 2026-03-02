<div class="modal fade" id="vendor_shipping<?php echo e($vendor_id); ?>" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo app('translator')->get('Shipping'); ?></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="packeging-area">
                    <?php $__empty_1 = true; $__currentLoopData = $shipping; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="radio-design">
                        <input type="radio" class="shipping" ref="<?php echo e($vendor_id); ?>"
                            data-price="<?php echo e(round($data->price * $curr->value,2)); ?>"
                            view="<?php echo e($curr->sign); ?><?php echo e(round($data->price * $curr->value,2)); ?>"
                            data-form="<?php echo e($data->title); ?>" id="free-shepping<?php echo e($data->id); ?>"
                            name="shipping[<?php echo e($vendor_id); ?>]" value="<?php echo e($data->id); ?>" <?php echo e(($loop->first) ? 'checked' : ''); ?>>
                        <span class="checkmark"></span>
                        <label for="free-shepping<?php echo e($data->id); ?>">
                            <?php echo e($data->title); ?>

                            <?php if($data->price != 0): ?>
                            + <?php echo e($curr->sign); ?><?php echo e(round($data->price * $curr->value,2)); ?>

                            <?php endif; ?>
                            <small><?php echo e($data->subtitle); ?></small>
                        </label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>
                        <?php echo app('translator')->get('No Shipping Method Available'); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mybtn1" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/includes/vendor_shipping.blade.php ENDPATH**/ ?>