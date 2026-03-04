
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-center text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white"><?php echo e(__('Login Page')); ?></h3>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>

                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Login')); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <!--==================== Login Form Start ====================-->
    <div class="full-row">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="woocommerce">
                        <div class="row">
                            <div class="col-lg-6 col-md-8 col-12 mx-auto">
                                <div class="sign-in-form border">
                                    <h3><?php echo e(__('User Login')); ?></h3>

                                    <?php echo $__env->make('alerts.admin.form-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                    <?php if(Session::has('auth-modal')): ?>
                                        <div class="alert alert-danger alert-dismissible">

                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <?php echo e(Session::get('auth-modal')); ?>

                                        </div>
                                    <?php endif; ?>

                                    <?php if(Session::has('forgot-modal')): ?>
                                        <div class="alert alert-success alert-dismissible">

                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <?php echo e(Session::get('forgot-modal')); ?>

                                        </div>
                                    <?php endif; ?>
                                    <form class="woocommerce-form-login" id="loginform"
                                        action="<?php echo e(route('user.login.submit')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <p>
                                            <label for="username"><?php echo e(__('Email address')); ?><span
                                                    class="required">*</span></label>
                                            <input type="email" class="form-control" name="email" id="username"
                                                placeholder="<?php echo e(__('Type Email Address')); ?>" required="">
                                        </p>
                                        <p>
                                            <label for="password"><?php echo e(__('Password')); ?><span
                                                    class="required">*</span></label>
                                            <input class="form-control" type="password" name="password" id="password"
                                                placeholder="<?php echo e(__('Type Password')); ?>" required="">
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <p>
                                                <a href="<?php echo e(route('user.register')); ?>"
                                                    class="text-secondary"><?php echo e(__("Don't have any account?")); ?></a>
                                            </p>
                                            <p>
                                                <a href="<?php echo e(route('user.forgot')); ?>"
                                                    class="text-secondary"><?php echo e(__('Lost your password?')); ?></a>
                                            </p>

                                        </div>
                                        
                                        <input type="hidden" name="modal" value="1">
                                        <?php if(Session::has('auth-modal')): ?>
                                            <input type="hidden" name="auth_modal" value="1">
                                        <?php endif; ?>
                                        <input id="authdata" type="hidden" value="<?php echo e(__('Authenticating...')); ?>">
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
                                        
                                        
                                        <button type="submit"
                                            class="woocommerce-form-login__submit btn btn-primary border-0 rounded-0 submit-btn float-none w-100"
                                            name="login" value="Log in"><?php echo e(__('Log in')); ?></button>

                                        <?php if($socialsetting->f_check == 1 || $socialsetting->g_check == 1): ?>
                                            <div class="social-area text-center">
                                                <h3 class="title  mt-3"><?php echo e('OR'); ?></h3>
                                                <p class="text"><?php echo e(__('Sign In with social media')); ?></p>
                                                <ul class="social-links">
                                                    <?php if($socialsetting->f_check == 1): ?>
                                                        <li>
                                                            <a href="<?php echo e(route('social-provider', 'facebook')); ?>">
                                                                <i class="fab fa-facebook-f"></i>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if($socialsetting->g_check == 1): ?>
                                                        <li>
                                                            <a href="<?php echo e(route('social-provider', 'google')); ?>">
                                                                <i class="fab fa-google-plus-g"></i>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==================== Login Form Start ====================-->
    <?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/frontend/login.blade.php ENDPATH**/ ?>