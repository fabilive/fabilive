

<?php $__env->startSection('content'); ?>

<div class="content-area">

  <div class="add-product-content1">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <form id="geniusformdata" action="<?php echo e(route('vendor-pickup-point-create')); ?>" method="POST"
              enctype="multipart/form-data">
              <?php echo e(csrf_field()); ?>


              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading"><?php echo e(__('Location')); ?> *</h4>
                    <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <input type="text" class="input-field" name="location" placeholder="<?php echo e(__('Location')); ?>" required=""
                    value="">
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">

                  </div>
                </div>
                <div class="col-lg-7">
                  <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Create')); ?></button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/pickup/create.blade.php ENDPATH**/ ?>