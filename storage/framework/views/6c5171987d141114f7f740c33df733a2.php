<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Reward')); ?>

                </h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Reward ')); ?></li>
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
                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Affiliate Program')); ?>

                                <a class="mybtn1" href="<?php echo e(route('user-affilate-history')); ?>"> <i class="fas fa-history"></i> <?php echo e(__('Referral History')); ?></a>
                            </h4>
                            <div class="edit-info-area">
                                <div class="body">
                                        <div class="edit-info-area-form">
                                                <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                                                <form>
                                                    <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                    <div class="row mb-4">
                                                        <div class="col-lg-4 text-right pt-2 f-14">
                                                            <label><?php echo e(__('Your Affilate Link *')); ?> <a id="affilate_click" data-toggle="tooltip" data-placement="top" title="Copy"  href="javascript:;" class="mybtn1 copy border"><i class="fas fa-copy"></i></a></label>
                                                            <br>
                                                            <small><?php echo e(__('This is your affilate link just copy the link and paste anywhere you want.')); ?></small>
                                                        </div>
                                                        <div class="col-lg-8 pt-2">
                                                             <textarea id="affilate_address" class="input-field affilate form-control border h--150" name="address" readonly="" row="5"><?php echo e(url('/').'/?reff='.$user->affilate_code); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row pb-5">
                                                        <div class="col-lg-4 text-right pt-2 f-14">
                                                            <label><?php echo e(__('Referral Banner *')); ?></label>
                                                            <br>
                                                            <small><?php echo e(__('This is your affilate banner Preview.')); ?></small>
                                                        </div>
                                                        <div class="col-lg-8 pt-2 pl-5">
                                                             <a href="<?php echo e(url('/').'/?reff='.$user->affilate_code); ?>" target="_blank"><img src="<?php echo e(asset('assets/images/'.$gs->affilate_banner)); ?>"></a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 text-right pt-2 f-14">
                                                            <label><?php echo e(__('Referral Banner HTML Code *')); ?> <a id="affilate_html_click" data-toggle="tooltip" data-placement="top" title="Copy"  href="javascript:;" class="mybtn1 copy border px-3"><i class="fas fa-copy"></i></a></label>
                                                            <br>
                                                            <small><?php echo e(__('This is your affilate banner html code just copy the code and paste anywhere you want.')); ?></small>
                                                        </div>
                                                        <div class="col-lg-8 pt-2">
                                                             <textarea id="affilate_html" class="input-field affilate from-control border w-100 p-4 h--150" name="address" readonly=""><a href="<?php echo e(url('/').'/?reff='.$user->affilate_code); ?>" target="_blank"><img src="<?php echo e(asset('assets/images/'.$gs->affilate_banner)); ?>"></a></textarea>
                                                        </div>
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
    </div>
</div>
<!--==================== Blog Section End ====================-->

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
(function($) {
		"use strict";
    $('#affilate_click').on('click',function(){
       var copyText =  document.getElementById("affilate_address");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
          });
          $('#affilate_html_click').on('click',function(){
            var copyText =  document.getElementById("affilate_html");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
    });
})(jQuery);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/affilate/affilate-program.blade.php ENDPATH**/ ?>