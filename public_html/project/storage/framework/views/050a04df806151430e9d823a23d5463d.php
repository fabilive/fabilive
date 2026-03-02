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
            <h3 class="mb-2 text-white"><?php echo e(__('Withdraw')); ?>

            </h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Withdraw ')); ?></li>
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
                   <div class="d-flex align-items-center justify-content-between mb-3">

                    <h4 class="widget-title down-line "><?php echo e(__('My Withdraws')); ?>


                    </h4>
                    <a class="mybtn1" href="<?php echo e(route('user-wwt-create')); ?>"> <i class="fas fa-plus"></i> <?php echo e(__('Withdraw Now')); ?></a>
                   </div>
                     <div class="mr-table allproduct mt-4">
                        <div class="table-responsive">
                           <table id="example" class="table" cellspacing="0" width="100%">
                              <thead>
                                 <tr>
                                    <th><?php echo e(__('Withdraw Date')); ?></th>
                                    <th><?php echo e(__('Method')); ?></th>
                                    <th><?php echo e(__('Account')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                 </tr>
                              </thead>
                              <tbody>

                                  <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td><?php echo e(date('d-M-Y',strtotime($withdraw->created_at))); ?></td>
									<td><?php echo e($withdraw->method); ?></td>

									<td>
                                        <?php if($withdraw->method == "Bank"): ?>
                                            <?php echo e($withdraw->iban); ?>

                                        <?php elseif($withdraw->method == "Campay"): ?>
                                            <?php echo e($withdraw->campay_acc_no); ?>

                                        <?php else: ?>
                                            <?php echo e($withdraw->acc_email); ?>

                                        <?php endif; ?>
                                    </td>
									<td><?php echo e($sign->sign); ?><?php echo e(round($withdraw->amount * $sign->value , 2)); ?></td>
									<td><?php echo e(ucfirst($withdraw->status)); ?></td>
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
<?php $__env->startSection('script'); ?>
<script src = "<?php echo e(asset('assets/front/js/dataTables.min.js')); ?>" defer ></script>
<script src = "<?php echo e(asset('assets/front/js/user.js')); ?>" defer ></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/withdraw/index.blade.php ENDPATH**/ ?>