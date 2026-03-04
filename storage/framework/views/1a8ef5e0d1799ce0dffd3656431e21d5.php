<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Register')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Register')); ?></li>
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
                                        <h3><?php echo e(__('Signup Now')); ?></h3>
                                        <form id="registerform" action="<?php echo e(route('user-register-submit')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <p>
                                                <input type="text" name="name" class="form-control" placeholder="<?php echo e(__('Full Name')); ?>"  >
                                            </p>
                                            <p>
                                                <input type="email" name="email" class="form-control" required=""  placeholder="<?php echo e(__('Email Address')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="phone" class="form-control" required=""  placeholder="<?php echo e(__('Phone Number')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="address" class="form-control" required=""  placeholder="<?php echo e(__('Address')); ?>" >
                                            </p>
                                            <p>
                                                <input type="password" name="password" class="form-control" required=""  placeholder="<?php echo e(__('Password')); ?>" >
                                            </p>
                                            <p>
                                                <input type="password" name="password_confirmation" class="form-control" required=""  placeholder="<?php echo e(__('Confirm Password')); ?>" >
                                            </p>
                                            <?php if($gs->is_capcha == 1): ?>
                                            <div class="form-input mb-3">
                                                 <div data-sitekey="6LcQrmIrAAAAABNWTdrJbBzy9TfhBzBH4P4IQIIT" class="g-recaptcha"></div>
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
                                            <input id="processdata" type="hidden" value="<?php echo e(__('Processing...')); ?>">
                                                <button class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register" value="Register"><?php echo e(__('Register')); ?></button>
                                            </p>
                                        </form>
                                        <p>
                                                <?php echo e(__("Do have any account?")); ?><a href="<?php echo e(route('user.login')); ?>"  class="text-secondary"><?php echo e(__(' Login')); ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    let isSubmitting = false;
    $(document).ready(function() {
        $("#registerform").off('submit').on('submit', function(e){
            e.preventDefault();
            if (isSubmitting) return;
            isSubmitting = true;
            $('.submit-btn').html('Processing...');
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response){
                    alert('Registration Successful! Please login.');
                    window.location.href = '<?php echo e(route("user.login")); ?>';
                },
                error: function(xhr){
                    $('.submit-btn').html('Register');
                    alert('Registration failed. Please try again.');
                    isSubmitting = false;
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/frontend/register.blade.php ENDPATH**/ ?>