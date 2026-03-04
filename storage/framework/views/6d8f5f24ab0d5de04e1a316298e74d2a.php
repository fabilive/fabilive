

<?php $__env->startSection('content'); ?>

<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="heading"><?php echo e(__('Customize Menu Links')); ?></h4>
        <ul class="links">
          <li>
            <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
          </li>
          <li>
            <a href="javascript:;"><?php echo e(__('Menu Page Settings')); ?></a>
          </li>
          <li>
            <a href="<?php echo e(route('admin-ps-menu-links')); ?>"><?php echo e(__('Customize Menu Links')); ?></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="add-product-content1">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="social-links-area">
            <div class="gocover"
              style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
            </div>
            <form id="geniusform" action="<?php echo e(route('admin-ps-menuupdate')); ?>" method="POST"
              enctype="multipart/form-data">
              <?php echo csrf_field(); ?>

              <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

              <div class="row justify-content-center">

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="home"><?php echo e(__('Product')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="home" value="1" <?php echo e($data->home==1?"checked":""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

                <div class="col-lg-2"></div>

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="blog"><?php echo e(__('Blog')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="blog" value="1" <?php echo e($data->blog==1?"checked":""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

              </div>

              <div class="row justify-content-center">

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="faq"><?php echo e(__('Faq')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="faq" value="1" <?php echo e($data->faq==1?"checked":""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

                <div class="col-lg-2"></div>

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="contact_us"><?php echo e(__('Contact Us')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="contact" value="1" <?php echo e($data->contact==1?"checked":""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

              </div>

              <br>

              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="submit-btn"><?php echo e(__('Submit')); ?></button>
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/pagesetting/menu_links.blade.php ENDPATH**/ ?>