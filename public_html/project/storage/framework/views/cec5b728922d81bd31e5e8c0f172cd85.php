<?php $__env->startSection('content'); ?>

            <div class="content-area">


              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Edit Page')); ?> <a class="add-btn" href="<?php echo e(url()->previous()); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__("Back")); ?></a></h4>
                      <ul class="links">
                        <li>
                          <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                        </li>
                        <li>
                          <a href="javascript:;"><?php echo e(__('Menu Page Settings')); ?> </a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-page-index')); ?>"><?php echo e(__('Pages')); ?></a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-page-edit',$data->id)); ?>"><?php echo e(__('Edit')); ?></a>
                        </li>
                      </ul>
                  </div>
                </div>
              </div>

              <div class="add-product-content1 add-product-content2">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">

                      <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                      <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                      <form id="geniusform" action="<?php echo e(route('admin-page-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Title')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="title" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e($data->title); ?>" required="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Slug')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="slug" placeholder="<?php echo e(__('Slug')); ?>" value="<?php echo e($data->slug); ?>" required="">
                          </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading"><?php echo e(__('Current Featured Image')); ?> *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <div class="img-upload">
                                  <div id="image-preview" class="img-preview" style="background: url(<?php echo e($data->photo ? asset('assets/images/pages/'.$data->photo):asset('assets/images/noimage.png')); ?>);">
                                      <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                      <input type="file" name="photo" class="img-upload" id="image-upload">
                                    </div>
                              </div>

                            </div>
                          </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              <h4 class="heading">
                                   <?php echo e(__('Description')); ?> *
                              </h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <textarea class="nic-edit-p" name="details" placeholder="<?php echo e(__('Description')); ?>"><?php echo e($data->details); ?></textarea>
                                <div class="checkbox-wrapper">
                                  <input type="checkbox" name="secheck" class="checkclick" id="allowProductSEO" <?php echo e(($data->meta_tag != null || strip_tags($data->meta_description) != null) ? 'checked':''); ?>>
                                  <label for="allowProductSEO"><?php echo e(__('Allow Page SEO')); ?></label>
                                </div>

                          </div>
                        </div>



                        <div class="<?php echo e(($data->meta_tag == null && strip_tags($data->meta_description) == null) ? "showbox":""); ?>">
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading"><?php echo e(__('Meta Tags')); ?> *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <ul id="metatags" class="myTags">
                                <?php $__currentLoopData = explode(',',$data->meta_tag); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <li><?php echo e($element); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </ul>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                <h4 class="heading">
                                    <?php echo e(__('Meta Description')); ?> *
                                </h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <div class="text-editor">
                                <textarea name="meta_description" class="input-field" placeholder="<?php echo e(__('Meta Description')); ?>"><?php echo e($data->meta_description); ?></textarea>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">

                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Save')); ?></button>
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

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript">

(function($) {
		"use strict";

          $("#metatags").tagit({
          fieldName: "meta_tag[]",
          allowSpaces: true
          });

})(jQuery);

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/page/edit.blade.php ENDPATH**/ ?>