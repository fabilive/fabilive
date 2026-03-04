
<?php $__env->startSection('content'); ?>
<input type="hidden" id="headerdata" value="<?php echo e(__('Delivery Fee')); ?>">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Delivery Fee Management')); ?></h4>
                <ul class="links">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                    <li><a href="javascript:;"><?php echo e(__('Delivery Fee')); ?></a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <!-- Add Button -->
    <div class="text-right mb-3">
        <a href="<?php echo e(route('admin-deliveryfee-create')); ?>" class="add-btn">
            <i class="fas fa-plus"></i> Add Delivery Fee
        </a>
    </div>

    <!-- Table -->
    <div class="product-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Weight</th>
                        <th>Start Range</th>
                        <th>End Range</th>
                        <th>Fee</th>
                        <th width="180px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $deliveryFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($fee->weight); ?></td>
                            <td><?php echo e($fee->start_range); ?></td>
                            <td><?php echo e($fee->end_range); ?></td>
                            <td><?php echo e($fee->fee); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin-deliveryfee-edit', $fee->id)); ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="<?php echo e(route('admin-deliveryfee-delete', $fee->id)); ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center">No Delivery Fees Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/deliveryfee/index.blade.php ENDPATH**/ ?>