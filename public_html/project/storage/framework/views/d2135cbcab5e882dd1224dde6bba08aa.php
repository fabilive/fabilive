
<?php $__env->startSection('content'); ?>

          <div class="content-area">
            <div class="mr-breadcrumb">
              <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading"><?php echo e(__('Website Loader')); ?></h4>
                    <ul class="links">
                      <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                      </li>
                      <li>
                        <a href="javascript:;"><?php echo e(__('General Settings')); ?></a>
                      </li>
                      <li>
                        <a href="<?php echo e(route('admin-gs-load')); ?>"><?php echo e(__('Website Loader')); ?></a>
                      </li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="add-logo-area">
                <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
              <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
              <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                              <?php echo e(__('Website Loader')); ?>

                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="<?php echo e(route('admin-gs-update')); ?>" method="POST" enctype="multipart/form-data">
                          <?php echo e(csrf_field()); ?>   
                          <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                          <div class="loader-switcher">
                            <h4 class="title" style="margin-left: 80px;">
                                <?php echo e(__('Loader')); ?> :
                              </h4>
                                      <div class="action-list">
                                          <select class="process select droplinks <?php echo e($gs->is_loader == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['is_loader',1])); ?>" <?php echo e($gs->is_loader == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?></option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['is_loader',0])); ?>" <?php echo e($gs->is_loader == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?></option>
                                          </select>
                                        </div>
                          </div>
                          <div class="currrent-logo">
                            <h4 class="title">
                              <?php echo e(__('Current Loader')); ?> :
                            </h4>
                            <img src="<?php echo e($gs->loader ? asset('assets/images/'.$gs->loader):asset('assets/images/noimage.png')); ?>" alt="">
                          </div>
                          <div class="set-logo">
                            <h4 class="title">
                              <?php echo e(__('Set New Loader')); ?> :
                            </h4>
                            <input class="img-upload1" type="file" name="loader">
                          </div>

                          <div class="submit-area mb-4">
                            <button type="submit" class="submit-btn"><?php echo e(__('Submit')); ?></button>
                          </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                              <?php echo e(__('Admin Loader')); ?>

                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="<?php echo e(route('admin-gs-update')); ?>" method="POST" enctype="multipart/form-data">
                          <?php echo e(csrf_field()); ?>   
                          <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                          <div class="loader-switcher">
                            <h4 class="title" style="margin-left: 80px;">
                                <?php echo e(__('Loader')); ?> :
                              </h4>
                                      <div class="action-list">
                                          <select class="process select droplinks <?php echo e($gs->is_admin_loader == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['is_admin_loader',1])); ?>" <?php echo e($gs->is_admin_loader == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?></option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['is_admin_loader',0])); ?>" <?php echo e($gs->is_admin_loader == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?></option>
                                          </select>
                                        </div>
                          </div>
           

                          <div class="currrent-logo">
                            <h4 class="title">
                              <?php echo e(__('Current Loader')); ?> :
                            </h4>

                            <img src="<?php echo e($gs->admin_loader ? asset('assets/images/'.$gs->admin_loader):asset('assets/images/noimage.png')); ?>" alt="">
                          </div>
                          
                          <div class="set-logo">
                            <h4 class="title">
                              <?php echo e(__('Set New Loader')); ?> :
                            </h4>
                            <input class="img-upload1" type="file" name="admin_loader">
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/generalsetting/loader.blade.php ENDPATH**/ ?>