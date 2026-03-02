

<?php $__env->startSection('content'); ?>

            <div class="content-area">
              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                      <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 

                                      <div class="row" >
                                        <div class="col-md-6 offset-md-4">
                                        <p><?php echo e(__('Use the BB codes, it show the data dynamically in your emails.')); ?></p>
                                        <br>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th><?php echo e(__('Meaning')); ?></th>
                                                <th><?php echo e(__('BB Code')); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><?php echo e(__('Customer Name')); ?></td>
                                                <td>{customer_name}</td>
                                            </tr>
                                            <tr>
                                                <td><?php echo e(__('Order Amount')); ?></td>
                                                <td>{order_amount}</td>
                                            </tr>
                                            <tr>
                                                <td><?php echo e(__('Admin Name')); ?></td>
                                                <td>{admin_name}</td>
                                            </tr>
                                            <tr>
                                                <td><?php echo e(__('Admin Email')); ?></td>
                                                <td>{admin_email}</td>
                                            </tr>
                                            <tr>
                                                <td><?php echo e(__('Website Title')); ?></td>
                                                <td>{website_title}</td>
                                            </tr>
                                            <tr>
                                                <td><?php echo e(__('Order Number')); ?></td>
                                                <td>{order_number}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                        </div>
                                        </div>

                      <form id="geniusformdata" action="<?php echo e(route('admin-mail-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Email Type')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" placeholder="<?php echo e(__('Email Type')); ?>" required="" value="<?php echo e($data->email_type); ?>" disabled="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Email Subject')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                            </div>

                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="email_subject" placeholder="<?php echo e(__('Email Subject')); ?>" required="" value="<?php echo e($data->email_subject); ?>">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              <h4 class="heading"><?php echo e(__('Email Body')); ?> *</h4>
                              <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <textarea class="nic-edit" name="email_body" placeholder="<?php echo e(__('Email Body')); ?>"><?php echo e($data->email_body); ?></textarea> 
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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/email/edit.blade.php ENDPATH**/ ?>