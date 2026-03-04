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
                <h3 class="mb-2 text-white"><?php echo e(__('Purchased Items')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Purchased Items')); ?></li>
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
                <div class="row table-responsive-lg mt-3">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-30 widget_categories bg-light account-info table-responsive">
                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Purchased Items')); ?></h4>

                            <table class="table order-table" cellspacing="0" id="example" width="100%">
                                <thead>
                                  <tr>
                                    <th><?php echo e(__('#Order')); ?></th>
                                    <th><?php echo e(__('Date')); ?></th>
                                    <th><?php echo e(__('Order Total')); ?></th>
                                    <th><?php echo e(__('Order Status')); ?></th>
                                    <th><?php echo e(__('View')); ?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                   <?php $__currentLoopData = Auth::user()->orders()->latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <tr>
                                    <td data-label="<?php echo e(__('#Order')); ?>">
                                        <?php echo e($order->order_number); ?>

                                    </td>
                                    <td data-label="<?php echo e(__('Date')); ?>">
                                        <?php echo e(date('d M Y',strtotime($order->created_at))); ?>

                                    </td>
                                    <td data-label="<?php echo e(__('Order Total')); ?>">
                                        <?php echo e(\PriceHelper::showAdminCurrencyPrice(($order->pay_amount  * $order->currency_value),$order->currency_sign)); ?>

                                    </td>
                                    <td data-label="<?php echo e(__('Order Status')); ?>">
                                      <div class="order-status <?php echo e($order->status); ?>">
                                          <?php echo e(ucwords($order->status)); ?>

                                      </div>
                                    </td>


                                    <td data-label="<?php echo e(__('View')); ?>">
                                      <a class="mybtn1 sm1 mb-1" href="<?php echo e(route('user-order',$order->id)); ?>">
                                        <?php echo e(__('View Order')); ?>

                                      </a>

                                      
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
<!--==================== Blog Section End ====================-->

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src = "<?php echo e(asset('assets/front/js/dataTables.min.js')); ?>" defer ></script>
<script src = "<?php echo e(asset('assets/front/js/user.js')); ?>" defer ></script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/order/index.blade.php ENDPATH**/ ?>