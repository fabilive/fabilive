
<?php $__env->startSection('content'); ?>

          <div class="content-area">
            <div class="mr-breadcrumb">
              <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading"><?php echo e(__('Website Logo')); ?></h4>
                    <ul class="links">
                      <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                      </li>
                      <li>
                        <a href="javascript:;"><?php echo e(__('General Settings')); ?></a>
                      </li>
                      <li>
                        <a href="<?php echo e(route('admin-gs-logo')); ?>"><?php echo e(__('Website Logo')); ?></a>
                      </li>
                    </ul>

                </div>
              </div>
            </div>
            <div class="add-logo-area">
              <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
              <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6">
                    <div class="special-box bg-gray">
                        <div class="heading-area">
                            <h4 class="title">
                              <?php echo e(__('Header Logo')); ?>

                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="<?php echo e(route('admin-gs-update')); ?>" method="POST" enctype="multipart/form-data">
                          <?php echo e(csrf_field()); ?>   

                  <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
                          <div class="currrent-logo">
                            <img src="<?php echo e($gs->logo ? asset('assets/images/'.$gs->logo):asset('assets/images/noimage.png')); ?>" alt="">
                          </div>
                          <div class="set-logo">
                            <input class="img-upload1" type="file" name="logo">
                          </div>

                          <div class="submit-area mb-4">
                            <button type="submit" class="submit-btn"><?php echo e(__('Submit')); ?></button>
                          </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                  <div class="special-box  bg-gray">
                      <div class="heading-area">
                          <h4 class="title">
                            <?php echo e(__('Footer Logo')); ?>

                          </h4>
                      </div>

                      <form class="uplogo-form" id="geniusform" action="<?php echo e(route('admin-gs-update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>   

              <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
                        <div class="currrent-logo">
                          <img src="<?php echo e($gs->footer_logo ? asset('assets/images/'.$gs->footer_logo):asset('assets/images/noimage.png')); ?>" alt="">
                        </div>
                        <div class="set-logo">
                          <input class="img-upload1" type="file" name="footer_logo">
                        </div>

                        <div class="submit-area mb-4">
                          <button type="submit" class="submit-btn"><?php echo e(__('Submit')); ?></button>
                        </div>
                      </form>
                  </div>
              </div>
                <div class="col-xl-4 col-md-6">
                    <div class="special-box  bg-gray">
                        <div class="heading-area">
                            <h4 class="title">
                              <?php echo e(__('Invoice Logo')); ?>

                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="<?php echo e(route('admin-gs-update')); ?>" method="POST" enctype="multipart/form-data">
                          <?php echo e(csrf_field()); ?>   

                           <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

                          <div class="currrent-logo">
                            <img src="<?php echo e($gs->invoice_logo ? asset('assets/images/'.$gs->invoice_logo):asset('assets/images/noimage.png')); ?>" alt="">
                          </div>
                          
                          <div class="set-logo">
                            <input class="img-upload1" type="file" name="invoice_logo">
                          </div>


                          <div class="submit-area mb-4">
                            <button type="submit" class="submit-btn"><?php echo e(__('Submit')); ?></button>
                          </div>
                        </form>
                    </div>
                </div>
              </div>
            </div>
          </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/generalsetting/logo.blade.php ENDPATH**/ ?>