
<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Add Distance Fee')); ?></h4>
                <ul class="links">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                    <li><a href="<?php echo e(route('admin-distancefee-index')); ?>"><?php echo e(__('Distance Fee')); ?></a></li>
                    <li><a href="javascript:;"><?php echo e(__('Add New')); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <form action="<?php echo e(route('admin-distancefee-store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div id="weight-container">
    <div class="weight-row d-flex" style="gap: 13px; margin-bottom: 10px;">
        <div class="form-group">
            <label> Distance Start Range</label>
            <input type="number" name="distance_start_range[]" class="form-control" required>
        </div>
        <div class="form-group">
            <label> Distance End Range</label>
            <input type="number" name="distance_end_range[]" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Fee</label>
            <input type="number" name="fee[]" class="form-control" required>
        </div>
        <div class="form-group" style="display:flex;align-items:center;gap:5px;margin-top:25px;">
            <button type="button" class="btn btn-success btn-add">+</button>
            <button type="button" class="btn btn-danger btn-remove" style="display:none;">-</button>
        </div>
    </div>
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
        <button class="add-btn">Add Distance Fee</button>
        <a href="<?php echo e(route('admin-distancefee-index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/distancefee/create.blade.php ENDPATH**/ ?>