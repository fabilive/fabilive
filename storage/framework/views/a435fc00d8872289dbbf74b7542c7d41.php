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
            <h3 class="mb-2 text-white"><?php echo e(__('Transactions')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Transactions')); ?></li>
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
                  <div class="widget border-0  widget_categories bg-light account-info p-4">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Transactions')); ?>

                     </h4>
                     <div class="mr-table allproduct mt-4">
                        <div class="table-responsive">
                           <table id="example" class="table">
                              <thead>
                                 <tr>
                                    <th><?php echo e(__('Transaction ID')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Transaction Date')); ?></th>
                                    <th><?php echo e(__('Details')); ?></th>
                                    <th><?php echo e(__('View')); ?></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php $__currentLoopData = Auth::user()->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <tr>
                                    <td data-label="<?php echo e(__('Transaction ID')); ?>">
                                       <div>
                                          <?php echo e($data->txn_number == null ? $data->txnid : $data->txn_number); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Amount')); ?>">
                                       <div>
                                          <?php echo e($data->type == 'plus' ? '+' : '-'); ?> <?php echo e(\PriceHelper::showOrderCurrencyPrice(($data->amount * $data->currency_value),$data->currency_sign)); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Transaction Date')); ?>">
                                       <div>
                                          <?php echo e(date('d-M-Y',strtotime($data->created_at))); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Details')); ?>">
                                       <div>
                                          <?php echo e($data->details); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('View')); ?>">
                                       <div>
                                          <a href="javascript:;" data-href="<?php echo e(route('user-trans-show',$data->id)); ?>" data-toggle="modal" data-target="#trans-modal" class="txn-show mybtn1 sm">
                                          <?php echo e(__('View')); ?>

                                          </a>
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
<!-- Order Tracking modal Start-->
<div class="modal fade" id="trans-modal" tabindex="-1" role="dialog" aria-labelledby="trans-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header p-3">
            <h4 class="modal-title">
               <b>
               <?php echo e(__('Transaction Details')); ?>

               </b>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="trans">
         </div>
      </div>
   </div>
</div>
<!-- Order Tracking modal End -->
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src = "<?php echo e(asset('assets/front/js/dataTables.min.js')); ?>" defer ></script>
<script src = "<?php echo e(asset('assets/front/js/user.js')); ?>" defer ></script>
<script type="text/javascript">
   (function($) {
   		"use strict";

       $('.txn-show').on('click',function(e){
           var url = $(this).data('href');
           $('#trans').load(url);
           $('#trans-modal').modal('show');
       });
       $('.close').on('click',function(e){
           $('#trans-modal').modal('hide');
       })

   })(jQuery);

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/transactions.blade.php ENDPATH**/ ?>