<?php $__env->startSection('content'); ?>

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <?php echo e(__('Edit Agreement')); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-agreement-index')); ?>">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?>

                    </a>
                </h4>
                <ul class="links">
                    <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin-agreement-index')); ?>"><?php echo e(__('Manage Agreement')); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('admin-agreement-edit', $data->id)); ?>"><?php echo e(__('Edit Agreement')); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                        <form id="geniusform" action="<?php echo e(route('admin-agreement-update', $data->id)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <!-- Type Dropdown -->
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="" disabled>Select type</option>

                                    <option value="Fabilive_Delivery_Individual_Agreement"
                                    <?php echo e($data->type == 'Fabilive_Delivery_Individual_Agreement' ? 'selected' : ''); ?>>
                                    Fabilive Delivery Individual Agreement
                                </option>

                                <option value="Fabilive_Delivery_Company_Agreement"
                                    <?php echo e($data->type == 'Fabilive_Delivery_Company_Agreement' ? 'selected' : ''); ?>>
                                    Fabilive Delivery Company Agreement
                                </option>

                                <option value="Fabilive_Sub_merchant_Agreement"
                                    <?php echo e($data->type == 'Fabilive_Sub_merchant_Agreement' ? 'selected' : ''); ?>>
                                    Fabilive Sub merchant Agreement
                                </option>

                                <option value="Selfi_Instructions"
                                    <?php echo e($data->type == 'Selfi_Instructions' ? 'selected' : ''); ?>>
                                    Selfi Instructions
                                </option>

                                </select>
                            </div>

                            <!-- Current PDF -->
                            <?php if($data->image): ?>
                            <div class="mb-3">
                                <label class="form-label">Current PDF</label>
                                <div>
                                    <a href="<?php echo e(asset($data->image)); ?>" target="_blank">View PDF</a>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Upload New PDF -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload New Agreement (PDF only)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="application/pdf">
                                <small class="text-muted">Leave blank if you do not want to change the PDF.</small>
                            </div>

                            <!-- Centered Submit Button -->
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-dark rounded-2">Update Agreement</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/agreement/edit.blade.php ENDPATH**/ ?>