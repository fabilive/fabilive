

<?php $__env->startSection('content'); ?>

<div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Affialte Program')); ?></h4>
                    <ul class="links">
                      <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                      </li>
                      <li>
                        <a href="javascript:;"><?php echo e(__('General Settings')); ?></a>
                      </li>
                      <li>
                        <a href="<?php echo e(route('admin-gs-affilate')); ?>"><?php echo e(__('Affialte Program')); ?></a>
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
                        <form action="<?php echo e(route('admin-gs-update')); ?>" id="geniusform" method="POST" enctype="multipart/form-data">
                          <?php echo csrf_field(); ?>

                        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                              <div class="left-area">
                                <h4 class="heading">
                                    <?php echo e(__('Web Based Affilate')); ?>

                                </h4>
                              </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="action-list">
                                    <select class="process select droplinks <?php echo e($gs->is_affilate == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                      <option data-val="1" value="<?php echo e(route('admin-gs-status',['is_affilate',1])); ?>" <?php echo e($gs->is_affilate == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?></option>
                                      <option data-val="0" value="<?php echo e(route('admin-gs-status',['is_affilate',0])); ?>" <?php echo e($gs->is_affilate == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?></option>
                                    </select>
                                  </div>
                            </div>
                          </div>

                          <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">
                                        <?php echo e(__('Product Based Affilate')); ?>

                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="action-list">
                                    <select
                                        class="process select droplinks <?php echo e($gs->product_affilate == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                        <option data-val="1" value="<?php echo e(route('admin-gs-status',['product_affilate',1])); ?>"
                                            <?php echo e($gs->product_affilate == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?>

                                        </option>
                                        <option data-val="0" value="<?php echo e(route('admin-gs-status',['product_affilate',0])); ?>"
                                            <?php echo e($gs->product_affilate == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Affilate Bonus(%)')); ?></h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <input type="text" class="input-field" placeholder="<?php echo e(__('Write Your Site Title Here')); ?>" name="affilate_charge" value="<?php echo e($gs->affilate_charge); ?>" required="">
                          </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading"><?php echo e(__('Current Featured Image')); ?> *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                    <div class="img-upload">
                                        <div id="image-preview" class="img-preview text-center" style="background: url(<?php echo e($gs->affilate_banner ? asset('assets/images/'.$gs->affilate_banner):asset('assets/images/noimage.png')); ?>);">
                                            <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                            <input type="file" name="affilate_banner" class="img-upload">
                                          </div>
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/generalsetting/affilate.blade.php ENDPATH**/ ?>