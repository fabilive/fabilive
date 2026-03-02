<?php $__env->startSection('content'); ?>

            <div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Edit Arrival')); ?> <a class="add-btn" href="<?php echo e(route('admin-arrival-index')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                      <ul class="links">
                        <li>
                          <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                        </li>
                        <li>
                          <a href="javascript:;"><?php echo e(__('Home Page Settings')); ?></a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-arrival-index')); ?>"><?php echo e(__('Arrival')); ?></a>
                        </li>

                          <li>
                            <a href="<?php echo e(route('admin-arrival-edit',$data->id)); ?>"><?php echo e(__('Edit')); ?></a>
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
                      <form id="geniusform" action="<?php echo e(route('admin-arrival-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>

                      <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Title')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="title" placeholder="<?php echo e(__('Add title here')); ?>" required="" value="<?php echo e($data->title); ?>">
                          </div>
                        </div>

                        
                      <div class="row">
                        <div class="col-lg-4">
                          <div class="left-area">
                              <h4 class="heading"><?php echo e(__('Text')); ?> *</h4>
                          </div>
                        </div>
                        <div class="col-lg-7">
                          <input type="text" class="input-field" name="header" placeholder="<?php echo e(__('Add Text here')); ?>" required="" value="<?php echo e($data->header); ?>">
                        </div>
                      </div>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Up To Sale')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="up_sale" placeholder="<?php echo e(__('Up To Sale')); ?>" required="" value="<?php echo e($data->up_sale); ?>">
                          </div>
                        </div>


                        
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Url')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="url" placeholder="<?php echo e(__('Add Url here')); ?>" required="" value="<?php echo e($data->url); ?>">
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
                                <div id="image-preview" class="img-preview" style="background: url(<?php echo e($data->photo ? asset('assets/images/arrival/'.$data->photo):asset('assets/images/noimage.png')); ?>);">
                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                    <input type="file" name="photo" class="img-upload" id="image-upload">
                                  </div>
                                  <p class="text"><?php echo e(__('Prefered Size: (600x770) or Square Sized Image')); ?></p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">

                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Update Arrival')); ?></button>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/arrival/edit.blade.php ENDPATH**/ ?>