
<?php $__env->startSection('content'); ?>
            <div class="content-area">
              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area" id="modalEdit">
                        <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <form id="geniusformdata" action="<?php echo e(route('admin-order-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Payment Status')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <select name="payment_status" required="">
                                <option value="Pending" <?php echo e($data->payment_status == 'Pending' ? "selected":""); ?>><?php echo e(__('Unpaid')); ?></option>
                                <option value="Completed" <?php echo e($data->payment_status == 'Completed' ? "selected":""); ?>><?php echo e(__('Paid')); ?></option>
                              </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Delivery Status')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <select name="status" required="">
                                <option value="pending" <?php echo e($data->status == "pending" ? "selected":""); ?>><?php echo e(__('Pending')); ?></option>
                                <option value="processing" <?php echo e($data->status == "processing" ? "selected":""); ?>><?php echo e(__('Processing')); ?></option>
                                <option value="on delivery" <?php echo e($data->status == "on delivery" ? "selected":""); ?>><?php echo e(__('On Delivery')); ?></option>
                                <option value="completed" <?php echo e($data->status == "completed" ? "selected":""); ?>><?php echo e(__('Completed')); ?></option>
                                <option value="declined" <?php echo e($data->status == "declined" ? "selected":""); ?>><?php echo e(__('Declined')); ?></option>
                              </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Track Note')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <textarea class="input-field" required name="track_text" placeholder="<?php echo e(__('Enter Track Note Here')); ?>"></textarea>
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
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/delivery.blade.php ENDPATH**/ ?>