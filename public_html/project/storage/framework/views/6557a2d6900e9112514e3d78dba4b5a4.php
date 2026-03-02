

<?php $__env->startSection('content'); ?>

            <div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Edit Deal Of The Day')); ?> <a class="add-btn" href="<?php echo e(route('admin-ps-deal')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                      <ul class="links">
                          
                        <li>
                          <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                        </li>
                        <li>
                          <a href="javascript:;"><?php echo e(__('Home Page Settings')); ?></a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-ps-deal')); ?>"><?php echo e(__('Deal Of The Day')); ?></a>
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
                      <form id="geniusform" action="<?php echo e(route('admin-gs-update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>

                      <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                      

                                              <div class="form-group">
                                                  <div class="col-sm-12">
                                                    <label class="control-label" for="subtitle_text"><?php echo e(__('Text')); ?>*</label>
                                                  <input type="text" class="form-control" name="deal_title" value="<?php echo e($gs->deal_title); ?>" placeholder="Add title here">
                                                </div>
                                              </div>

                                              <div class="form-group">
                                                <div class="col-sm-12">
                                                  <label class="control-label" for="subtitle_text"><?php echo e(__('Details')); ?>*</label>
                                                <input type="text" class="form-control" name="deal_details" value="<?php echo e($gs->deal_details); ?>" placeholder="Add details here">
                                              </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                  <label class="control-label" for="subtitle_text"><?php echo e(__('Date Limit')); ?>*</label>
                                                <input type="date" class="form-control" name="deal_time" value="<?php echo e($gs->deal_time); ?>" placeholder="Add Date here">
                                              </div>
                                            </div>
                                      
                        <div class="row mt-5">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Current Featured Image')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <div class="img-upload full-width-img">
                                <div id="image-preview" class="img-preview" style="background: url(<?php echo e($gs->deal_background ? asset('assets/images/'.$gs->deal_background):asset('assets/images/noimage.png')); ?>);">
                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                    <input type="file" name="deal_background" class="img-upload" id="image-upload">
                                  </div>
                                  <p class="text"><?php echo e(__('Prefered Size: (1920x800) or Square Sized Image')); ?></p>
                            </div>
                          </div>
                        </div>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">

                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Update Deal')); ?></button>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/pagesetting/deal.blade.php ENDPATH**/ ?>