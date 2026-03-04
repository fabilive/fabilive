<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Affiliate History ')); ?>


                </h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Affiliate History  ')); ?></li>
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
            <div class="col-xl-4">
                <?php echo $__env->make('partials.user.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-40 widget_categories bg-light account-info">

                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Affiliate History')); ?>

                                <a class="mybtn1" href="<?php echo e(route('user-affilate-program')); ?>">
                                    <i class="fas fa-arrow-left"></i>
                                    <?php echo e(__('Back')); ?>

                                </a>
                            </h4>

                            <div class="mr-table allproduct mt-4">
                                <div class="table-responsive">
                                        <table id="example" class="table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?php echo e(__('Customer Name')); ?></th>
                                                    <th><?php echo e(__('Product')); ?></th>
                                                    <th><?php echo e(__('Affiliate Bonus')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $final_affilate_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fuser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td data-label="<?php echo e(__('Customer Name')); ?>">
                                                        <div>
                                                            <?php echo e($fuser['customer_name']); ?>

                                                        </div>
                                                    </td>
                                                    <td data-label="<?php echo e(__('Product')); ?>">
                                                        <div>
                                                            <?php
                                                            $product = \App\Models\Product::find($fuser['product_id']);
                                                            ?>
                                                            <a href="<?php echo e(route('front.product', $product->slug)); ?>" target="_blank"><?php echo e($product->name); ?></a>
                                                        </div>
                                                    </td>
                                                    <td data-label="<?php echo e(__('Affiliate Bonus')); ?>">
                                                        <div>
                                                            <?php echo e($fuser['charge']); ?>

                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--==================== Blog Section End ====================-->

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/affilate/affilate-history.blade.php ENDPATH**/ ?>