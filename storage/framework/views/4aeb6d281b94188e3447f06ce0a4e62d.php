

<?php $__env->startSection('content'); ?>

<div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Google Analytics')); ?></h4>
                    <ul class="links">
                      <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                      </li>
                      <li>
                        <a href="javascript:;"><?php echo e(__('SEO Tools')); ?></a>
                      </li>
                      <li>
                        <a href="<?php echo e(route('admin-seotool-analytics')); ?>"><?php echo e(__('Google Analytics')); ?></a>
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
                        <form id="geniusform" action="<?php echo e(route('admin-seotool-analytics-update')); ?>" method="POST" enctype="multipart/form-data">
                          <?php echo e(csrf_field()); ?>

                        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                              <h4 class="heading">
                                  <?php echo e(__('Google Analytics ID')); ?> *
                              </h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="tawk-area">
                                <input class="input-field" name="google_analytics" type="text" placeholder="<?php echo e(__('Google Analytics ID')); ?> " value="<?php echo e($tool->google_analytics); ?>">
                              </div>
                          </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                              <div class="left-area">
                                <h4 class="heading">
                                    <?php echo e(__('Facebook Pixel ID')); ?> *
                                </h4>
                              </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="tawk-area">
                                  <input class="input-field" name="facebook_pixel" type="text" placeholder="<?php echo e(__('Facebook Pixel ID')); ?> " value="<?php echo e($tool->facebook_pixel); ?>">
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
                      </div>
                     </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/seotool/googleanalytics.blade.php ENDPATH**/ ?>