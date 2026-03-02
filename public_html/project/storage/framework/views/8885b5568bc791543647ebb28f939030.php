 
<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Edit Delivery Fee')); ?></h4>
                <ul class="links">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                    <li><a href="<?php echo e(route('admin-deliveryfee-index')); ?>"><?php echo e(__('Delivery Fee')); ?></a></li>
                    <li><a href="javascript:;"><?php echo e(__('Edit')); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <form action="<?php echo e(route('admin-deliveryfee-update', $id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <div id="weight-container">
            <?php $__currentLoopData = $deliveryFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $deliveryFee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="weight-row d-flex" style="gap:15px; margin-bottom:10px;">
                <div class="form-group">
                    <label>Weight Unit</label>
                    <select name="weight[]" class="form-control" required>
                        <option value="">-- Select Weight Unit --</option>
                        <option value="Kg" <?php echo e($deliveryFee->weight == 'Kg' ? 'selected' : ''); ?>>Kg</option>
                        <option value="Gram" <?php echo e($deliveryFee->weight == 'Gram' ? 'selected' : ''); ?>>Gram</option>
                        <option value="Ton" <?php echo e($deliveryFee->weight == 'Ton' ? 'selected' : ''); ?>>Ton</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Start Range</label>
                    <input type="number" name="start_range[]" class="form-control" value="<?php echo e($deliveryFee->start_range); ?>" required>
                </div>
                <div class="form-group">
                    <label>End Range</label>
                    <input type="number" name="end_range[]" class="form-control" value="<?php echo e($deliveryFee->end_range); ?>" required>
                </div>
                <div class="form-group">
                    <label>Fee</label>
                    <input type="number" name="fee[]" class="form-control" value="<?php echo e($deliveryFee->fee); ?>" required>
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:5px;margin-top:25px;">
                    <button type="button" class="btn btn-success btn-add">+</button>
                    <button type="button" class="btn btn-danger btn-remove" style="<?php echo e($key == 0 ? 'display:none;' : ''); ?>">-</button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <script>
        $(document).ready(function () {
            $(document).on("click", ".btn-add", function () {
                let clone = $(this).closest(".weight-row").clone();
                clone.find("input").val("");
                clone.find("select").val("");
                clone.find(".btn-remove").show();
                $("#weight-container").append(clone);
            });
            $(document).on("click", ".btn-remove", function () {
                $(this).closest(".weight-row").remove();
            });
        });
        </script>
        <button class="btn btn-primary">Update Delivery Fee</button>
        <a href="<?php echo e(route('admin-deliveryfee-index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/deliveryfee/edit.blade.php ENDPATH**/ ?>