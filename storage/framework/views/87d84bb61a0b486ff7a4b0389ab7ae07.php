

<?php $__env->startSection('content'); ?>

            <div class="content-area">

              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Rewards')); ?> </h4>
                      <ul class="links">
                        <li>
                          <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                        </li>
                      
                        <li>
                          <a href="<?php echo e(route('admin-reward-index')); ?>"><?php echo e(__('Rewards')); ?></a>
                        </li>
                        
                       
                      </ul>
                  </div>
                </div>
              </div>

              <div class="add-product-content1 add-product-content2">
			
                <div class="row">
                  <div class="col-lg-12">
					<div class="heading-area">
						<?php echo $__env->make('includes.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
						<form id="geniusform" action="<?php echo e(route('admin-reward-info-update')); ?>" method="POST">
							<?php echo csrf_field(); ?>
						<h4 class="title">
							<?php echo e(__('Rewards')); ?> :
						</h4>
						
						<div class="action-list mr-2">
							<select class="process select droplinks <?php echo e($gs->is_reward  == 1 ? 'drop-success' : 'drop-danger'); ?>">
							  <option data-val="1" value="<?php echo e(route('admin-gs-is_reward',1)); ?>" <?php echo e($gs->is_reward == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?></option>
							  <option data-val="0" value="<?php echo e(route('admin-gs-is_reward',0)); ?>" <?php echo e($gs->is_reward == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?></option>
							</select>
						  </div>
						  
						  <span><?php echo e(__('Reward Point Number')); ?></span>
						  
						<div class="action-list ml-2 mr-2 d-inline-block">
							<input type="number" min="1" name="reward_point" class="form-control" value="<?php echo e($gs->reward_point); ?>" placeholder="<?php echo e(__('Reward point')); ?>">
						</div>
						<?php echo e(__('To (USD) Dolar ($)')); ?>

						<div class="action-list ml-2">
							<input type="number" min="0" name="reward_dolar" class="form-control" value="<?php echo e($gs->reward_dolar); ?>" placeholder="<?php echo e(__('USD')); ?>">
						</div>
						<div class="action-list ml-2">
							<button class="mybtn1" type="submit">Save</button>
						</div>
					</form>
					</div>
                    <div class="product-description">
                      <div class="body-area">

                        <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                        
						<?php echo $__env->make('includes.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                      <form id="geniusform" action="<?php echo e(route('admin-reward-update')); ?>" method="POST">

                        <?php echo csrf_field(); ?>

                        <div class="featured-keyword-area">
													<div class="feature-tag-top-filds" id="whole-section">
														<?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<div class="feature-area">
															<span class="remove whole-remove"><i
																	class="fas fa-times"></i></span>
															<div class="row">
																
																<div class="col-lg-6">
																	
																	<input type="number" name="order_amount[]"
																		class="input-field"
																		placeholder="<?php echo e(__('Order Amount (USD)')); ?>" min="0" value="<?php echo e($data->order_amount); ?>">
																</div>
																<div class="col-lg-6">
																	<input type="number" name="reward[]"
																		class="input-field"
																		placeholder="<?php echo e(__('Reward')); ?>"
																		min="0" value="<?php echo e($data->reward); ?>" />
																</div>
															</div>
														</div>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													</div>
													<a href="javascript:;" id="whole-btn" class="add-fild-btn"><i
															class="icofont-plus"></i> <?php echo e(__('Add More Field')); ?></a>
												</div>


                        <div class="row text-center">
                          <div class="col-lg-12">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Update Reward')); ?></button>
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
<script type="text/javascript">

// Whole Sell Section

$("#whole-btn").on('click', function(){


$("#whole-section").append(''+
						'<div class="feature-area">'+
							'<span class="remove whole-remove"><i class="fas fa-times"></i></span>'+
								'<div  class="row">'+
									'<div class="col-lg-6">'+
										'<input type="number" name="order_amount[]" class="input-field" placeholder="Order Amount (USD)" min="0" required>'+
									'</div>'+
									'<div class="col-lg-6">'+
										'<input type="number" name="reward[]" class="input-field" placeholder="Reward" min="0" required>'+
									'</div>'+
								'</div>'+
						'</div>'
						+'');        

});

$(document).on('click','.whole-remove', function(){

$(this.parentNode).remove();
if (isEmpty($('#whole-section'))) {

$("#whole-section").append(''+
						'<div class="feature-area">'+
							'<span class="remove whole-remove"><i class="fas fa-times"></i></span>'+
								'<div  class="row">'+
									'<div class="col-lg-4">'+
										'<input type="number" name="order_amount[]" class="input-field" placeholder="Order Amount" min="0" required>'+
									'</div>'+
									
									'<div class="col-lg-4">'+
										'<input type="number" name="reward[]" class="input-field" placeholder="Reward" min="0" required>'+
									'</div>'+
								'</div>'+
						'</div>'
						+'');
}

});

// Whole Sell Section Ends

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/reward/index.blade.php ENDPATH**/ ?>