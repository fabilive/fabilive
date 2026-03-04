

<?php $__env->startSection('content'); ?>

<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="heading"><?php echo e(__('Vendor Verification')); ?></h4>
        <ul class="links">
          <li>
            <a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
          </li>
          <li>
            <a href="<?php echo e(route('vendor-verify')); ?>"><?php echo e(__('Vendor Verification')); ?></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="add-product-content1">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">

            <?php if($data->checkVerification()): ?>


            <div class="alert alert-success validation" style="">
              <p class="text-left">
                <div class="text-center"><i class="fas fa-check-circle fa-4x"></i><br>
                  <h3><?php echo e(__('Your Documents Submitted Successfully.')); ?></h3>
                </div>
              </p>
            </div>
            <?php else: ?>
            <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="gocover"
              style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
            </div>
            <form id="verifyform" action="<?php echo e(route('vendor-verify-submit')); ?>" method="POST"
              enctype="multipart/form-data">
              <?php echo e(csrf_field()); ?>

              <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              <div class="row  py-3">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">
                      <?php echo e(__('Description')); ?> *
                    </h4>
                  </div>
                </div>
                <div class="col-lg-7">
                  <textarea class="input-field" name="text" required=""
                    placeholder="<?php echo e(__('Enter Details')); ?>"></textarea>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">
                      <?php echo e(__('Attachment')); ?>*
                    </h4>
                    <p class="sub-heading"><?php echo e(__('(Maximum Size is 10 MB)')); ?></p>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="attachments" id="attachment-section">
                    <div class="attachment-area">
                      <input type="file" name="attachments[]" required>
                    </div>
                  </div>
                  <a href="javascript:;" id="attachment-btn" class="add-more mt-4"><i
                      class="fas fa-plus"></i><?php echo e(__('Add More Attachment')); ?> </a>
                </div>
              </div>

              <input type="hidden" name="warning" value="<?php echo e(isset($verify) ? $verify->admin_warning : '0'); ?>" />
              <input type="hidden" name="verify_id" value="<?php echo e(isset($verify) ? $verify->id : '0'); ?>" />

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">

                  </div>
                </div>
                <div class="col-lg-7">
                  <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Submit')); ?></button>
                </div>
              </div>
            </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>


<script type="text/javascript">

(function($) {
		"use strict";

  function isEmpty(el) {
    return !$.trim(el.html())
  }

  // Color Section

  $("#attachment-btn").on('click', function () {

    $("#attachment-section").append('' +
      '<div class="attachment-area  mt-2">' +
      '<span class="remove attachment-remove"><i class="fas fa-times"></i></span>' +
      '<input type="file" name="attachments[]" required>' +
      '</div>' +
      '');
  });


  $(document).on('click', '.attachment-remove', function () {

    $(this.parentNode).remove();
    if (isEmpty($('#attachment-section'))) {

      $("#attachment-section").append('' +
        '<div class="attachment-area  mt-2">' +
        '<input type="file" name="attachments[]" required>' +
        '</div>' +
        '');

    }

  });

  // Color Section Ends

})(jQuery);

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/verify.blade.php ENDPATH**/ ?>