
<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">
                        					<?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
											<form id="geniusformdata" action="<?php echo e(route('admin-vendor-edit',$data->id)); ?>" method="POST" enctype="multipart/form-data">
												<?php echo e(csrf_field()); ?>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Email")); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="email" class="input-field" name="email" placeholder="<?php echo e(__("Email Address")); ?>" value="<?php echo e($data->email); ?>" disabled="">
													</div>
												</div>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Shop Name")); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_name" placeholder="<?php echo e(__("Shop Name")); ?>" required="" value="<?php echo e($data->shop_name); ?>">
													</div>
												</div>




												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Shop Details")); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
													<textarea class="nic-edit" name="shop_details" placeholder="<?php echo e(__("Details")); ?>"><?php echo e($data->shop_details); ?></textarea> 
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Owner Name")); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="owner_name" placeholder="<?php echo e(__("Owner Name")); ?>" required="" value="<?php echo e($data->owner_name); ?>">
													</div>
												</div>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Shop Number")); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_number" placeholder="<?php echo e(__("Shop Number")); ?>" required="" value="<?php echo e($data->shop_number); ?>">
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Shop Address")); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_address" placeholder="<?php echo e(__("Shop Address")); ?>" required="" value="<?php echo e($data->shop_address); ?>">
													</div>
												</div>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Registration Number")); ?> </h4>
																<p class="sub-heading"><?php echo e(__("(This Field is Optional)")); ?></p>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="reg_number" placeholder="Registration Number" value="<?php echo e($data->reg_number); ?>">
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Message")); ?> </h4>
																<p class="sub-heading"><?php echo e(__("(This Field is Optional)")); ?></p>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_message" placeholder="<?php echo e(__("Message")); ?>" value="<?php echo e($data->shop_message); ?>">
													</div>
												</div>

						                        <div class="row">
						                          <div class="col-lg-4">
						                            <div class="left-area">
						                              
						                            </div>
						                          </div>
						                          <div class="col-lg-7">
						                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__("Submit")); ?></button>
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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/vendor/edit.blade.php ENDPATH**/ ?>