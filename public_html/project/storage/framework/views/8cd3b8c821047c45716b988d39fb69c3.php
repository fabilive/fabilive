
<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">

                                            <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                                            
                                            <form id="geniusformdata" action="<?php echo e(route('admin-user-vendor-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                                                
                                                <?php echo e(csrf_field()); ?>

                                                
                                                <div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Shop Name')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_name" placeholder="<?php echo e(__('Shop Name')); ?>" required="" value="">
													</div>
												</div>

                                                <div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Owner Name')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="owner_name" placeholder="<?php echo e(__('Owner Name')); ?>" required="" value="">
													</div>
												</div>

                                                <div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Shop Number')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_number" placeholder="<?php echo e(__('Shop Number')); ?>" required="" value="">
													</div>
												</div>

                                                <div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Shop Address')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_address" placeholder="<?php echo e(__('Shop Address')); ?>" required="" value="">
													</div>
												</div>

                                                <div class="row">
													<div class="col-lg-4">
														<div class="left-area">
                                                                <h4 class="heading"><?php echo e(__('Registration Number')); ?> *</h4>
                                                                <p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="reg_number" placeholder="<?php echo e(__('Registration Number')); ?>" value="">
													</div>
												</div>


                                                <div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Shop Details')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
                                                        <textarea name="shop_address" class="input-field" placeholder="<?php echo e(__('Shop Details')); ?>" required></textarea>
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Choose Plan')); ?> :</h4>
														</div>
													</div>
                                                    <div class="col-lg-7">
                                                        <select name="subs_id" required="">
                                                            <?php $__currentLoopData = DB::table('subscriptions')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($subdata->id); ?>"><?php echo e($subdata->title); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
															
														</div>
													</div>
													<div class="col-lg-7">
														<button class="addProductSubmit-btn" type="submit"><?php echo e(__('Submit')); ?></button>
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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/user/setvendor.blade.php ENDPATH**/ ?>