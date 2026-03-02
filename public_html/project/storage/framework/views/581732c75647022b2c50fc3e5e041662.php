<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Order Tracking')); ?>

            </h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Order Tracking')); ?></li>
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
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Get Tracking Code')); ?>

                     </h4>
                     <div class="order-tracking-content py-30">
                        <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <form class="tracking-form" method="GET">
                           <div class="new d-flex">
                              <input type="text" class="form-control border w-75 mr-2 rounded-pill " id="code" placeholder="<?php echo e(__('Get Tracking Code')); ?>" required="">
                              <button type="submit" id="t-form"  class="mybtn1"><?php echo e(__('View Tracking')); ?></button>
                              <a href="#"  data-bs-toggle="modal" data-bs-target="#order-tracking-modal"></a>
                           </div>
                        </form>
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
<div class="modal fade" id="order-tracking-modal" role="dialog"  data-bs-backdrop="static" data-bs-keyboard="false"  aria-labelledby="order-tracking-modal" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content text-center">
         <div class="modal-header">
            <h5 class="modal-title pt-3 pl-3 mx-auto"> <b><?php echo e(__('Order Tracking')); ?></b> </h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="order-track">
         </div>
      </div>
   </div>
</div>
<!-- Order Tracking modal End -->
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
   $(document).on('click','form #t-form',function(e){
   e.preventDefault();


         var code = $('#code').val();
         $('#order-track').load('<?php echo e(url("user/order/trackings/")); ?>/'+code);
         $('#order-tracking-modal').modal('show');
   })

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/order-track.blade.php ENDPATH**/ ?>