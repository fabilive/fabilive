<?php $__env->startSection('content'); ?>

            <div class="content-area">
                <div class="mr-breadcrumb">
                    <div class="row">
                      <div class="col-lg-12">
                          <h4 class="heading"><?php echo e(__('Add Agreement')); ?> <a class="add-btn" href="<?php echo e(route('admin-agreement-index')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                          <ul class="links">
                            <li>
                              <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                            </li>
                            <li>
                              <a href="<?php echo e(route('admin-agreement-index')); ?>"><?php echo e(__('Manage Agreement')); ?></a>
                            </li>
                            <li>
                              <a href="<?php echo e(route('admin-agreement-create')); ?>"><?php echo e(__('Add Agreemnet')); ?></a>
                            </li>
                          </ul>
                      </div>
                    </div>
                  </div>
                  <div class="add-product-content">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="product-description">
                          <div class="body-area">
                            <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                            <form id="geniusform" action="<?php echo e(route('admin-agreement-store')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                <!-- Type Dropdown -->
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="" disabled selected>Select type</option>
                                        <option value="Fabilive_Delivery_Individual_Agreement">Fabilive Delivery Individual Agreement</option>
                                        <option value="Fabilive_Delivery_Company_Agreement">Fabilive Delivery Company Agreement</option>
                                        <option value="Fabilive_Sub_merchant_Agreement">Fabilive Sub merchant Agreement</option>
                                        <option value="Selfi_Instructions">Selfi Instructions</option>

                                    </select>
                                </div>

                                <!-- PDF Upload -->
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Agreement (PDF only)</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="application/pdf" required>
                                </div>

                                <!-- Centered Submit Button -->
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" class="btn btn-dark rounded-2">Add Agreement</button>
                                </div>
                            </form>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

            </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/agreement/create.blade.php ENDPATH**/ ?>