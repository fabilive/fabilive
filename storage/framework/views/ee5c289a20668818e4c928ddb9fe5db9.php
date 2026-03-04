

<?php $__env->startSection('content'); ?>

            <div class="content-area">

              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                      <form id="geniusformdata" action="<?php echo e(route('admin-payment-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>



                      <?php if($data->type == 'automatic'): ?>

                      <div class="row">
                        <div class="col-lg-4">
                          <div class="left-area">
                              <h4 class="heading"><?php echo e(__('Name')); ?> *</h4>
                              <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                          </div>
                        </div>
                        <div class="col-lg-7">
                          <input type="text" class="input-field" name="name" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e($data->name); ?>" required="">
                        </div>
                      </div>
                      <?php if($data->information != null): ?>
                        <?php $__currentLoopData = $data->convertAutoData(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkey => $pdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php if($pkey == 'sandbox_check'): ?>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) )); ?> *
                                  </h4>
                            </div>
                          </div>

                          <div class="col-lg-7">
                            <label class="switch">
                              <input type="checkbox" name="pkey[<?php echo e(__($pkey)); ?>]" value="1" <?php echo e($pdata == 1 ? "checked":""); ?>>
                              <span class="slider round"></span>
                            </label>
                          </div>
                        </div>

                        <?php else: ?>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) )); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="pkey[<?php echo e(__($pkey)); ?>]" placeholder="<?php echo e(__( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) )); ?>" value="<?php echo e($pdata); ?>" required="">
                          </div>
                        </div>

                        <?php endif; ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <hr>
                       <?php
                           $setCurrency = json_decode($data->currency_id);
                           if($setCurrency == 0){
                             $setCurrency = [];
                           }
                       ?>
                        <?php $__currentLoopData = DB::table('currencies')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dcurr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                             
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <ul class="list">
                              <li>
                                <input class="" name="currency_id[]" <?php echo e(in_array($dcurr->id,$setCurrency) ? 'checked' : ''); ?> type="checkbox" id="currency_id<?php echo e($dcurr->id); ?>" value="<?php echo e($dcurr->id); ?>">
                                <label for="currency_id<?php echo e($dcurr->id); ?>"><?php echo e($dcurr->name); ?></label>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                      <?php endif; ?>

                      <?php else: ?>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Name')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="title" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e($data->title); ?>" required="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Subtitle')); ?> *</h4>
                                <?php if($data->keyword == null): ?>
                                <p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
                                <?php else: ?> 
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                                <?php endif; ?>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <input type="text" class="input-field" name="subtitle" placeholder="<?php echo e(__('Subtitle')); ?>" value="<?php echo e($data->subtitle); ?>">
                          </div>
                        </div>

                        <?php if($data->keyword == null): ?>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              <h4 class="heading">
                                   <?php echo e(__('Description')); ?> *
                              </h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <textarea class="nic-edit" name="details" placeholder="<?php echo e(__('Details')); ?>"><?php echo e($data->details); ?></textarea> 
                          </div>
                        </div>
                        <?php endif; ?>
                      <?php endif; ?>

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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/payment/edit.blade.php ENDPATH**/ ?>