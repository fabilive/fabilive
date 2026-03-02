

<?php $__env->startSection('content'); ?>

            <div class="content-area">

              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <form id="geniusformdata" action="<?php echo e(route('admin-cat-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Name')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="name" placeholder="<?php echo e(__('Enter Name')); ?>" required="" value="<?php echo e($data->name); ?>">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Slug')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In English)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="slug" placeholder="<?php echo e(__('Enter Slug')); ?>" required="" value="<?php echo e($data->slug); ?>">
                          </div>
                        </div>
						
						<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Current Image')); ?>*</h4>
														</div>
													</div>
                          <div class="col-lg-7">
                            <div class="img-upload">
                              <div id="image-preview" class="img-preview" style="background: url(<?php echo e($data->image ? asset('assets/images/categories/'.$data->image):asset('assets/images/noimage.png')); ?>);">
                                <label for="image-upload" class="img-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                <input type="file" name="image" class="img-upload">
                              </div>
                              <p class="text"><?php echo e(__('Prefered Size: (1230x267) or Square Sized Image')); ?></p>
                            </div>
                          </div>
												</div>
												
												
						<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Current Photo')); ?>*</h4>
														</div>
													</div>
                          <div class="col-lg-7">
                            <div class="img-upload">
                              <div id="image-preview" class="img-preview" style="background: url(<?php echo e($data->image ? asset('assets/images/categories/'.$data->photo):asset('assets/images/noimage.png')); ?>);">
                                <label for="image-upload" class="img-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                <input type="file" name="photo" class="img-upload">
                              </div>
                              <p class="text"><?php echo e(__('Prefered Size: (1230x267) or Square Sized Image')); ?></p>
                            </div>
                          </div>
												</div>
												
												
												
                        <br>
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

<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/category/edit.blade.php ENDPATH**/ ?>