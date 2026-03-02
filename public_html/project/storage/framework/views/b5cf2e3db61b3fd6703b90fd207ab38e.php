

<?php $__env->startSection('content'); ?>
<input type="hidden" id="headerdata" value="<?php echo e(strtoupper($country->country_name)); ?> / <?php echo e(__('STATE TAX')); ?>">
            <div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><u><?php echo e(__($country->country_name)); ?></u> / <?php echo e(__('Tax')); ?> <a class="add-btn" href="<?php echo e(route('admin-country-tax')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                      <ul class="links">
                        <li>
                          <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                        </li>
                        <li>
                          <a href="javascript:;"><?php echo e(__('Country')); ?> </a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-country-index')); ?>"><?php echo e(__('Manage Tax')); ?> </a>
                        </li>
                        <li>
                          <a href="javascript:;"><?php echo e(__('Tax')); ?></a>
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
                      <form id="geniusform" action="<?php echo e(route('admin-tax-update',$country->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>

                      <?php echo $__env->make('includes.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 

                      <div class="row">
                        <div class="col-lg-4">
                          <div class="left-area">
                              <h4 class="heading"><?php echo e(__('Country')); ?> *</h4>
                              <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                          </div>
                        </div>
                        <div class="col-lg-7">
                          <input type="text" readonly class="input-field"  value="<?php echo e($country->country_name); ?>">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-4">
                          <div class="left-area">
                              <h4 class="heading"><?php echo e(__('Tax')); ?> (%)  *</h4>
                             
                          </div>
                        </div>
                        <div class="col-lg-7">
                          <input type="text" name="tax" class="input-field" placeholder="<?php echo e(__('Enter Tax')); ?>"  value="<?php echo e($country->tax); ?>">
                        </div>
                      </div>

                      
                      <div class="row">
                        <div class="col-lg-4">
                          <div class="left-area">
                              <h4 class="heading"><?php echo e(__('Allow State Tax')); ?></h4>
                          </div>
                        </div>
                        <div class="col-lg-7">
                            <ul class="list">
                                <li>
                                    <input type="checkbox" name="is_state_tax" id="allow_state_tax" value="1" id="check1">
                                    <label for="check1"><?php echo e(__('Allow State Tax')); ?> </label>
                                </li>
                            </ul>
                        </div>
                      </div>

                      <div class="show_state d-none">
                        <hr>
                        <u><h4 class="text-center mb-3"><?php echo e($country->country_name); ?> / <?php echo e(__('State List')); ?></h4></u>
                        <br>
                      <?php $__empty_1 = true; $__currentLoopData = $country->states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading"><?php echo e(__($state->state)); ?> (%)  *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <input type="text"  class="input-field" name="state_tax[]" placeholder="Enter Tax"  value="<?php echo e($state->tax); ?>">
                            </div>
                          </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center">
                        <?php echo e(__('State Not Found Please')); ?>  <a class="mybtn1" href="<?php echo e(route('admin-state-index',$country->id)); ?>"><?php echo e(__('Insert State')); ?></a>
                    </div>
                    <?php endif; ?>
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
    <script>
        $(document).on('click','#allow_state_tax',function(){
            if($(this).is(':checked')){
                $('.show_state').removeClass('d-none');
            }else{
                $('.show_state').addClass('d-none');
            }
        })

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/country/set_tax.blade.php ENDPATH**/ ?>