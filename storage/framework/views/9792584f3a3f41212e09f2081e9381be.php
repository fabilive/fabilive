
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
    style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Service Area')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('rider-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Service Area')); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->
<!--==================== Blog Section Start ====================-->
<div class="full-row">
    <div class="container">
        <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-xl-3">
                <?php echo $__env->make('partials.rider.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="col-xl-9">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-30 widget_categories bg-light account-info table-responsive">
                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Service Area')); ?></h4>
                            <div class="my-1">
                                    <a href="<?php echo e(route('rider-service-area-create')); ?>" class="mybtn1">
                                        <?php echo app('translator')->get('Add Service Area'); ?>
                                    </a>
                            </div>
                            <table class="table order-table" cellspacing="0" id="example" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('Service Area')); ?></th>
                                        <th><?php echo e(__('Action')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $service_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td data-label="<?php echo e(__('#Service Area')); ?>">
                                                <?php echo e(optional($area->serviceArea)->location ?? 'N/A'); ?>

                                            </td>
                                            <td data-label="<?php echo e(__('Action')); ?>">
                                                <a class="mybtn1 sm1" href="<?php echo e(route('rider-service-area-edit',$area->id)); ?>">
                                                    <?php echo e(__('Edit')); ?>

                                                </a>
                                                <a class="mybtn1 sm1" href="<?php echo e(route('rider-service-area-delete',$area->id)); ?>">
                                                    <?php echo e(__('Delete')); ?>

                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center"><?php echo e(__('No Orders Found.')); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function() {
    $('.service_area').select2({
        placeholder: "Select Service Area",
        allowClear: true
    });


});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/rider/service-area.blade.php ENDPATH**/ ?>