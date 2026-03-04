<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Forget Password')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Forget Password')); ?></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<!--==================== Registration Form Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="row">
         <div class="col">
            <div class="woocommerce">
               <div class="row">
                  <div class="col-lg-6 col-md-8 col-12 mx-auto">
                     <div class="registration-form border">
                        <?php echo $__env->make('includes.admin.form-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <h3><?php echo e(__('Forget Password')); ?></h3>
                        <form id="forgotform" action="<?php echo e(route('user.change.password')); ?>" method="POST">
                           <?php echo e(csrf_field()); ?>

                           <input type="hidden" value="<?php echo e($token); ?>" name="token" >
                           <p>
                              <label for="reg_email"><?php echo e(__('New Password')); ?><span class="required">*</span></label>
                              <input type="text" name="newpass" class="form-control border" placeholder="" id="reg_email"  required="">
                           </p>
                           <p>
                              <label for="reg_emadil"><?php echo e(__('Re-new Password')); ?><span class="required">*</span></label>
                              <input type="text" name="renewpass" class="form-control border" placeholder="" id="reg_emadil"  required="">
                           </p>
                          
                           <p>
                              <input class="authdata" type="hidden" value="<?php echo e(__('Checking...')); ?>">
                              <button type="submit" class="btn btn-primary rounded-0 submit-btn" name="register" value="Register"><?php echo e(__('Submit')); ?></button>
                           </p>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Registration Form Start ====================-->
<?php echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/changepass.blade.php ENDPATH**/ ?>