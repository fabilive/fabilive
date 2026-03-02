

<?php $__env->startSection('content'); ?>
            <div class="content-area">

              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                      <form id="geniusformdata" action="<?php echo e(route('admin-package-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Title')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="title" placeholder="<?php echo e(__('Title')); ?>" required="" value="<?php echo e($data->title); ?>">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Subtitle')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="subtitle" placeholder="<?php echo e(__('Subtitle')); ?>" required="" value="<?php echo e($data->subtitle); ?>">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Price')); ?> *</h4>
                                <p class="sub-heading">(<?php echo e(__('In')); ?> <?php echo e($sign->name); ?>)</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="number" class="input-field" name="price" placeholder="<?php echo e(__('Price')); ?>" required="" value="<?php echo e($data->price * $sign->value); ?>" min="0" step="0.01">
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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/package/edit.blade.php ENDPATH**/ ?>