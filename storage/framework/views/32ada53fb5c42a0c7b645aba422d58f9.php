<?php $__env->startSection('content'); ?>

<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">

      <div class="col-lg-12">
        <h4 class="heading"><?php echo e(__('Home Page Customization')); ?></h4>
        <ul class="links">
          <li>
            <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
          </li>
          <li>
            <a href="javascript:;"><?php echo e(__('Home Page Settings')); ?></a>
          </li>
          <li>
            <a href="<?php echo e(route('admin-ps-customize')); ?>"><?php echo e(__('Home Page Customization')); ?></a>
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
            <form id="geniusform" action="<?php echo e(route('admin-ps-homeupdate')); ?>" method="POST"
              enctype="multipart/form-data">
              <?php echo csrf_field(); ?>

              <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

              <div class="row justify-content-center">

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="slider"><?php echo e(__('Slider')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="slider" value="1" <?php echo e($data->slider == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

                <div class="col-lg-2"></div>

          
                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="arrival_section"><?php echo e(__('Arrival Section')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="arrival_section" value="1" <?php echo e($data->arrival_section == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

              </div>

              <div class="row justify-content-center">

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="category"><?php echo e(__('Featured Products')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="category" value="1" <?php echo e($data->category == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

                <div class="col-lg-2"></div>
                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="deal_of_the_day"><?php echo e(__('Deal Of The Day')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="deal_of_the_day" value="1" <?php echo e($data->deal_of_the_day == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

              </div>

              <div class="row justify-content-center">

             
                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="top_big_trending"><?php echo e(__('Top Rated, Big Save & Trending')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="top_big_trending" value="1" <?php echo e($data->top_big_trending == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

                <div class="col-lg-2"></div>

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="partner"><?php echo e(__('Partner')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="partner" value="1" <?php echo e($data->partner == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

              </div>

              <div class="row justify-content-center">

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="best_sellers"><?php echo e(__('Best Selling Product')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="best_sellers" value="1" <?php echo e($data->best_sellers == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

                <div class="col-lg-2"></div>

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="blog"><?php echo e(__('Blogs')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="blog" value="1" <?php echo e($data->blog == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

              </div>

            

              <div class="row justify-content-center">

                <div class="col-lg-4 d-flex justify-content-between">
                  <label class="control-label" for="third_left_banner"><?php echo e(__('Newsletter')); ?> *</label>
                  <label class="switch">
                    <input type="checkbox" name="third_left_banner" value="1" <?php echo e($data->third_left_banner == 1 ? "checked" : ""); ?>>
                    <span class="slider round"></span>
                  </label>
                </div>

                <div class="col-lg-2"></div>
                <div class="col-lg-4 d-flex justify-content-between">
                 
                </div>
              </div>

      

              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="submit-btn"><?php echo e(__('Submit')); ?></button>
                </div>
              </div>

            </form>

              </div>

              <br>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/pagesetting/customize.blade.php ENDPATH**/ ?>