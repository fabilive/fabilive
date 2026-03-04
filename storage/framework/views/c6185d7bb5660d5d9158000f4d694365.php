<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e($page->title); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?php echo e($page->title); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->

  <!--==================== About Owner Section Start ====================-->
  <div class="full-row">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12">
                <?php echo clean($page->details , array('Attr.EnableID' => true)); ?>

            </div>
            <div class="col-lg-5 col-md-12 sm-mx-none mt-5">
                <img class="sm-mb-30" src="<?php echo e($page->photo ? asset('assets/images/pages/'.$page->photo) : 'Image not found!'); ?>" alt="Image not found!">
            </div>
        </div>
    </div>
</div>
<!--==================== About Owner Section End ====================-->




<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Shilley Pc\fabilive\resources\views/frontend/page.blade.php ENDPATH**/ ?>