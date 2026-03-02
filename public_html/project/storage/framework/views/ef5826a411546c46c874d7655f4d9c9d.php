<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/datatables.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Deposite')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Deposite')); ?></li>
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

                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Deposits')); ?>

                                <a class="mybtn1" href="<?php echo e(route('user-deposit-create')); ?>"> <i class="fas fa-plus"></i> <?php echo e(__('Add Deposit')); ?></a>
                            </h4>

                            <div class="mr-table allproduct mt-4">
                                <div class="table-responsive">
                                        <table id="example" class="table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?php echo e(__('Deposit Date')); ?></th>
                                                    <th><?php echo e(__('Method')); ?></th>
                                                    <th><?php echo e(__('Amount')); ?></th>
                                                    <th><?php echo e(__('Status')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = Auth::user()->deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td data-label="<?php echo e(__('Deposit Date')); ?>">
                                                            <div>
                                                                <div>
                                                                    <?php echo e(date('d-M-Y',strtotime($data->created_at))); ?>

                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td data-label="<?php echo e(__('Method')); ?>">
                                                            <div>
                                                                <div>
                                                                    <?php echo e($data->method); ?>

                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td data-label="<?php echo e(__('Amount')); ?>">
                                                            <div>
                                                                <div>
                                                                    <?php echo e(\PriceHelper::showOrderCurrencyPrice(($data->amount * $data->currency_value),$data->currency_code)); ?>

                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td data-label="<?php echo e(__('Status')); ?>">
                                                            <div>
                                                                <span class="badge <?php echo e($data->status == 1 ? ' bg-success': 'bg-primary'); ?>">
                                                                    <?php echo e($data->status == 1 ? 'Completed' : 'Pending'); ?>

                                                                </span>
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


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block"><?php echo e(__('License Key')); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-center"><?php echo e(__('The Licenes Key is :')); ?> <span id="key"></span></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
            </div>
        </div>
    </div>
</div>

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
<script src = "<?php echo e(asset('assets/front/js/dataTables.min.js')); ?>" defer ></script>
<script src = "<?php echo e(asset('assets/front/js/user.js')); ?>" defer ></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/deposit/index.blade.php ENDPATH**/ ?>