

<?php $__env->startSection('content'); ?>

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            <?php echo $__env->make('includes.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <form id="geniusformdata" action="<?php echo e(route('admin-city-update',$city->id)); ?>" method="POST">
              <?php echo e(csrf_field()); ?>


              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading"><?php echo e(__('State')); ?> *</h4>
                    <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <input type="text" readonly class="input-field" value="<?php echo e($city->state->state); ?>">
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading"><?php echo e(__('City')); ?> *</h4>
                    <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <input type="text" class="input-field" name="city_name" placeholder="<?php echo e(__('Enter City')); ?>"
                    value="<?php echo e($city->city_name); ?>">
                </div>
              </div>

              <br>
              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">

                  </div>
                </div>
                <div class="col-lg-7">
                  <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Create City')); ?></button>
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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/country/state/city/edit.blade.php ENDPATH**/ ?>