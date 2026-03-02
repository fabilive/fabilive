

<?php $__env->startSection('content'); ?>

<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="heading"><?php echo e(__("Add Product")); ?></h4>
        <ul class="links">
          <li>
            <a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__("Dashboard")); ?></a>
          </li>
          <li>
            <a href="javascript:;"><?php echo e(__("Products")); ?> </a>
          </li>
          <li>
            <a href="<?php echo e(route('vendor-prod-index')); ?>"><?php echo e(__("All Products")); ?></a>
          </li>
          <li>
            <a href="<?php echo e(route('vendor-prod-types')); ?>"><?php echo e(__("Add Product")); ?></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="heading-area">
            <h2 class="title">
              <?php echo e(__("Product Types")); ?>

            </h2>
          </div>
        </div>
      </div>
    </div>
    <div class="ap-product-categories">
      <div class="row">
        <?php if($gs->physical == 1): ?>
        <div class="col-lg-4">
          <a href="<?php echo e(route('vendor-prod-create','physical')); ?>">
            <div class="cat-box box1">
              <div class="icon">
                <i class="fas fa-tshirt"></i>
              </div>
              <h5 class="title"><?php echo e(__("Physical")); ?> </h5>
            </div>
          </a>
        </div>
        <?php endif; ?>
        <?php if($gs->digital == 1): ?>
        <div class="col-lg-4">
          <a href="<?php echo e(route('vendor-prod-create','digital')); ?>">
            <div class="cat-box box2">
              <div class="icon">
                <i class="fas fa-camera-retro"></i>
              </div>
              <h5 class="title"><?php echo e(__("Digital")); ?> </h5>
            </div>
          </a>
        </div>
        <?php endif; ?>
        <?php if($gs->license == 1): ?>
        <div class="col-lg-4">
          <a href="<?php echo e(route('vendor-prod-create','license')); ?>">
            <div class="cat-box box3">
              <div class="icon">
                <i class="fas fa-award"></i>
              </div>
              <h5 class="title"><?php echo e(__("license")); ?> </h5>
            </div>
          </a>
        </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/product/types.blade.php ENDPATH**/ ?>