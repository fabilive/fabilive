<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Contact')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Contact')); ?></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<!--==================== Contact Section Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="row">
         <div class="col-lg-7 col-md-7">
            <h3 class="down-line mb-5"><?php echo e(__('Send Message')); ?></h3>
            <div class="form-simple mb-5">
               <form class="contactform"  id="contact-form" action="#" method="POST">
                  <?php echo csrf_field(); ?>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label><?php echo e(__('Full Name')); ?>:</label>
                           <input type="text" class="form-control bg-gray" name="name" placeholder="<?php echo e(__('Name *')); ?>" required="">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label><?php echo e(__('Your Email')); ?>:</label>
                           <input type="email" class="form-control bg-gray" name="email" placeholder="<?php echo e(__('Email Address *')); ?>" required="">
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label><?php echo e(__('Phone Number')); ?>:</label>
                           <input type="text" class="form-control bg-gray" name="phone" placeholder="<?php echo e(__('Phone Number *')); ?>" required="">
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="mb-3">
                           <label><?php echo e(__('Message')); ?>:</label>
                           <textarea class="form-control bg-gray" name="text" rows="8" placeholder="<?php echo e(__('Your Message *')); ?>" required=""></textarea>
                        </div>
                     </div>

                     <?php if($gs->is_capcha == 1): ?>
                     <div class="form-input">
                        <?php echo NoCaptcha::display(); ?>

                        <?php echo NoCaptcha::renderJs(); ?>

                        <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="my-2"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                     </div>
                     <?php endif; ?>
                     <input type="hidden" name="to" value="<?php echo e($ps->contact_email); ?>">
                     <div class="col-md-12 mt-3">
                        <button class="btn btn-primary submit-btn mybtn1" name="submit" type="submit"><?php echo e(__('Send Message')); ?></button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div class="col-lg-5 col-md-5">
            <h3 class="down-line mb-5"><?php echo e(__('Get In Touch')); ?></h3>
            <div class="d-flex mb-3">
               <ul>
                  <?php if($ps->street != null): ?>
                  <li class="mb-3">
                     <strong><?php echo e(__('Office Address')); ?> :</strong><br> <?php echo e($ps->street); ?>

                  </li>
                  <?php endif; ?>
                  <?php if($ps->phone != null ): ?>
                  <li class="mb-3">
                     <strong>Contact Number :</strong><br> <?php echo e($ps->phone); ?>

                  </li>
                  <?php endif; ?>
                  <?php if($ps->fax != null ): ?>
                  <li class="mb-3">
                     <strong>Fax :</strong><br> <?php echo e($ps->fax); ?>

                  </li>
                  <?php endif; ?>
                  <?php if($ps->email != null): ?>
                  <li class="mb-3">
                     <strong><?php echo e(__('Email Address')); ?> :</strong><br>
                     <p class="email"><?php echo e($ps->email); ?></p>
                  </li>
                  <?php endif; ?>
               </ul>
            </div>

         </div>
      </div>
   </div>
</div>
<!--======================== Contact Section End ==========================-->







<?php echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Shilley Pc\fabilive\resources\views/frontend/contact.blade.php ENDPATH**/ ?>