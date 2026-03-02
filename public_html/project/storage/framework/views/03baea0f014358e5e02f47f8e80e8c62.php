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
            <h3 class="mb-2 text-white"><?php echo e(__('Favorite Sellers')); ?>

            </h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Favorite Sellers')); ?></li>
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
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Favorite Sellers')); ?>

                     </h4>
                     <div class="mr-table allproduct message-area  mt-4">
                        <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="table-responsive">
                           <table id="example" class="table" cellspacing="0" width="100%">
                              <thead>
                                 <tr>
                                    <th><?php echo e(__('Shop Name')); ?></th>
                                    <th><?php echo e(__('Owner Name')); ?></th>
                                    <th><?php echo e(__('Address')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <?php
                                 $seller = App\Models\User::findOrFail($vendor->vendor_id);
                                 ?>
                                 <tr class="conv">
                                    <td data-label="<?php echo e(__('Shop Name')); ?>">
                                       <div>
                                          <?php echo e($seller->shop_name); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Owner Name')); ?>">
                                       <div>
                                          <?php echo e($seller->owner_name); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Address')); ?>">
                                       <div>
                                          <?php echo e($seller->shop_address); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Actions')); ?>">
                                       <div>
                                          <a target="_blank" href="<?php echo e(route('front.vendor',str_replace(' ', '-',($seller->shop_name)))); ?>" class="link view mybtn1"><i class="fa fa-eye"></i></a>

                                          <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="<?php echo e(route('user-favorite-delete',$vendor->id)); ?>" class="link remove mybtn1 "><i class="fa fa-trash"></i></a>
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
            <h4 class="modal-title d-inline-block"><?php echo e(__('Confirm Delete ?')); ?></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p class="text-center"><?php echo e(__('You are about to delete this Seller.')); ?></p>
            <p class="text-center"><?php echo e(__('Do you want to proceed?')); ?></p>
         </div>
         <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <a class="btn btn-danger btn-ok"><?php echo e(__('Delete')); ?></a>
         </div>
      </div>
   </div>
</div>
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
   (function($) {
           "use strict";

         $('#confirm-delete').on('show.bs.modal', function(e) {
             $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
         });

   })(jQuery);

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/favorite.blade.php ENDPATH**/ ?>