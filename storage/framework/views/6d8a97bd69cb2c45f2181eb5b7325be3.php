

<?php $__env->startSection('content'); ?>

<div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Contact Us')); ?></h4>
                    <ul class="links">
                      <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                      </li>
                      <li>
                        <a href="javascript:;"><?php echo e(__('Menu Page Settings')); ?></a>
                      </li>
                      <li>
                        <a href="<?php echo e(route('admin-ps-contact')); ?>"><?php echo e(__('Contact Us Page')); ?></a>
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
                        <form id="geniusform" action="<?php echo e(route('admin-ps-update')); ?>" method="POST" enctype="multipart/form-data">
                          <?php echo csrf_field(); ?>

                        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

                          <div class="row justify-content-center">
                              <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading"><?php echo e(__('Email')); ?> *
                                      </h4>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <input type="email" class="input-field" placeholder="<?php echo e(__('Enter Email')); ?>" name="email" value="<?php echo e($data->email); ?>">
                              </div>
                            </div>
    
                            <div class="row justify-content-center">
                              <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading"><?php echo e(__('Website')); ?> *
                                      </h4>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <input type="text" class="input-field" placeholder="<?php echo e(__('Enter Website')); ?>" name="site" value="<?php echo e($data->site); ?>">
                              </div>
                            </div>
    
                            <div class="row justify-content-center">
                              <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading"><?php echo e(__('Phone')); ?> *
                                      </h4>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <input type="text" class="input-field" placeholder="<?php echo e(__('Enter Phone')); ?>" name="phone" value="<?php echo e($data->phone); ?>">
                              </div>
                            </div>
    
                            <div class="row justify-content-center">
                              <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading"><?php echo e(__('Fax')); ?> *
                                      </h4>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <input type="text" class="input-field" placeholder="<?php echo e(__('Enter Fax')); ?>" name="fax" value="<?php echo e($data->fax); ?>">
                              </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                  <div class="left-area">
                                    <h4 class="heading">
                                        <?php echo e(__('Street Address')); ?> *
                                    </h4>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                      <textarea name="street" class="input-field" placeholder="Enter Street Address"> <?php echo e($data->street); ?> </textarea>
                                </div>
                              </div>

                          <div class="row justify-content-center">
                              <div class="col-lg-3">
                                <div class="left-area">
                                  <h4 class="heading">
                                      <?php echo e(__('Contact Us Email Address')); ?> *
                                  </h4>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="tawk-area">
                                    <textarea name="contact_email"> <?php echo e($data->contact_email); ?> </textarea>
                                  </div>
                              </div>
                            </div>

                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-6">
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/pagesetting/contact.blade.php ENDPATH**/ ?>