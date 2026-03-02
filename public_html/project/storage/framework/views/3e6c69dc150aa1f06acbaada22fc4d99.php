
<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="mr-breadcrumb">
								<div class="row">
									<div class="col-lg-12">
											<h4 class="heading"><?php echo e(__('Edit Profile')); ?></h4>
											<ul class="links">
												<li>
													<a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
												</li>
												<li>
													<a href="<?php echo e(route('vendor-profile')); ?>"><?php echo e(__('Edit Profile')); ?></a>
												</li>
											</ul>
									</div>
								</div>
							</div>
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">

				                        <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
											<form id="geniusform" action="<?php echo e(route('vendor-profile-update')); ?>" method="POST" enctype="multipart/form-data">
												<?php echo e(csrf_field()); ?>


                      						 <?php echo $__env->make('alerts.vendor.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Shop Name:')); ?> </h4>
														</div>
													</div>
													<div class="col-lg-7">
														<div class="right-area">
																<h6 class="heading"> <?php echo e($data->shop_name); ?>

																	<?php if($data->checkStatus()): ?>
																	<a class="badge badge-success verify-link" href="javascript:;"><?php echo e(__('Verified')); ?></a>
																	<?php else: ?>
																	 <span class="verify-link"><a href="<?php echo e(route('vendor-verify')); ?>"><?php echo e(__('Verify Account')); ?></a></span>
																	<?php endif; ?>
																</h6>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Owner Name')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="owner_name" placeholder="<?php echo e(__('Owner Name')); ?>" required="" value="<?php echo e($data->owner_name); ?>">
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Shop Number')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_number" placeholder="<?php echo e(__('Shop Number')); ?>" required="" value="<?php echo e($data->shop_number); ?>">
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Shop Address')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="shop_address" placeholder="<?php echo e(__('Shop Address')); ?>" required="" value="<?php echo e($data->shop_address); ?>">
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Registration Number')); ?></h4>
																<p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="reg_number" placeholder="<?php echo e(__('Registration Number')); ?>" required="" value="<?php echo e($data->reg_number); ?>">
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__('Shop Details')); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<textarea class="input-field nic-edit" name="shop_details" placeholder="<?php echo e(__('Shop Details')); ?>"><?php echo e($data->shop_details); ?></textarea>
													</div>
												</div>

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
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/profile.blade.php ENDPATH**/ ?>